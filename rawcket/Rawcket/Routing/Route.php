<?php

namespace Rawcket\Routing;

class Route {

	private $app;

	public function __construct($app){
		$this->app = $app;
	}

	private function call($type, $url, $method, $role){
		$this->app->auth->setRole($url, $role);
		return $this->app->$type($url, function () use($method){
			$method = explode('@', $method);
			$controller_name = $method[0] . 'Controller';
			$controller = new $controller_name($this->app);
			call_user_func_array([$controller, $method[1]], func_get_args());
		});
	}

	public function get($url, $method, $role = null){
		return $this->call('get', $url, $method, $role);
	}

	public function post($url, $method, $role = null){
		return $this->call('post', $url, $method, $role);
	}
}