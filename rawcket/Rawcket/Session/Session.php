<?php

namespace Rawcket;

class Session {

	public function __construct(){
		if(!isset($_SESSION)){session_cache_limiter(false); session_start();}	
	}

	public function set($key, $value){
		$_SESSION[$key] = $value;
	}

	public function erase($key = null){
		if($key){
			unset($_SESSION[$key]);
		}else{
			session_destroy();
		}
	}

	public function read($key = null){
		return $key?(array_key_exists($key, $_SESSION)?$_SESSION[$key]:false):$_SESSION;
	}

	public function isLogged(){
		return false;
	}
}