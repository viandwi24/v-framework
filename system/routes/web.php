<?php
use vframework\router\route;

route::get('/', 'HomeController@index');
route::get('/dokumentasi', 'HomeController@docs');