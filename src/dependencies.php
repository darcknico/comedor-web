<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['view'] = function($container){
	$view = new \Slim\Views\Twig(__DIR__ . '/../templates/views' , [
		'cache' => false,
		]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
		));

	$view->getEnvironment()->addGlobal('auth', [
		'check' => $container->auth->check(),
		'user' => $container->auth->user()
		]);

	$view->getEnvironment()->addGlobal('flash', $container->flash);

  $view->addExtension(new Teraone\Twig\Extension\StrftimeExtension());
	return $view;
};

$container['session'] = function ($container) {
    return new \Adbar\Session(
        $container->get('settings')['session']['namespace']
    );
};

$container['HomeController'] = function ($container) {
	return new \App\Controllers\HomeController($container);
};
$container['AuthController'] = function ($container) {
	return new \App\Controllers\Auth\AuthController($container);
};
$container['PasswordController'] = function ($container) {
	return new \App\Controllers\Auth\PasswordController($container);
};

$container['auth'] = function($container){
	return new \App\Auth\Auth;
};
$container['flash'] = function ($container){
	return new \Slim\Flash\Messages;
};
$container['csrf'] = function($container){
	return new \Slim\Csrf\Guard;
};

$container['client'] = function ($container){
  return new \GuzzleHttp\Client([
    'headers' => [ 'Content-Type' => 'application/json' ],
    'base_uri' => 'http://localhost/proyectos/comedor-rest/public/',
    //'base_uri' => 'http://proyectosinformaticos.esy.es/apirest.slim/public/',
    //'base_uri' => 'http://localhost:8080/apirest.slim/public/',
    'timeout'  => 15.0,
  ]);
};
$container['UsuarioController'] = function ($container) {
	return new \App\Controllers\UsuarioController($container);
};
$container['MenuController'] = function ($container) {
	return new \App\Controllers\Comedor\MenuController($container);
};
$container['TicketController'] = function ($container) {
	return new \App\Controllers\Comedor\TicketController($container);
};
