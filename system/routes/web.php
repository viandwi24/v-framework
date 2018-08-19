<?php
use vframework\router\route;

#### OPSIONAL
use vframework\base\view; 		# Untuk Render File View
use vframework\config\config; 	# Untuk mengambil value configurasi
use app\models\tbauth;

route::get('/', 'HomeController@index'); 	# Contoh Route Dengan Controller

route::get('/dokumentasi', function(){		# Contoh Route Dengan Fungsi Langsung.	
	return view::make('default.docs');
});

route::get('/tentang', function(){
	$data = [
		'app_name' => config::app('APP_NAME'),
		'app_vers' => config::app('APP_VERS'),
		'app_author' => config::app('APP_AUTHOR'),
		'app_company' => config::app('APP_COMPANY'),
			];

	return view::make('default.tentang', $data);
});

route::get('/tes', function(){
	return view::make('tes', ['nama' => 'alfian']);
});


#### EXAMPLE CRUD ROUTE
route::get('/user', 'UserController@index');

route::get('/user/insert', 'UserController@insert');
route::post('/user/insert', 'UserController@proses_insert');

route::get('/user/update/{id_user}', 'UserController@update');
route::post('/user/update/{id_user}', 'UserController@proses_update');

route::get('/user/delete/{id_user}', 'UserController@delete');