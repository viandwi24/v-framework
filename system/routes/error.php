<?php

use vframework\router\error;
use vframework\exception\handle as HandleException;

##### System Error
error::set('404', function(){
	return "404";
});
error::set('csrf_error', function($arg){
	try {
		throw new HandleException();				
	} catch (HandleException $e) {
		$e->renderError("Error CSRF Token!", $arg[0], "Lakukan Refresh Kembali Pada Form. Kami Menggunakan CSRF Untuk Mengamankan Form.");
	}
});