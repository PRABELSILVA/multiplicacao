<?php
namespace metas\controlador;

use \Discipulo\Modelo\Discipulo 		as discipulo;
use \Metas\Modelo\Metas					as metas;
use \metas\modelo\participantesMetas 	as participantesMetasModelo;

class participantesMetas
{
    public function index($url)
    {
        $encontroId = $url['4'] ;
        $encontro = new \EncontroComDeus\Modelo\Equipe() ;
        $encontro->encontroComDeusId = $encontroId ;

        $equipes = $encontro->listarEquipeEncontro();

        require_once 'modulos/EncontroComDeus/visao/equipe/listar.php';

    }

    public function listar($url)
    {
        $participante = new participantesMetasModelo() ;
        $participante->metasId = $url[4];
        $participantes = $participante->listar();
        $total = count($participantes);
        require_once 'modulos/metas/visao/participantesMetas/listar.php';

    }

    public function membros($url)
    {
        $equipe = new \EncontroComDeus\Modelo\Equipe() ;
        $equipe->id = $url[4] ;
        $membros = $equipe->membros();
        //var_dump($membros);

        require_once 'modulos/EncontroComDeus/visao/equipe/membros.php';

    }

    public function novoEquipe($url)
    {
      if ( empty ( $url['post'] ) ) {
            $encontroId = $url[4] ;

            $tipoEquipe = new \encontroComDeus\modelo\tipoEquipe();
            $tiposEquipe = $tipoEquipe->listarTodos() ;

            require_once 'modulos/EncontroComDeus/visao/equipe/novo.php';
        } else {

        $post = $url['post'] ;

        $equipe = new \EncontroComDeus\Modelo\Equipe() ;

        $equipe->tipoEquipeId = $post['tipoEquipeId'] ;
        $equipe->encontroComDeusId = $post['encontroId'] ;
        $equipe->salvar() ;

        header ('location:/encontroComDeus/equipe/index/id/'.$equipe->encontroComDeusId );
        exit();
        }

    }

    public function novoMembro($url)
    {
      if ( empty ( $url['post'] ) ) {
            $id = $url[4] ;
            $discipulo = new discipulo() ;
            $discipulo->id = $id ;
            $discipulo = $discipulo->listarUm();

            $encontro = new \encontroComDeus\modelo\encontroComDeus();
            $equipe = new \EncontroComDeus\Modelo\Equipe();
            $equipes = $equipe->listarEquipes() ;

            //var_dump($equipes);

            require_once 'modulos/EncontroComDeus/visao/equipe/novoMembro.php';
        } else {

        $post = $url['post'] ;

        $equipe = new \EncontroComDeus\Modelo\Equipe() ;
        $equipe->id = $post['equipeId'] ;
        $equipe->salvarMembro($post['discipuloId']) ;

        //$redirecionar = $_SERVER['HTTP_REFERER'];
        //header ('location:'.$redirecionar );
        header ('location:/discipulo/discipulo' );
        exit();
        }

    }

    public function novo($url)
    {
        if ( !empty($url[4] )) {
            $discipulo = new discipulo();
            $discipulo->id = $url[4];
            $discipulo = $discipulo->listarUm();

            $meta = new metas() ;
            $meta->discipuloId =$_SESSION['usuario_id']  ;
            $metas = $meta->listar() ;

        //var_dump($metas);

            require_once 'modulos/metas/visao/participantesMetas/novo.php';
            exit;
        } else {
            $post = $url['post'];
            $participante = new participantesMetasModelo();
            $participante->discipuloId =$post['discipuloId'] ;
            $participante->metasId =  $post['metaId'];
            $participante->salvar();
            header ('location:/discipulo/discipulo' );
            exit();
        }
    }

        public function novoMinisterio($url)
        {
            if ( empty ( $url['post'] ) ) {

                require_once 'modulos/ministerio/visao/novoMinisterio.php';

            } else {

            $ministerio =	new \Ministerio\Modelo\Ministerio() ;

            $post = $url['post'] ;
            $ministerio->nome = $post['nome'] ;

            $ministerio->salvar();
            header ('location:/ministerio/listarMinisterio') ;
            exit();
            }

        }

        public function novaFuncao($url)
        {
            if ( empty ( $url['post'] ) ) {

                require_once 'modulos/ministerio/visao/novaFuncao.php';

            } else {

            $funcao =	new \Ministerio\Modelo\Funcao() ;

            $post = $url['post'] ;
            $funcao->nome = $post['nome'] ;

            $funcao->salvar();
            header ('location:/ministerio/listarFuncao') ;
            exit();
            }

        }

        public function listarMinisterio()
        {
                  $ministerios =	new \Ministerio\Modelo\Ministerio();
                  $ministerios = $ministerios->listarTodos();

                  require 'modulos/ministerio/visao/listarMinisterio.php';

        }

        public function listarFuncao()
        {
                  $funcoes =	new \Ministerio\Modelo\Funcao();
                  $funcoes = $funcoes->listarTodos();

                  require 'modulos/ministerio/visao/listarFuncao.php';

        }

        public function atualizar($url)
        {
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

                require_once 'modulos/Discipulo/visao/atualizar.php';

            } else {
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

        public function atualizarMinisterio($url)
        {
            if ( empty ( $url['post'] ) ) {

                $ministerio =	new \Ministerio\Modelo\Ministerio();
                $ministerio->id = $url[3] ;
                $ministerio = $ministerio->listarUm();

                require_once 'modulos/ministerio/visao/atualizarMinisterio.php';

            } else {
                $ministerio =	new \Ministerio\Modelo\Ministerio();

                $post = $url['post'] ;

                $ministerio->id = $post['id'];
                $ministerio->nome = $post['nome'];

                $ministerio->atualizarMinisterio();

                header ('location:/ministerio/atualizarMinisterio/id/'.$ministerio->id);
                exit();
            }

        }

        public function atualizarFuncao($url)
        {
            if ( empty ( $url['post'] ) ) {

                $funcao =	new \Ministerio\Modelo\Funcao();
                $funcao->id = $url[3] ;
                $funcao = $funcao->listarUm();

                require_once 'modulos/ministerio/visao/atualizarFuncao.php';

            } else {
                $funcao =	new \Ministerio\Modelo\Funcao();

                $post = $url['post'] ;

                $funcao->id = $post['id'];
                $funcao->nome = $post['nome'];

                $funcao->atualizarFuncao();

                header ('location:/ministerio/atualizarFuncao/id/'.$funcao->id);
                exit();
            }

        }

        public function excluirMembro($url)
        {
                $equipe =	new \EncontroComDeus\Modelo\Equipe();
                $equipe->equipeId = $url[4];
                $equipe->discipuloId = $url[6];
                $equipe->excluir();

                header ('location:/encontroComDeus/equipe/membros/id/'.$equipe->equipeId );
                exit();
        }

        public function excluirEquipe($url)
        {
                $encontroId = $url[6] ;
                $equipe =	new \EncontroComDeus\Modelo\Equipe();
                $equipe->equipeId = $url[4];
                $equipe->excluirEquipe();

                header ('location:/encontroComDeus/equipe/index/id/'.$encontroId );
                exit();
        }

        public function excluirFuncao($url)
        {
                $funcao =	new \Ministerio\Modelo\Funcao();
                $funcao->id = $url[3];
                $funcao->excluir();

                $_SESSION['mensagem'] = !is_null($funcao->erro) ? $funcao->erro : null ;
                header ('location:/ministerio/listarFuncao');
                exit();
        }

        public function excluir($url)
        {
                $ministerio =	new \Ministerio\Modelo\MinisterioTemDiscipulo();
                $ministerio->discipuloId = $url[3];
                $ministerio->ministerioId = $url[4];
                $ministerio->excluir();
                header ('location:/ministerio/novo/id/'.$ministerio->discipuloId);
                exit();
        }

        public function detalhar ($url)
        {
            $discipulo = new \Discipulo\Modelo\Discipulo() ;

            $discipulo->id = $url[3] ;
            $discipulo = $discipulo->listarUm() ;

            require 'Discipulo/visao/detalhar.php';
        }

        public function detalharFuncao ($url)
        {
            $funcao = new \Ministerio\Modelo\Funcao() ;

            $funcao->id = $url[3] ;
            $funcao = $funcao->listarUm() ;

            require 'ministerio/visao/detalharFuncao.php';

        }

        public function detalharMinisterio ($url)
        {
            $ministerio = new \Ministerio\Modelo\Ministerio() ;

            $ministerio->id = $url[3] ;
            $ministerio = $ministerio->listarUm() ;

            require 'ministerio/visao/detalharMinisterio.php';

        }

        public function chamar ()
        {
            $nome = (!empty($_GET['nome'])) ? $_GET['nome'] : NULL;
            $discipulo =	new \Discipulo\Modelo\Discipulo();
            $discipulo->nome = $nome;
            $discipulos = $discipulo->chamar($nome);
            require_once 'Discipulo/visao/chamar.php';

        }

        public function evento($url)
        {
            if ( empty ( $url['post'] ) ) {

                  $eventos = new \Evento\Modelo\Evento();

                  $id = $url[3];
                  $eventosDiscipulos = $eventos->listarTodosDiscipulo($id);
                $eventos = $eventos->listarTodos();

            require_once 'modulos/Discipulo/visao/evento.php';
            } else {
                      $post = $url['post'];
                     $discipuloEvento = new \Evento\Modelo\Evento();
                      $eventoId = $post['eventoId'];
                        $discipuloId = $post['discipuloId'];

                     $discipuloEvento->salvarDiscipuloEvento($discipuloId, $eventoId );

                      echo "url" ;
                     var_dump($url);
                     $id = $post['discipuloId'];

                     header ('location:/discipulo/evento/id/'.$id);
                     exit();

            }

        }

    }
