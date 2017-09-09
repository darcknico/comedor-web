<?php

namespace App\Controllers\Comedor;

use App\Controllers\Controller;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;

class TicketController extends Controller{

	public function lista($request,$response)
	{
    try{
  		$res = $this->client->get('ticket',[
        'headers'=> [
          'token' => $this->auth->user()['token']
        ]
      ]);
      $json = json_decode($res->getBody(), true);
      return $this->view->render($response,'comedor/ticket/lista.twig',[
        'data'=>$json['salida']
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
			return $response->withRedirect($this->router->pathFor('home'));
		}
	}

	public function nuevo($request,$response,$args)
	{
    try{
			$res = $this->client->get('menu/'.$args['idMenu']);
			$json = json_decode($res->getBody(), true);
			//$this->flash->addMessage('info', $json['resultado']);
			return $this->view->render($response,'comedor/menu/ticket.twig',[
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
			return $response->withRedirect($this->router->pathFor('comedor.menu'));
		}

	}

	public function post($request,$response,$args)
	{
    var_dump('menu/'.$args['idMenu'].'/ticket');
		try{
			$res = $this->client->post('menu/'.$args['idMenu'].'/ticket',
				['headers'=> [
					'token' => $this->auth->user()['token']
				]
			]);
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('success', $json['resultado']);
			return $response->withRedirect($this->router->pathFor('comedor.menu'));

		} catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.menu'));
		}
	}


	public function get($request,$response,$args){
    try{
			$us = $this->client->get('usuario',[
        'headers'=> [
          'token' => $this->auth->user()['token']
        ]
      ]);
			$json = json_decode($us->getBody(), true);

			$this->auth->attempt($json['salida']);
      $res = $this->client->get('menu/'.$args['id']);
      $json = json_decode($res->getBody(), true);
  		return $this->view->render($response, 'comedor/menu/menu.twig',[
        'old'=>$json['salida'][0]
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
			return $response->withRedirect($this->router->pathFor('comedor.menu.edit'));
		}
	}


  public function delete($request,$response,$args){
    try{
      $res = $this->client->delete('ticket/'.$args['id']);
      $json = json_decode($res->getBody(), true);
			$this->flash->addMessage('success', $json['resultado']);
			return $response->withRedirect($this->router->pathFor('comedor.ticket'));
    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.ticket'));
		}
	}

  public function empezar($request,$response,$args){
		try{
      $res = $this->client->get('menu',[
        'headers' =>[
          'fecha'=> $args['fecha']
        ]
      ]);
      $json = json_decode($res->getBody(), true);
			return $this->view->render($response, 'comedor/ticket/validar.twig',[
        'menu'=>$json['salida']
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
			return $response->withRedirect($this->router->pathFor('home'));
		}
  }
	public function comprobarTicket($request,$response,$args){
		try{
			$res = $this->client->get('ticket',[
				'headers'=> [
          'codigo' => $request->getQueryParam('codigo'),
        ]
			]);
			return $response->withJson(json_decode($res->getBody(), true)['salida']);
    } catch (TransferException $e) {
			return $response->withStatus(400);
		}
  }

	public function validar($request,$response,$args){
		try{
			$res = $this->client->put('ticket/'.$request->getParam('id'),[
				'json'=>[
					'idMenu' => $args['id']
				]
			]);
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
			return $response->withRedirect($this->router->pathFor('comedor.ticket.start',[
        'fecha'=>$args['fecha'],
				'usuario'=>$json['salida']['usuario']
      ]));
			/*
			return $this->view->render($response, 'comedor/ticket/validar.twig',[
        'menu'=>$json['salida']['menu'],
				'usuario'=>$json['salida']['usuario']
      ]);
			*/
    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.ticket.start',[
        'fecha'=>$args['fecha']
      ]));
		}
  }
}
