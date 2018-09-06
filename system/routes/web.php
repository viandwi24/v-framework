<?php
use vifaframework\router\web as route;
use app\models\tbauth;

/*
|--------------------------------------------------------
| Router Web
|--------------------------------------------------------
| Router Web atau Router APP adalah router utama yang 
| menangani request url antarmuka web utama.
|
*/

route::get('/', 'HomeController@index')->name('home');

route::prefix('/page', function(){
	route::get('/welcome_page', 'HomeController@welcome_page');
	route::get('/docs_page', 'HomeController@docs_page');
	route::get('/tentang_page', 'HomeController@tentang_page');
	route::get('/demo_page', 'HomeController@demo_page');
});