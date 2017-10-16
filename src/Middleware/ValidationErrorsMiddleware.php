<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		if ($this->container->session->has('errors')) {
			$this->container->view->getEnvironment()->addGlobal('errors', $this->container->session->get('errors'));
			$this->container->session->delete('errors');
		}

		$response = $next($request,$response);

		return $response;
	}
}
