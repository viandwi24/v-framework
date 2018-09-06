<?php

use vifaframework\router\api as route;

/*
|--------------------------------------------------------
| Router API
|--------------------------------------------------------
| Router API adalah router khusus untuk menangani API
| Route yang dibuat disini tidak diberi pengaman CSRF
|
| Ex :
| route::api('GET', '/api/get-table', 'apicontroller@index');
*/