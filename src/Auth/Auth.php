<?php

namespace App\Auth;


/**
*
*/
class Auth
{

	public function user()
	{
		if (isset($_SESSION['user'])) {
			return $_SESSION['user'];
		} else {
			return false;
		}
	}

	public function Authorization(){
		if (isset($_SESSION['Authorization'])) {
			return $_SESSION['Authorization'];
		} else {
			return false;
		}
	}

	public function check()
	{
		return isset($_SESSION['user']);

	}

	public function attempt($user,$Authorization,$refresh_token)
	{

		if(!$user) {
			return false;
		}
		$_SESSION['user'] = $user;
		$_SESSION['Authorization'] = $Authorization;
		$_SESSION['refresh_token'] = $refresh_token;
		return true;
	}

	public function logout()
	{
		unset($_SESSION['user']);
		unset($_SESSION['Authorization']);
		unset($_SESSION['refresh_token']);
	}
}
