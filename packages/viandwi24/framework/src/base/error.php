<?php


namespace vframework\base;

use vframework\router\error as errorRouter;

class error {
	public static function make(){
		$name = func_get_arg(0);
		$arg = func_get_args();
		unset($arg[0]);
		$arg = implode('[][][][][]', $arg);
		$arg = explode('[][][][][]', $arg);

		errorRouter::route($name, $arg);
	}
}