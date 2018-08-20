<?php

namespace vframework\lib;

use vframework\lib\session;
use vframework\lib\url;
use vframework\base\error;
use vframework\lib\date;
use vframework\lib\csrf;

class csrf {
	private $key;
	
	public function __construct($key){
		$key = str_replace(' ', '', $key);
		if ($key == '') {
			$this->key = 'vframework';
		} else {
			$this->key = $key;
		}
	}

	public function get(){
		$token 		= password_hash(microtime(true) . "vframework" . date::get('dmYHis') . $this
			->key, PASSWORD_DEFAULT);
		session::set('_csrf', $token);
		session::set('_form', url::now());

		return $token;
	}

	public function verify($token){
		if (!session::get('_csrf') == $token) {
			error::make('csrf_error', get_called_class());
		} else {
			session::delete('_csrf');
		}
	}
}