<?php

namespace vframework\vte;

use vframework\vte\vte;

class render {
	private $view_dir;

	public function __construct($view_dir){
		$this->view_dir		= $view_dir;

	}

	public function make($file_name, $data = array()){
		$engine 	= new vte();
		return $engine->parse($file_name, $this->view_dir, $data);
	}
}