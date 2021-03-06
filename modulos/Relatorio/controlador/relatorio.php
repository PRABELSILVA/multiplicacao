<?php
namespace relatorio\controlador ;

use discipulo\Modelo\Discipulo ;

class relatorio
{
    public function discipulos()
    {
        $discipulos = new \Discipulo\Modelo\Discipulo();
        $discipulos= $discipulos->listarTodosDiscipulos();

        require 'Relatorio/visao/discipulos.php';

    }

    public function celulas()
    {
        $celulas = new \Celula\Modelo\Celula();
        $celulas= $celulas->listarTodos();

        require 'Relatorio/visao/celulas.php';

    }

    public function statusCelular()
    {
        $statusCelulares = new \StatusCelular\Modelo\StatusCelular();
        $statusCelulares= $statusCelulares->listarStatusCelularTodos();

        require 'Relatorio/visao/statusCelular.php';

    }

    public function statusCelularPorTipo($url)
    {
        $statusCelulares = new \StatusCelular\Modelo\StatusCelular();
        $statusCelulares->tipoStatusCelular = $url['2'] ;
        $statusCelulares= $statusCelulares->listarStatusCelularPorTipo();
        $status= $statusCelulares[0]['status'];
        require 'Relatorio/visao/statusCelularTipo.php';

    }

    public function statusCelularIndex($url)
    {
        $statusCelulares = new \StatusCelular\Modelo\TipoStatusCelular();
        $statusCelulares= $statusCelulares->listarTodos();

        require 'Relatorio/visao/statusCelularIndex.php';

    }


    public function relatorioResumido($url)
    {
        $post = $url['post'];
    if (empty($post)) {
        $tipoStatusCelulares = new \StatusCelular\Modelo\TipoStatusCelular();
        $tipoStatusCelulares= $tipoStatusCelulares->listarTodos();

        $estadoCivies = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivies = $estadoCivies->listarTodos();

        $celulas = new \Celula\Modelo\Celula();
        $celulas = $celulas->listarTodos();

        $tipoRedes = new \Rede\Modelo\TipoRede();
        $tipoRedes = $tipoRedes->listarTodos();

        $lideres = new \Discipulo\Modelo\Discipulo();
        $lideres = $lideres->listarTodosDiscipulos();

        require 'Relatorio/visao/discipulosResumido.php';
    } else {

        $idadeMinima = new \DateTime( "now" ,new \DateTimeZone('America/Sao_Paulo')) ;
        $idadeMaxima = new \DateTime( "now" ,new \DateTimeZone('America/Sao_Paulo')) ;

        $idadeMinima->sub(new \DateInterval('P'.$post['idadeMinima'].'Y') );
        $idadeMaxima->sub(new \DateInterval('P'.$post['idadeMaxima'].'Y') );

        $sexo = $post['sexo'];
        $estadoCivil = $post['estadoCivil'];
        $status = $post['tipoStatusCelular'];
        $celula = $post['celula'];
        $rede = $post['rede'];
        $ativo = $post['ativo'];
        $lider = $post['lider'];

        $relatorio = new \Relatorio\Modelo\Discipulos();

        $relatorio = $relatorio->discipulosResumido($idadeMaxima->format('Y-m-d'), $idadeMinima->format('Y-m-d') ,$sexo,$estadoCivil,$status , $celula , $rede,$ativo , $lider);

        if ($sexo != 'todos') {
            $sexo = ($post['sexo']=='m') ? "Masculino" : "Feminino";
        }

        if ($estadoCivil != 'todos') {
        $estadoCivil = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivil->id = $post['estadoCivil'];
        $estadoCivil = $estadoCivil->listarUm();
        }

        if ($status != 'todos') {
        $status = new \StatusCelular\Modelo\TipoStatusCelular();
        $status->id = $post['tipoStatusCelular'];
        $status = $status->listarUm();
        }

        if ($celula != 'todos') {
        $celula = new \Celula\Modelo\Celula();
        $celula->id = $post['celula'];
        $celula = $celula->listarUm();
        }

        if ($estadoCivil != 'todos') {
        $estadoCivil = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivil->id = $post['estadoCivil'];
        $estadoCivil = $estadoCivil->listarUm();
        }

        if ($rede != 'todos') {
        $rede = new \Rede\Modelo\TipoRede();
        $rede->id = $post['rede'];
        $rede = $rede->listarUm();
        }

    //	$status = $post['tipoStatusCelular'];
    //	$celula = $post['celula'];
    //	$rede = $post['rede'];
    //var_dump($relatorio);
        //
        $total = count($relatorio);
        $count = 0 ;
        require 'Relatorio/visao/discipulosResumidoRelatorio.php';
    }

    }

    public function relatorioResumidoImprimir($url)
    {
        $post = $url['post'];
    if (empty($post)) {
        $tipoStatusCelulares = new \StatusCelular\Modelo\TipoStatusCelular();
        $tipoStatusCelulares= $tipoStatusCelulares->listarTodos();

        $estadoCivies = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivies = $estadoCivies->listarTodos();

        $celulas = new \Celula\Modelo\Celula();
        $celulas = $celulas->listarTodos();

        $tipoRedes = new \Rede\Modelo\TipoRede();
        $tipoRedes = $tipoRedes->listarTodos();

        $lideres = new \Discipulo\Modelo\Discipulo();
        $lideres = $lideres->listarTodosDiscipulos();

        require 'Relatorio/visao/discipulosResumido.php';
    } else {

        $idadeMinima = new \DateTime( "now" ,new \DateTimeZone('America/Sao_Paulo')) ;
        $idadeMaxima = new \DateTime( "now" ,new \DateTimeZone('America/Sao_Paulo')) ;

        $idadeMinima->sub(new \DateInterval('P'.$post['idadeMinima'].'Y') );
        $idadeMaxima->sub(new \DateInterval('P'.$post['idadeMaxima'].'Y') );

        $sexo = $post['sexo'];
        $estadoCivil = $post['estadoCivil'];
        $status = $post['tipoStatusCelular'];
        $celula = $post['celula'];
        $rede = $post['rede'];
        $ativo = $post['ativo'];
        $lider = $post['lider'];

        $relatorio = new \relatorio\modelo\discipulos();

        $relatorio = $relatorio->discipulosResumido($idadeMaxima->format('Y-m-d'), $idadeMinima->format('Y-m-d') ,$sexo,$estadoCivil,$status , $celula , $rede,$ativo , $lider);

        if ($sexo != 'todos') {
            $sexo = ($post['sexo']=='m') ? "Masculino" : "Feminino";
        }

        if ($estadoCivil != 'todos') {
        $estadoCivil = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivil->id = $post['estadoCivil'];
        $estadoCivil = $estadoCivil->listarUm();
        }

        if ($status != 'todos') {
        $status = new \StatusCelular\Modelo\TipoStatusCelular();
        $status->id = $post['tipoStatusCelular'];
        $status = $status->listarUm();
        }

        if ($celula != 'todos') {
        $celula = new \Celula\Modelo\Celula();
        $celula->id = $post['celula'];
        $celula = $celula->listarUm();
        }

        if ($estadoCivil != 'todos') {
        $estadoCivil = new \Discipulo\Modelo\EstadoCivil();
        $estadoCivil->id = $post['estadoCivil'];
        $estadoCivil = $estadoCivil->listarUm();
        }

        if ($rede != 'todos') {
        $rede = new \Rede\Modelo\TipoRede();
        $rede->id = $post['rede'];
        $rede = $rede->listarUm();
        }

    //	$status = $post['tipoStatusCelular'];
    //	$celula = $post['celula'];
    //	$rede = $post['rede'];
    //var_dump($relatorio);
        //
        $total = count($relatorio);
        $count = 0 ;
        require 'Relatorio/visao/discipulosResumidoRelatorio.php';
    }

    }

    public function liderComDiscipulos ()
    {
        $relatorio = new \relatorio\modelo\discipulos();

        $relatorio = $relatorio->liderComDiscipulos();

//		var_dump($relatorio);

        require 'Relatorio/visao/liderComDiscipulos.php';

    }

    public function graficoPorStatusCelular ()
    {
        $relatorio = new \relatorio\modelo\discipulos();
        $relatorio = $relatorio->graficoPorStatusCelular();

        require 'Relatorio/visao/graficoPorStatusCelular.php';

    }

    public function graficoPorCelula ($url)
    {
    if (empty($url['post'])) {

        $celulas = new \Celula\Modelo\Celula();
        $celulas = $celulas->listarTodos();
        require 'Relatorio/visao/graficoPorCelula.php';
    } else {
        $celula = $url['post'];

        $celulas = new \Celula\Modelo\Celula();

        $celulas->id = $celula['celula'];
        $nomeCelula = $celulas->listarUm();

        $celulas = $celulas->listarTodos();

        $relatorio = new \relatorio\modelo\discipulos();
        $relatorio = $relatorio->graficoPorCelula($celula['celula']);

        require 'Relatorio/visao/graficoPorCelula.php';

    }

    }

    public function graficoPorEvento ()
    {
        $relatorio = new \relatorio\modelo\discipulos();
        $relatorio = $relatorio->graficoPorEvento();

        require 'Relatorio/visao/graficoPorEvento.php';

    }

    public function aniversariantes($url)
    {
        $post = $url['post'];

        if ( !empty($post) ) {
            $relatorios = new \Relatorio\Modelo\Discipulos();
            $relatorios = $relatorios->aniversarianteMes($post['data']);

            require 'Relatorio/visao/aniversariantes.php';
        } else {
            require 'Relatorio/visao/aniversarios.php';
        }
    }

    public function relatorioCelula($url)
    {
        if ($url['post']) {

        $inicio = explode('/',$url['post']['inicio']);
        $inicio =$inicio[2]. '-' .$inicio[1].'-'.$inicio[0];
        $fim = explode('/',$url['post']['fim']);
        $fim = $fim[2].'-'.$fim[1].'-'.$fim[0];

        $relatorios = \celula\modelo\relatorioCelula::porData($inicio,$fim);
        }

        require 'Relatorio/visao/relatorioCelula.php';

    }

    public function relatorioCelulaEnvio($url)
    {
        if ($url['post']) {

        $inicio = explode('/',$url['post']['inicio']);
        $inicio =$inicio[2]. '-' .$inicio[1].'-'.$inicio[0].' '.$url['post']['tempoInicio'];
        $fim = explode('/',$url['post']['fim']);
        $fim = $fim[2].'-'.$fim[1].'-'.$fim[0].' '.$url['post']['tempoFim'] ;

        $relatorios = \celula\modelo\relatorioCelula::envioPorCelula($inicio,$fim);
        }

        require 'Relatorio/visao/relatorioCelulaEnvio.php';

    }

    public function relatorioCelulaEnvioPorTema($url)
    {
        $tema = new \celula\modelo\temaRelatorioCelula() ;
        //$temas = $tema->listarTodosAtivos();
        $temas = $tema->listarTodos();

        $tipoRede = new \Rede\Modelo\TipoRede();
        $tipoRede = $tipoRede->listarTodos() ;

        if ($url['post']) {

            $tema->id = $url['post']['temaId'];
            $tipoRedeId = $url['post']['tipoRedeId'];
            $tema = $tema->listarUm();

            $relatorio = new \celula\modelo\relatorioCelula() ;
            $relatorio->temaRelatorioCelulaId = $url['post']['temaId'] ;
            $relatorios = $relatorio->listarTodosPorTemaRede($tipoRedeId) ;
            $cont=0;
        }

        require 'modulos/relatorio/visao/relatorioCelulaPorTema.php';

    }

  public function statusPorLider($url)
  {
        $post=$url['post'];

        $lideres = new \Discipulo\Modelo\Discipulo();
        $lideres = $lideres->listarTodosDiscipulos();

        $tiposStatus = new \StatusCelular\Modelo\TipoStatusCelular();
        $tiposStatus =  $tiposStatus->listarTodos();

        if (!empty($post)) {

        $liderId = 	$post['liderId'] ;
        $statusCelularId = $post['statusCelularId'] 	;
        $relatorio = new \relatorio\modelo\discipulos();
        $relatorio = $relatorio->statusPorLider($liderId, $statusCelularId);

        }
        $total = 0 ;
        require 'modulos/relatorio/visao/statusPorLider.php';
//		var_dump($relatorio);

    }

}
