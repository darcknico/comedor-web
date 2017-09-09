<?php

namespace App\Controllers;

use App\Controllers\Controller;
use GuzzleHttp\Exception\TransferException;

class UsuarioController extends Controller{

	public function lista($request,$response)
	{
		$res = $this->client->get('usuario');
    $json = json_decode($res->getBody(), true);
    //$this->flash->addMessage('info', $json['resultado']);
    return $this->view->render($response,'usuario/lista.twig',[
      'data'=>$json['salida']
    ]);
	}

	public function nuevo($request,$response)
	{
		return $this->view->render($response,'usuario/usuario.twig');
	}


	public function get($request,$response,$args){
    try{
      $res = $this->client->get('usuario/'.$args['id']);
      $json = json_decode($res->getBody(), true);
  		return $this->view->render($response, 'usuario/usuario.twig',[
        'old'=>$json['salida']
      ]);
    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.usuario.edit'));
		}
	}

  public function edit($request,$response,$args){
    try{
      $res = $this->client->put('usuario/'.$args['id'],[
        'json' =>[
          'nombre'=> $request->getParam('nombre'),
					'apellido' => $request->getParam('apellido'),
					'estado' => $request->getParam('estado')
        ]
      ]);
      $json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
			return $response->withRedirect($this->router->pathFor('comedor.usuario'));

    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.usuario.edit',[
        'id'=>$args['id']
      ]));
		}
	}

  public function delete($request,$response,$args){
    try{
      $res = $this->client->delete('usuario/'.$args['id']);
      $json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
			return $response->withRedirect($this->router->pathFor('comedor.usuario'));

    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.usuario'));
		}
	}

}
