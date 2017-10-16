<?php
// Routes

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/','HomeController:index')->setName('home');

$app->group('', function () {

	$this->get('/auth/signup','AuthController:getSignup')->setName('auth.signup');
	$this->post('/auth/signup','AuthController:postSignup');

	$this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin','AuthController:postSignIn');

})->add(new GuestMiddleware($container));

$app->group('',function () {

	$this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');

	$this->get('/auth/password/change','PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/auth/password/change','PasswordController:postChangePassword');

	/////////////////////GESTION DE MENUS////////////////////////////
	$this->get('/menu','MenuController:lista')->setName('comedor.menu');
	$this->get('/menu/nuevo','MenuController:nuevo')->setName('comedor.menu.new');
	$this->post('/menu/nuevo','MenuController:post')->setName('comedor.menu.new');
	$this->get('/menu/{id}','MenuController:get')->setName('comedor.menu.edit');
	$this->post('/menu/{id}','MenuController:edit')->setName('comedor.menu.edit');
	$this->get('/menu/{id}/eliminar','MenuController:delete')->setName('comedor.menu.delete');

	$this->get('/menu/{id}/finalizar','MenuController:finalizar')->setName('comedor.menu.finalizar');

	/////////////////////GESTION DE TICKETS////////////////////////////
	$this->get('/menu/{idMenu}/ticket','TicketController:nuevo')->setName('comedor.menu.ticket.new');
	$this->post('/menu/{idMenu}/ticket','TicketController:post')->setName('comedor.menu.ticket.new');

	$this->get('/ticket','TicketController:lista')->setName('comedor.ticket');
	$this->get('/ticket/{id}','TicketController:delete')->setName('comedor.ticket.delete');

	$this->get('/menu/{fecha}/validar','TicketController:empezar')->setName('comedor.ticket.start');
	$this->post('/menu/{id}/{fecha}/validar/','TicketController:validar')->setName('comedor.ticket.next');
	$this->post('/ticket/validar','TicketController:validarAjax')->setName('comedor.ticket.validar');
	$this->get('/menu/{id}/cerrar','TicketController:cerrar')->setName('comedor.ticket.close');

	$this->get('/ticket/validar/codigo','TicketController:comprobarTicket')->setName('comedor.ticket.codigo');

	/////////////////////GESTION DE USUARIOS////////////////////////////
	$this->get('/usuario','UsuarioController:lista')->setName('comedor.usuario');
	$this->get('/usuario/nuevo','UsuarioController:nuevo')->setName('comedor.usuario.new');
	$this->post('/usuario/nuevo','UsuarioController:post')->setName('comedor.usuario.new');
	$this->get('/usuario/{id}','UsuarioController:get')->setName('comedor.usuario.edit');
	$this->post('/usuario/{id}','UsuarioController:edit')->setName('comedor.usuario.edit');
	$this->get('/usuario/{id}/eliminar','UsuarioController:delete')->setName('comedor.usuario.delete');

})->add(new AuthMiddleware($container));
