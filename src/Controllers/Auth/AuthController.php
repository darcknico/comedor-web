<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller{

	public function getSignOut($request,$response)
	{
		$this->auth->logout();

		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignIn($request,$response)
	{
		return $this->view->render($response,'auth/signin.twig');
	}

	public function postSignIn($request,$response)
	{
		try{
			$respuesta = $this->client->post('acceder',
				['json'=> [
					'dni' => $request->getParam('dni'),
					'contraseña' => $request->getParam('contraseña')
				]
			]);
			$json = json_decode($respuesta->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
			$this->auth->attempt($json['salida']);
			return $response->withRedirect($this->router->pathFor('home'));

		} catch (ClientException $e) {
			$respuesta = $e->getResponse();
			$json = json_decode($respuesta->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			$_SESSION['errors']= $json['salida'];
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}
	}


	public function getSignup($request,$response
		){

		return $this->view->render($response, 'auth/signup.twig');
	}

	public function postSignup($request,$response)
	{
		try{
			$respuesta = $this->client->post('registrar',
				['json'=> [
					'nombre'=> $request->getParam('nombre'),
					'dni' => $request->getParam('dni'),
					'contraseña' => $request->getParam('contraseña')
				]
			]);
			$json = json_decode($respuesta->getBody(), true);
			$this->flash->addMessage('info', $json['resultado']);
			$this->auth->attempt($json['salida']);
			return $response->withRedirect($this->router->pathFor('home'));

		} catch (ClientException $e) {
			$respuesta = $e->getResponse();
			$json = json_decode($respuesta->getBody(), true);
			$this->flash->addMessage('error', $json['resultado']);
			$_SESSION['errors']= $json['salida'];
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}
	}
}
