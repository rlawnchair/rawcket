<?php
require '../vendor/autoload.php';

//Config
$app = new \Slim\Slim();
$app->config([
		'debug' => true,
		'templates.path' => '../app/views/'
	]);


// Dependencies
$app->form = function(){
	return new \Rawcket\Html\Form();
};
$app->session = new \Rawcket\Session();


// Middleware
$app->add(new \Rawcket\Auth\AuthMiddleware());


// Routes -- Global
$route = new \Rawcket\Routing\Route($app);
$route->get('/', 'Pages@index')->name('root');
$route->get('/test', 'Pages@test')->name('test');
$route->post('/test', 'Pages@postTest')->name('postTest');

// Route -- Login
$route->get('/login', 'Login@index')->name('login');
$route->post('/login', 'Login@postLogin')->name('postLogin');


// Render
$app->render('header.php', compact('app'));
$app->render('nav.php');
$app->run();
$app->render('footer.php');

