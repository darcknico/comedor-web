<?php

namespace App\Controllers\Comedor;

use App\Controllers\Controller;
use GuzzleHttp\Exception\TransferException;

class MenuController extends Controller{

	public function lista($request,$response)
	{
		$res = $this->client->get('menu');
    $json = json_decode($res->getBody(), true);
    return $this->view->render($response,'comedor/menu/lista.twig',[
      'data'=>$json['salida'],
			'old'=>null
    ]);
	}

	public function nuevo($request,$response)
	{
		return $this->view->render($response,'comedor/menu/menu.twig');
	}

	public function post($request,$response)
	{
    $date = date('d-m-Y',strtotime($request->getParam('fecha')));

		try{
			$res = $this->client->post('menu',
				['json'=> [
					'fecha' => $date,
					'cantidad' => $request->getParam('cantidad'),
					'precio' => $request->getParam('precio'),
					'descripcion' => $request->getParam('descripcion')
				]
			]);
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
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
      $res = $this->client->get('menu/'.$args['id']);
      $old = json_decode($res->getBody(), true);
			$res = $this->client->get('menu');
			$data = json_decode($res->getBody(), true);
  		return $this->view->render($response, 'comedor/menu/lista.twig',[
				'old'=>$old['salida'],
				'data'=>$data['salida']
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
			//return $response->withRedirect($this->router->pathFor('comedor.menu.edit'));
			return $response->withRedirect($this->router->pathFor('comedor.menu'));
		}
	}

  public function edit($request,$response,$args){
    try{
      $res = $this->client->put('menu/'.$args['id'],[
        'json' =>[
          'cantidad'=> $request->getParam('cantidad'),
					'precio' => $request->getParam('precio'),
					'descripcion' => $request->getParam('descripcion')
        ]
      ]);
      $json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
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
			return $response->withRedirect($this->router->pathFor('comedor.menu.edit',[
        'id'=>$args['id']
      ]));
		}
	}

  public function delete($request,$response,$args){
    try{
      $res = $this->client->delete('menu/'.$args['id']);
      $json = json_decode($res->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
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

	public function finalizar($request,$response,$args){
    try{
      $res = $this->client->post('menu/'.$args['id']);
      $json = json_decode($res->getBody(), true);
  		return $response->withRedirect($this->router->pathFor('home'));
    } catch (TransferException $e) {
			$res = $e->getResponse();
			$json = json_decode($res->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			if($res->getStatusCode()==400) {
      	$_SESSION['errors']= $json['salida'];
			} elseif (isset($json['salida'])) {
				$this->flash->addMessage('warning', $json['salida']);
			}
			return $response->withRedirect($this->router->pathFor('comedor.ticket.start'));
		}
	}
}
