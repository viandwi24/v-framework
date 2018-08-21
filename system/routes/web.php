<?php
use vframework\router\route;
use vframework\lib\redirect;

route::get('/', 'HomeController@index')->name('home');
route::get('/dokumentasi', 'HomeController@docs');

route::get('/tes', function(){
	redirect::route('home');
});