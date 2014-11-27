<?php

namespace Rawcket\Auth;

class AuthMiddleware extends \Slim\Middleware {
	public function call(){
		$app = $this->app;

		$this->next->call();
	}
}