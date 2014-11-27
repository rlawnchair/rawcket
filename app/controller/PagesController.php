<?php



class PagesController extends BaseController{


	public function index(){
		$this->app->render('template.php');
	}

	public function test(){
		$this->app->render('test/test.php');
	}

	public function postTest(){
		$request = $this->app->request();
		$body = $request->getBody();
		//var_dump($request);
		var_dump($body);
		var_dump($_POST);
		//die('lul');
	}
}