<?php

namespace Rawcket\Auth;
	
class Auth{

	private $auth;

	public function setRole($url, $role){
		$this->auth[$url] = $role;
	}
}