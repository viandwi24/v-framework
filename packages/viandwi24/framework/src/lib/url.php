<?php

namespace vframework\lib;

use vframework\config\config;
use vframework\router\kernel;

class url {

	public static function base($data = null){
		if ($data == null) {
			$output = config::get("base_url");
		} else {
			$data = explode('/', $data);
			foreach ($data as $d_k => $d_v) {
				if ($d_v == '') {
					unset($data[$d_k]);
				}
			}
			$output = config::get("base_url") . "/" . implode('/', $data);
		}
		return $output;
	}

	public static function now(){
		$requrl = router::GetReqUrl(true);

		return implode('/', $requrl);
	}
}