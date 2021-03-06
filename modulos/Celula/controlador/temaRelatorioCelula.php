<?php
use celula\modelo\celula;
use discipulo\Modelo\Discipulo;
namespace celula\controlador;

use \aviso\modelo\tipoAviso;
use \Aviso\Modelo\Aviso;

class temaRelatorioCelula
{
    /*Metodo padrão para o controler
     *
     * */
    public function index()
    {
        $temas =	new \celula\modelo\temaRelatorioCelula() ;

        $temas = $temas->listarTodos();

        require_once 'modulos/Celula/visao/temaRelatorioCelula/listar.php';

    }
        public function novo($url)
        {
            if ( empty ( $url['post'] ) ) {

                require_once 'modulos/Celula/visao/temaRelatorioCelula/novo.php';

            } else {
                $tema =	new \celula\modelo\temaRelatorioCelula();

                $post = $url['post'] ;
                $tema->nome = $post['nome'];
                $tema->dataInicio = implode ('-',(array_reverse (explode('/',$post['dataInicio'])))).' '.$post['tempoInicio'];
                $tema->dataFim = implode ('-',(array_reverse (explode('/',$post['dataFim'])))).' '.$post['tempoFim'];

                $tema->salvar();

                $aviso = new aviso();

                $aviso->tipoAviso = tipoAviso::temaRelatorioNovo ;
                $aviso->identificacao = $tema->id ;
                $aviso->emissor = $_SESSION['usuario_id'];
                $aviso->salvar();

                header ('location:/celula/temaRelatorioCelula');
                exit();
            }

        }

    public function ativar($url)
    {
        $tema =	new \celula\modelo\temaRelatorioCelula() ;

        $tema->id = $url[4] ;
        $tema->ativar() ;

        //var_dump($tema);exit;

        header ('location:/celula/temaRelatorioCelula');
        exit();
    }

    public function desativar($url)
    {
        $tema =	new \celula\modelo\temaRelatorioCelula() ;

        $tema->id = $url[4] ;
        $tema->desativar() ;

        header ('location:/celula/temaRelatorioCelula');
        exit();
    }

        public function atualizar($url)
        {
            if ( empty ( $url['post'] ) ) {

                $celula =	new \Celula\Modelo\Celula();
                $lideres = $celula->listarLideres();

                $celula->id =  $url[4] ;
                $celula = $celula->listarUm() ;

                $lider =	new \Discipulo\Modelo\Discipulo() ;
                $lider->id = $celula->lider ;
                $lider = $lider->listarUm($celula->lider) ;

                require_once 'modulos/Celula/visao/atualizar.php';

            } else {

                $celula =	new \Celula\Modelo\Celula();

                $post = $url['post'] ;
                $celula->nome = $post['nome'];
                $celula->horarioFuncionamento = $post['horarioFuncionamento'];
                $celula->endereco = $post['endereco'];
                $celula->lider = $post['lider'];
                $celula->id = $post['id'];

                $celula->atualizar();

                header ('location:/celula/celula/atualizar/id/'.$celula->id);
                exit();
            }

        }

        public function excluir($url)
        {
                $tema =	new \celula\modelo\temaRelatorioCelula();
                $tema->id = $url[4];
                $tema->excluir();

                $_SESSION['mensagem'] = !is_null($celula->erro) ? $celula->erro : NULL ;
                header ('location:/celula/temaRelatorioCelula');
                exit();

        }

        public function detalhar($url)
        {
            $celula =	new \Celula\Modelo\Celula() ;
            $celula->id = $url[3] ;
            $discipulos= $celula->listarDiscipulos() ;
            $celula = $celula->listarUm() ;

                $lider =	new \Discipulo\Modelo\Discipulo() ;
                $lider->id = $celula->lider ;
                $lider = $lider->listarUm($celula->lider) ;

//				var_dump($discipulos);

            require 'Celula/visao/detalhar.php';

        }

        public function chamar ()
        {
            $nome = isset($_GET['nome']) ? $_GET['nome'] : NULL ;
            $celula =	new \Celula\Modelo\Celula();
            $celula->nome = $nome;
            $celulas = $celula->chamar($nome);
            require_once 'Celula/visao/chamar.php';

        }

        public function lideresCelula()
        {
            $lideres = new \Celula\Modelo\Celula();
            $lideres = $lideres->listarLideresCelula() ;

            require_once 'Celula/visao/listarLideresCelula.php';

        }

    public function participacao($url)
    {
        $celulas =	new \Celula\Modelo\Celula();
        $celulas->id =	$url[4];
        $participacao =	$celulas->listarParticipacao() ;

        require_once 'modulos/Celula/visao/participacao.php';

    }

    }
