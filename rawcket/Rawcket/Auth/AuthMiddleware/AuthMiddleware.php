<?php

namespace Rawcket\Auth;

class AuthMiddleware extends \Slim\Middleware {
	public function call(){
		$this->app->hook('slim.before.dispatch',[$this, 'onBeforeDispatch']);
		$this->next->call();
	}

	public function onBeforeDispatch(){
		$route = $this->app->router()->getCurrentRoute()->getPattern();
		$role = $this->app->auth->roleFor($route);
		/*if(isset($role)){
			if(!in_array('all', $role)){
				if(!$this->app->session->isLogged()){
					if(in_array('admin', $role)){
							$this->app->redirect('login');
					}
				}
			}
		}*/
		if(isset($role) && !in_array('all',$role) && !$this->app->session->isLogged() && in_array('admin', $role)){
			$this->app->redirect('login');
		}
	}
}