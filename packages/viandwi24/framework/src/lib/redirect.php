<?php

namespace vframework\lib;

class redirect {
	public function now($url){

		$url = explode('/', $url);
		foreach ($url as $u_k => $u_v) {
			if ($u_v == ''){
				unset($url[$u_k]);
			}
		}
		$url = implode('/', $url);
		return header('location:'.$url);
	}
}