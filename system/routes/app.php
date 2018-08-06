<?php
use vframework\kernel\route;
use vframework\base\view;

## Lihat Dokumentasi lengkapnya di : github.com/viandwi24/v-framework

/* 
| Routing
| -----------------------------------------------------------
| route							berfungsi untuk mengatur lalu lintas url yang di
|								minta (request) dan memberi hasilnya (response).
|
| ::get dan ::post 				route::get adalah route normal yang dapat menerima
|								semua methode pengiriman tetapi umum digunakan untuk get.
|								route::post akan memfilter hanya request yang memiliki
|								form bertype post saja yang dapat mengakses dan route
|								post akan didahulukan dari pada get.
|
| ('/page', 'Controll@Method')	Parameter pertama berfungsi untuk meng-set url yang akan
|								di route kan, parameter kedua berisi nama file controller
|								dan nama metode di controller tersebut yang akan dipanggil
|								mereka di tulis menjadi satu dan dipisahkan dengan tanda @
*/
# Ex:
// route::get('/page/about-me', 'PageController@about_me');
// route::post('/simpan_data_form', 'PageController@simpan');


/* 
| Middleware
| -----------------------------------------------------------
| afterMiddleware		middleware ini akan diproses setelah controller
|						diakses.
|
| beforeMiddleware 		sebaliknya, Middleware ini akan di proses sebelum 
|						controller diakses.
|						
|
*/
# Ex
// route::post('/simpan_data_form', 'PageController@simpan')->beforeMiddleware('auth');
// route::post('/simpan_data_form', 'PageController@simpan')->afterMiddleware('log', 'auth_cookies');



route::get('/', 'HomeController@index');

route::get('/dokumentasi', function(){
	return view::make('docs');
});

route::get('/tentang', function(){
	$string =  "<b>APP_NAME : </b>".APP_NAME."<br>
				<b>APP_VERSION :</b>".APP_VERSION."<br>
				<b>APP_AUTHOR :</b>".APP_AUTHOR."<br>
			   ";
	return $string;
});