<?php
namespace oferta\controlador;

use \Discipulo\Modelo\Discipulo ;
use \Oferta\Modelo\TipoOferta as TipoOfertaModelo;
use \Oferta\Modelo\Oferta as OfertaModelo;
use \Oferta\Modelo\Conta as ContaModelo;
use \Celula\Modelo\Celula as CelulaModelo;

class oferta
{
    public function index(){
//			require_once  'modulos/Discipulo/visao/listar.php';
    }

    public function novo($url)
    {
      if ( empty ( $url['post'] ) ) {

        $tiposOfertas = new TipoOfertaModelo() ;
        $ofertasDiscipulo = new OfertaModelo() ;
        $conta = new ContaModelo() ;

        $tiposOfertas = $tiposOfertas->listarTodos();
        $contas = $conta->listarTodos();

        $ofertasDiscipulo->discipuloId= $url[3];

        $ofertasMesAno = array();
        for($i = 1; $i <= 12 ; $i++ ){
            $ofertasMesAno[$i] = $ofertasDiscipulo->discipuloMesAno($i, 2015);
        }

        $ofertasDiscipulo= $ofertasDiscipulo->pegarOfertasDiscipulo();

        $discipulo = new \Discipulo\Modelo\Discipulo() ;
        $discipulo->id = $url[3] ;
        $discipulo = $discipulo->listarUm();

        require_once  'modulos/Oferta/visao/novo.php';

        } else {
            $discipulo = new \Discipulo\Modelo\Discipulo() ;
            $discipulo->id = $url[3] ;
            $discipulo = $discipulo->listarUm();

            $oferta = new OfertaModelo();

            $post = $url['post'] ;
            $oferta->discipuloId = $post['discipuloId'];
            $oferta->tipoOfertaId = $post['tipoOfertaId'];
            $oferta->conta = $post['conta'];
            $oferta->valor = $post['valor'];
            $data = implode('-',array_reverse(explode('/',$post['data'])));

            $oferta->dataOferta = $data;

        if ($oferta->salvar()){
            $headers = "MIME-Version: 1.1\n";
            $headers .= "Content-type: text/plain; charset=utf-8\n";
            $headers .= "From: Multiplicação12 <multiplicaca12@multiplicacao.org>"."\n"; // remetente
            $headers .= "Return-Path: Meu Nome <multiplicacao@multiplicacao.org>"."\n"; // return-path
            $envio = mail("wouerner@gmail.com,".$discipulo->email,
                            "Oferta Recebida",
                            "Data: ".$post['data']." Valor: ".$oferta->valor,
                            $headers,"-r multiplicacao@multiplicacao.org");

                header ('location:/oferta/oferta/novo/'.$oferta->discipuloId);
                exit();
        } else {
            $oferta->atualizar();
            header ('location:/discipulo/discipulo/detalhar/id/'.$oferta->discipuloId);
            exit();
        }
    }

    }

    public function geral($url)
    {
        $tipo = array_key_exists('id', $_GET) ? $_GET['id'] : null ;
        $inativos = array_key_exists('inativos', $_GET) ? $_GET['inativos'] : null ;
        $rede = array_key_exists('rede', $_GET) ? $_GET['rede'] : null ;
        $celulaId = array_key_exists('celula', $_GET) ? $_GET['celula'] : null ;

        $ano = array_key_exists('ano', $_GET) ? $_GET['ano'] : null;
        $mes['inicio'] = array_key_exists('inicio', $_GET) ? $_GET['inicio'] : null;
        $mes['fim'] = array_key_exists('fim', $_GET) ?$_GET['fim'] : null;

        $discipulos = new Discipulo();
        $discipulos = $discipulos->listarFiltro(['celula' => $celulaId]);

        $celula = new CelulaModelo() ;
        $celulas = $celula->listarTodos() ;

        $relatorios = array();
        foreach( $discipulos as $discipulo ) {
            $ofertas = $discipulo->ofertasMesAno(
                $ano,
                $tipo ? $tipo : null,
                !empty($mes['inicio']) ? $mes : null,
                $rede
            );

            $mostrar = false;
            foreach($ofertas as $oferta){
                 if( !empty($oferta ) ){
                    $mostrar = true;
                 }
            }
            $relatorios[] = array(
                            'ofertas'=> $ofertas,
                            'nome'=>$discipulo->nome,
                            'id'=>$discipulo->id,
                            'mostrar'=> $mostrar
                            );
        }

        $tipoOferta = new \Oferta\Modelo\TipoOferta() ;
        $tiposRede = new \Rede\Modelo\TipoRede() ;
        $tiposRede = $tiposRede->listarTodos() ;

        $tipoOferta = $tipoOferta->listarTodos();
        require_once  'modulos/Oferta/visao/oferta/geral.php' ;
    }

    public function novoTipoOferta($url){
        if ( empty ( $url['post'] ) ) {

            require_once  'modulos/oferta/visao/novoTipoOferta.php' ;

        }else{

        $tipoOferta =	new \oferta\modelo\tipoOferta() ;

        $post = $url['post'] ;
        $tipoOferta->nome = $post['nome'] ;

        $tipoOferta->salvar();
        header ('location:/oferta/listarTipoOferta') ;
            exit();
        }


    }

    public function listarTipoOferta(){

              $tipoOfertas =	new \oferta\modelo\tipoOferta();
              $tipoOfertas = $tipoOfertas->listarTodos();

              require 'modulos/oferta/visao/listarTipoOferta.php' ;

    }

    public function atualizar($url){

        if ( empty ( $url['post'] ) ) {

            $discipulo =	new \Discipulo\Modelo\Discipulo();
            $lideres = $discipulo->listarLideres();

            $discipulo->id =  $url[3] ;
            $discipulo = $discipulo->listarUm();

            $lider =	new \Discipulo\Modelo\Discipulo();
            $lider->id = $discipulo['lider'] ;
            $lider = $lider->listarUm($discipulo['lider']);

            $celula = new \Celula\Modelo\Celula();
            $celula->id = $discipulo['celula'];
            $celula = $celula->listarUm();

            $celulas = new \Celula\Modelo\Celula();
            $celulas = $celulas->listarTodos();




            require_once  'modulos/Discipulo/visao/atualizar.php';

        }else {
            $discipulo =	new \Discipulo\Modelo\Discipulo();

            $post = $url['post'] ;

            $discipulo->id = $post['id'];
            $discipulo->nome = $post['nome'];
            $discipulo->telefone = $post['telefone'];
            $discipulo->endereco = $post['endereco'];
            $discipulo->email = $post['email'];
            $discipulo->celula = $post['celula'];
            $discipulo->ativo =isset( $post['ativo']) ? $post['ativo']: null;
            $discipulo->lider = $post['lider'];

            $discipulo->atualizar();

            header ('location:/discipulo/atualizar/id/'.$discipulo->id);
            exit();
        }

    }

    public function atualizarTipoOferta($url){

        if ( empty ( $url['post'] ) ) {


            $tipoOferta =	new \oferta\modelo\tipoOferta();
            $tipoOferta->id = $url[3] ;
            $tipoOferta = $tipoOferta->listarUm();

            require_once  'modulos/oferta/visao/atualizarTipoOferta.php';

        }else {
            $tipoOferta =	new \oferta\modelo\tipoOferta();

            $post = $url['post'] ;

            $tipoOferta->id = $post['id'];
            $tipoOferta->nome = $post['nome'];

            $tipoOferta->atualizar();

            header ('location:/oferta/atualizarTipoOferta/id/'.$tipoOferta->id);
            exit();
        }

    }

    public function excluirTipoOferta($url){
            $tipoOferta =	new \oferta\modelo\tipoOferta();
            $tipoOferta->id = $url[3];
            $tipoOferta->excluir();
            $_SESSION['mensagem'] = !is_null($tipoOferta->erro) ? $tipoOferta->erro : null ;
            header ('location:/oferta/listarTipoOferta');
            exit();
    }

    public function excluir($url){
            $oferta =	new OfertaModelo();
            $oferta->id = $url[4];
            $oferta->excluir();
            header ('location:/oferta/oferta/novo/'.$url[5]);
            exit();
    }


    public function detalhar ($url) {

        $oferta = new \oferta\modelo\tipoOferta() ;

        $oferta->id = $url[3] ;
        $oferta = $oferta->listarUm() ;

        require 'oferta/visao/detalhar.php' ;

    }

    public function geralSemValor($url)
    {
        $tipo = array_key_exists('id', $_GET) ? $_GET['id'] : null ;
        $inativos = array_key_exists('inativos', $_GET) ? $_GET['inativos'] : null ;
        $rede = array_key_exists('rede', $_GET) ? $_GET['rede'] : null ;
        $celulaId = array_key_exists('celula', $_GET) ? $_GET['celula'] : null ;

        $ano = array_key_exists('ano', $_GET) ? $_GET['ano'] : null;
        $mes['inicio'] = array_key_exists('inicio', $_GET) ? $_GET['inicio'] : null;
        $mes['fim'] = array_key_exists('fim', $_GET) ?$_GET['fim'] : null;

        $discipulos = new Discipulo();
        $discipulos = $discipulos->listarFiltro(['celula' => $celulaId]);

        $celula = new CelulaModelo() ;
        $celulas = $celula->listarTodos() ;

        $relatorios = array();
        foreach( $discipulos as $discipulo ) {
            $ofertas = $discipulo->ofertasMesAno(
                $ano ? date('Y'): null,
                $tipo ? $tipo : null,
                !empty($mes['inicio']) ? $mes : null,
                $rede
            );

            $mostrar = false;
            foreach($ofertas as $oferta){
                 if( !empty($oferta ) ){
                    $mostrar = true;
                 }
            }
            $relatorios[] = array(
                            'ofertas'=> $ofertas,
                            'nome'=>$discipulo->nome,
                            'lider'=>$discipulo->getLider(),
                            'id'=>$discipulo->id,
                            'mostrar'=> $mostrar
                            );
        }

        $tipoOferta = new \Oferta\Modelo\TipoOferta() ;
        $tiposRede = new \Rede\Modelo\TipoRede() ;
        $tiposRede = $tiposRede->listarTodos() ;

        $tipoOferta = $tipoOferta->listarTodos();
        require_once  'modulos/Oferta/visao/oferta/geralSemValor.php' ;
    }


}
