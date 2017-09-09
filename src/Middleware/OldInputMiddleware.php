<?php

namespace App\Middleware;

class OldInputMiddleware extends Middleware{
	
	public function __invoke($request, $response, $next){
		//if (session_status() == PHP_SESSION_NONE) {
		//	session_start();
		//}
		if (isset($_SESSION['old'])) {
			$this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
		}
		$_SESSION['old'] = $request->getParams();

		$response = $next($request,$response);

		return $response;
	}
}