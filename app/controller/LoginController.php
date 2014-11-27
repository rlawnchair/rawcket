<?php

class LoginController extends BaseController{
	public function index(){
		$this->app->render('auth/login.php');
	}

	public function postLogin(){
		//Load model
		//...
		$this->app->flash('lel', 'Identifiant Incorrect');
		$this->app->redirect('login');
	}
}