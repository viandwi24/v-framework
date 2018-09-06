<?php

use vifaframework\router\error;
use vifaframework\view\view;
use vifaframework\exception\handle as HandleException;

/*
|--------------------------------------------------------
| Router Error
|--------------------------------------------------------
| Router Error dalah router yang khusus menangani halaman
| error.
|
| System Error berisi : 404 dan csrf_error. Tidak boleh hilang
*/

############## SYSTEM ERROR
error::set('404', function(){
	header("HTTP/1.0 404 Not Found");
	return view::make('default.404');
});
error::set('csrf_error', function($arg){
	try {
		throw new HandleException("Error CSRF Token!", $arg[0], "Lakukan Refresh Kembali Pada Form. Kami Menggunakan CSRF Untuk Mengamankan Form.");				
	} catch (HandleException $e) {
		$e->renderError();
	}
});


############## CUSTOM ERROR
error::set('my_error', function(){
	return "Error !!! This My First Route Error.";
});