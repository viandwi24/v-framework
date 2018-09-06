<?php

namespace vifaframework\lib;

use vifaframework\lib\session;
use vifaframework\lib\url;
use vifaframework\router\error;
use vifaframework\lib\date;
use vifaframework\lib\csrf;

class csrf {
	private $key;
	private $token = null;
	
	public function __construct($key){
		$key = str_replace(' ', '', $key);
		if ($key == '') {
			$this->key = 'vifaframework';
		} else {
			$this->key = $key;
		}
	}

	public function get(){
		if ($this->token == null)
		{
			$token 		= password_hash(microtime(true) . "vifaframework" . date::get('dmYHis') . $this
				->key, PASSWORD_DEFAULT);
			$app = session::get('__app__');
			session::set('_csrf', $token);

			$app['last_form'] = url::now();
			session::set('__app__', $app);
			$this->token = $token;
		}
		
		return $this->token;
	}

	public function verify($token){
		if (!session::get('_csrf') == $token or session::get('_csrf') == null) {
			return false;
		} else {
			session::set('_csrf', null);
			return true;
		}
	}
}