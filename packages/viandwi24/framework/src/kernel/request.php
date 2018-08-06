<?php

namespace vframework\kernel;

class request {
	public function __construct(){
		foreach ($_POST as $p_k => $p_v) {
			$this->{$p_k} = $p_v;
		}
	}
}