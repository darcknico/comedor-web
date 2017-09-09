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

	public function check()
	{
		return isset($_SESSION['user']);

	}

	public function attempt($user)
	{

		if(!$user) {
			return false;
		}
		$_SESSION['user'] = $user;
		return true;
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}
}
