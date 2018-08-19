<?php

use vframework\request\request as Request;
use vframework\base\view;

class HomeController {
	public function index(){
		return view::make('home', ['nama' => 'alfian']);
	}
}