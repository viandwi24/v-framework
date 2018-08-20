<?php

use vframework\base\view;
use vframework\request\HTTPRequests as Request;

class HomeController {
	public function index(){
		return view::make('default.home');
	}
	public function docs(){
		return view::make('default.docs');
	}
}