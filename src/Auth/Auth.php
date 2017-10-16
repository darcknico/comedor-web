<?php

namespace App\Auth;


/**
*
*/
class Auth
{

	public function user()
	{
		$session = new \Adbar\Session('slim_app');
		if ($session->has('user')) {
			return $session->get('user');
		} else {
			return false;
		}
	}

	public function Authorization(){
		$session = new \Adbar\Session('slim_app');
		if ($session->has('Authorization')) {
			return $session->get('Authorization');
		} else {
			return false;
		}
	}

	public function check()
	{
		$session = new \Adbar\Session('slim_app');
		return $session->has('user');
	}

	public function attempt($user,$Authorization,$refresh_token)
	{
		$session = new \Adbar\Session('slim_app');
		if(!$user) {
			return false;
		}
		$session->set('user', $user);
		$session->set('Authorization', $Authorization);
		$session->set('refresh_token', $refresh_token);
		return true;
	}

	public function logout()
	{
		$session = new \Adbar\Session('slim_app');
		$session->delete('user');
		$session->delete('Authorization');
		$session->delete('refresh_token');
		$session->clear();
	}
}
