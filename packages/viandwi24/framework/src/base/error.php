<?php


namespace vframework\base;

use vframework\router\error as errorRouter;

class error {
	public static function make($name){
		errorRouter::route($name);
	}
}