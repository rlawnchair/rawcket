<?php

namespace Rawcket;
	
class Auth{

	private $auth = [];
	public $id;

	public function __construct(){
		$this->id =  uniqid();
	}

	public function setRole($url, $role){
		if(array_key_exists($url, $this->auth)){
			array_push($this->auth[$url], $role);
		}else{
			$this->auth[$url] = [$role];
		}
	}

	public function roleFor($url){
		return array_key_exists($url, $this->auth) ? $this->auth[$url] : null;
	}
}