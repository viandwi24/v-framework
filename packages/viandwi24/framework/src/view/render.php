<?php

namespace vifaframework\view;

use vifaframework\view\vte;

class render {
	private $view_dir;

	public function __construct($view_dir){
		$this->view_dir		= $view_dir;

	}

	public function make($file_name, $_data = array()){
		$engine 	= new vte();
		return $engine->parse($file_name, $this->view_dir, $_data);
	}
}