<?php

use vframework\kernel\request as Request;
use vframework\base\view;

class HomeController {
	public function index(){
		/* 
		| Render View
		| -----------------------------------------------------------
		| view::make			fungsi untuk me-render file view, render view
		|						akan di return.
		|
		| ('home', array()) 	Ada dua parameter, parameter pertama adalah nama
		|						file view, dan paramter kedua bersifat opsional
		|						yang berfungsi mengirim data berbentuk array ke
		|						file view.
		|
		| ('page.user')			Titik yang ada di parameter pertama (nama view)
		|						akan diubah menjadi slash \ dan ditambahkan .php
		|						Jadi page.user akan sama dengan page\user.php
		|				
		*/
		# Ex :
		//return view::make('home');
		//return view::make('page.user', ['nama_user' => 'John', 'id_user' => 23]);
		//return view::make('admin.dashboard', $user_admin);   # $user_admin  adalah array

		## ---------------------------------------------------------
		## Merender file view bernama "home.php"
		## ---------------------------------------------------------
		return view::make('home');
	}

	public function nama($params, Request $request){
		## Lihat Dokumentasi lengkapnya di : github.com/viandwi24/v-framework

		/* 
		| Paramater Metode Controller :
		| -----------------------------------------------------------
		| $params 				Digunakan untuk mengambil salah satu segment
		|						di url yang telah ditentukan di route.
		|						(parameter pertama)
		|
		| Request $request		Digunakan mengambil value post yang ada.
		|						(parameter kedua).
		*/
		# Ex :
		//echo $params->nama;
		//echo $request->nama;
		

		## ---------------------------------------------------------
		## Merender file view "user", mengambil parameter get dari url
		## lalu mengirim data "nama" ke view.
		## ---------------------------------------------------------
		return view::make('user', ['nama' => $params->nama]);
	}

}