<?php

namespace vframework\request;

use vframework\lib\csrf;
use vframework\config\config;

class request {
	public function __construct(){
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$csrf 			= new csrf(config::get('uniq_key'));

			if (!isset($_POST['_csrf'])) $_POST['_csrf'] = '';
			$csrf->verify($_POST['_csrf']);
			
			foreach ($_POST as $key => $value) {
				$this->{$key} = $value;
			}
		}


	}
}