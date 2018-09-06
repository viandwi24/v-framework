<?php

use vifaframework\view\view;
use vifaframework\http\Request;
use vifaframework\config\config;

class HomeController {
	public function index(){
		return view::make('default.home');
	}
	public function welcome_page(){
		return view::make('default.page.welcome');
	}
	public function docs_page(){
		return view::make('default.page.docs');
	}
	public function tentang_page(){
		$data = [
					'app' => [
								'NAME' => config::app('APP_NAME'),
								'VERS' => config::app('APP_VERS'),
								'AUTHOR' => config::app('APP_AUTHOR'),
								'COMPANY' => config::app('APP_COMPANY')
							] 
				];
		return view::make('default.page.about', $data);
	}
	public function demo_page(){
		return view::make('default.page.demo');
	}
}