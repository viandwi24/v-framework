<?php

namespace vframework\lib;

class date {
	public static function init(){
		date_default_timezone_set('Asia/Jakarta');
	}

	public static function get($format){
		self::init();
		
		return date($format);
	}
}