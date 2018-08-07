<?php
use vframework\kernel\route;
use vframework\base\view;

#### DONT REMOVE THIS
route::error('system_error', function($arg){ 
	$string = "<b> Error </b>: " . $arg[0] .
			  '<div style="border: 1px solid red;text-align: center;padding:5px;">'
			  . $arg[1] .
			  '</div>';

	return $string;
});
route::error('404', function($arg){
	return view::make('error.404', ['description' => 'Alamat URL Yang Diminta Tidak Ada.']);
});

#### Tulis Route Error Lainya Dibawah Ini