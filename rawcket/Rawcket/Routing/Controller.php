<?php

namespace Rawcket\Routing;

abstract class Controller{
	protected $app;

	public function __construct($app){
		$this->app = $app;
	}
}