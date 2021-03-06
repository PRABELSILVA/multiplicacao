<?php

namespace Aviso\Modelo;

use \Framework\Modelo\ModeloFramework;

class Aviso extends ModeloFramework
{
    private $id;
    private $emissor;
    private $tipoAviso;
    private $dataAviso;
    private $identificacao;

    public function __get($prop)
    {
        return $this->$prop ;
    }

    public function __set($prop, $valor)
    {
        $this->$prop = $valor ;
    }

    public function salvar()
    {
        $pdo = self::pegarConexao();

        $sql = "INSERT INTO Avisos (emissor , tipoAvisoId, dataAviso ,  identificacao )
        VALUES (?, ?, NOW(),?)";

        $stm = $pdo->prepare($sql);

        $stm->bindParam( 1 , $this->emissor );
        $stm->bindParam( 2 , $this->tipoAviso );
        $stm->bindParam( 3 , $this->identificacao );

        $resposta = $stm->execute();

        $pdo = null ;

        return $resposta;
    }

    public function listarTodos()
    {
        $pdo = self::pegarConexao();

        $sql = '
                        SELECT d.nome, d.alcunha, a.id, f.url, a.visto, a.identificacao, a.dataAviso, ta.modulo, ta.controlador, ta.acao, ta.link,ta.mensagem, ta.icone, ta.cor
                         FROM
                         Discipulo AS d
                         inner join Foto as f on f.discipuloId = d.id
                        inner join
                        Avisos AS a on d.id = a.emissor
                        inner join
                        TipoAviso AS ta
                        on a.tipoAvisoId = ta.id
                        order by a.dataAviso DESC
                        ';

        $stm = $pdo->prepare($sql);

        $stm->execute();

        return $stm->fetchAll();

    }
    public function listarTimeline()
    {
        $pdo = self::pegarConexao();

        $sql = '
            SELECT d.nome, a.id, a.identificacao, a.dataAviso AS startDate, ta.modulo,
            ta.controlador, ta.acao, ta.link,ta.mensagem, ta.icone
                         FROM
                        Discipulo AS d
                        inner join
                        Avisos AS a on d.id = a.emissor
                        inner join
                        TipoAviso AS ta
                        on a.tipoAvisoId = ta.id
                        order by a.dataAviso DESC
                        limit 1
                        ';

        $stm = $pdo->prepare($sql);

        $stm->execute();

        return $stm->fetchAll();

    }
    public function listarUltimos()
    {
        $pdo = self::pegarConexao();

        $sql = 'SELECT d.nome , d.alcunha , a.id, a.identificacao, a.dataAviso, ta.modulo,
            ta.controlador, ta.acao, ta.link,ta.mensagem, ta.icone, f.url FROM
        Discipulo AS d
        inner join
        Avisos AS a on d.id = a.emissor
        inner join
        TipoAviso AS ta
        on a.tipoAvisoId = ta.id
        left join
        Foto as f
        on d.id = f.discipuloId
        order by a.dataAviso DESC
        limit 10
    ';

        $stm = $pdo->prepare($sql);

        $stm->execute();

        return $stm->fetchAll();

    }

    /* Exclui um evento associado a um discipulo.
     *
     *
     *
     */
    public function excluir()
    {
        $pdo = new \PDO (DSN,USER,PASSWD);

        $sql = 'DELETE FROM Avisos WHERE id = ?';

        $stm = $pdo->prepare($sql);

        $stm->bindParam(1, $this->id);

        $stm->execute();

    }

    /*Lista apenas um Disicpulo
    */

    public function listarUm()
    {
        $pdo = new \PDO (DSN,USER,PASSWD);

        $sql = 'SELECT * FROM Admissao, TipoAdmissao WHERE discipuloId = ? AND Admissao.tipoAdmissao = TipoAdmissao.id Limit 1';

        $stm = $pdo->prepare($sql);

        $stm->bindParam(1, $this->discipuloId);

        $stm->execute();

        return $stm->fetch();

    }

    public function visto()
    {
        $pdo = self::pegarConexao();
        //cria sql
        $sql = "UPDATE Avisos SET visto = 1 WHERE id = ?";
        //prepara sql
        $stm = $pdo->prepare($sql);
        //trocar valores
        $stm->bindParam(1, $this->id);

        $resposta = $stm->execute();

        //var_dump($stm->errorInfo());
        //exit;

        //fechar conexÃ£o
        $pdo = null ;

        return $resposta;
    }

    public function listarTodosDiscipulo($url)
    {
        $pdo = new \PDO(DSN, USER, PASSWD);

        $sql = '
        SELECT DISTINCT id , nome
        FROM DiscipuloTemEvento , Evento
        WHERE DiscipuloTemEvento.discipuloId = ? AND Evento.id = DiscipuloTemEvento.eventoId
        ' ;

        $stm = $pdo->prepare($sql);

        $stm->bindParam(1, $url);

        $stm->execute() ;

        return $stm->fetchAll();

    }

}
