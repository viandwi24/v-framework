<?php
use vifaframework\lib\url;
use vifaframework\lib\csrf;
use vifaframework\lib\session;
use vifaframework\config\config;
use vifaframework\lib\history;

/*
|--------------------------------------------------------
| Global Function
|--------------------------------------------------------
| Global Function adalah sebuah file untuk mengumpulkan
| fungsi yang bersifat global atau fungsi yang dapat diakses
| dari mana pun.
|
*/

if (!function_exists('base_url')){
	function base_url($url = ''){
		return url::base($url);
	}
}
if (!function_exists('dd')){
	function dd($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}
}
if (!function_exists('csrf_input')){
	function csrf_input(){
		$csrf = new csrf(config::get('uniq_key'));
		return '<input type="hidden" name="_csrf" value="'.$csrf->get().'">';
	}
}
if (!function_exists('csrf_token')){
	function csrf_token(){
		$csrf = new csrf(config::get('uniq_key'));
		return $csrf->get();
	}
}
if (!function_exists('assets')){
	function assets($url = ''){
		return url::base('assets/'.$url);
	}
}
if (!function_exists('appSession')){
	function appSession($key = ''){
		if ($key == '')
		{
			return session::get('__app__');
		} else 
		{
			return session::get('__app__')[$key];
		}
	}
}
if (!function_exists('previous')){
	function previous($type)
	{
		$app = session::get('__app__');
		$type = strtolower($type);

		switch ($type) {
			case 'page':
				if (isset($app['previous_page']))
				{
					return $app['previous_page'];
				} else 
				{
					return null;
				}
				break;
			
			case 'form':
				if (isset($app['last_form']))
				{
					return $app['last_form'];
				} else 
				{
					return null;
				}
				break;
			default:
				return null;
				break;
		}
	}
}


function history()
{
	return history::getInstance();
}