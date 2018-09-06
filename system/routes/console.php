<?php

use vifaframework\router\console;

/*
|--------------------------------------------------------
| Router Console
|--------------------------------------------------------
| Router Console adalah router yang mengatur kustomisasi
| perintah konsol di VIFA Console.
|
| Ex :
| 
| console::add('project_name', function(){
|	echo "My Project Name : VIFA FRAMEWORK" ;
| });
*/

console::add('project_name', function(){
	echo "My Project Name : VIFA FRAMEWORK" ;
});

## DEMO INSTALL - CONSOLE
console::add('install_demo', function(){
	$confirm = readline('\nMenginstal Live Demo Offline? menignstall akan mereset route anda. [yes/YES] : ');
	
	if ($confirm == 'yes' or $confirm == 'YES'){
	} else {
		echo "\nMenginstall Dibatalkan.";exit();die();
	}

	echo "\nMereset Route...";
	$route = SYSTEM_ROOT . '/routes/web.php';

	$filenya = <<<EOT
<?php
use vifaframework\\router\\web as route;

/*
|--------------------------------------------------------
| Router Web
|--------------------------------------------------------
| Router Web atau Router APP adalah router utama yang 
| menangani antarmuka web utama.
|
*/

route::get('/', 'HomeController@index')->name('home');

route::prefix('/page', function(){
	route::get('/welcome_page', 'HomeController@welcome_page');
	route::get('/docs_page', 'HomeController@docs_page');
	route::get('/tentang_page', 'HomeController@tentang_page');
	route::get('/demo_page', 'HomeController@demo_page');
});



route::prefix('/demo', function(){
	route::get('/', 'DemoController@index');
	route::get('/insert', 'DemoController@insert');
	route::post('/insert', 'DemoController@proses_insert');
	route::get('/update/{id_user}', 'DemoController@update');
	route::post('/update/{id_user}', 'DemoController@proses_update');
	route::get('/delete/{id_user}', 'DemoController@delete');
});
EOT;

	if (file_put_contents($route, $filenya)) {

	} else {
		echo "\nKesalahan Mereset Route..";exit();die();
	}

	sleep(2);

	echo "\nMembuat File View...";
	$zip = new ZipArchive;
	$res = $zip->open(ROOT . '/assets/view_demo.zip');
	if ($res === TRUE) {
		$zip->extractTo(SYSTEM_ROOT . '/app/views/');
		$zip->close();
	} else {
		echo "\nKesalahan Membuat File View..";exit();die();
	}
	sleep(2);

	echo "\nMembuat File Migrasi...";
	$migration = SYSTEM_ROOT . '/migration/demo.vmf';
	$filenya = <<<EOT
CREATE DATABASE vifaframework;

USE vifaframework;

CREATE TABLE `tb_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kelas` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tb_auth VALUES ('1','Alfian Dwi Nugraha','viandwicyber@gmail.com','X TKJ 1');
INSERT INTO tb_auth VALUES ('2','Deasy Mutiara Wardhani','deasy@gmail.com','X TKJ 2');
EOT;

	if (file_put_contents($migration, $filenya)) {

	} else {
		echo "\nKesalahan Membuat File Migrasi..";exit();die();
	}

	sleep(2);

	echo "\nMembuat File Models...";
	$models = SYSTEM_ROOT . '/app/models/tbauth.php';
	$filenya = <<<EOT
<?php

namespace app\\models;

use vifaframework\\base\\model as BaseModel;

class tbauth extends BaseModel {
	protected static {?}tb_name 	= 'tb_auth';
	protected static {?}id_collum = 'id';

}
EOT;
	
	$filenya = str_replace('{?}', '$', $filenya);
	if (file_put_contents($models, $filenya)) {

	} else {
		echo "\nKesalahan Membuat File Models..";exit();die();
	}
	
	sleep(2);

	echo "\nMembuat File Controllers...";
	$controllers = SYSTEM_ROOT . '/app/controllers/DemoController.php';
	$filenya = <<<EOT
<?php

use vifaframework\\view\\view;
use vifaframework\\http\\Request;

use app\\models\\tbauth;			# File Modelnya Untuk Terhubung Ke Table tb_auth
use vifaframework\\lib\\redirect;

class DemoController {
	public function index(){
		return view::make('demo.home', ['data_user' => tbauth::get() ]);
	}

	public function delete({?}params){
		{?}proses = tbauth::where('id', {?}params->id_user)->delete();

		if ( {?}proses ) {
			return redirect::now('demo');
		} else {
			return "Gagal Dihapus : " . {?}proses;
		}
	}

	public function insert(){
		return view::make('demo.form', ['card_title' => "Tambah Data"]);
	}
	public function proses_insert({?}params, Request {?}request){
		{?}request->validate([
					'nama' 		=> 'required|min:10|max:24',
					'kelas' 	=> 'required',
					'email'		=> 'required|email',
				]);

		{?}data = ['nama' => {?}request->input('nama'), 'kelas' => {?}request->input('kelas'), 'email' => {?}request->input('email')];
		{?}proses = tbauth::insert({?}data);

		if ({?}proses) {
			return redirect::now('demo');
		} else {
			return "Gagal Menambah Data! Error : " . {?}proses;
		}
	}

	public function update({?}params){
		{?}id = {?}params->id_user;

		{?}user = tbauth::where('id', {?}id)->first()->get();

		return view::make('demo.form', ['card_title' => "Update User : " . {?}user->nama, 'user' => {?}user]);
	}


	public function proses_update({?}params, Request {?}request){
		{?}id = {?}params->id_user;		
		{?}data = ['nama' => {?}request->input('nama'), 'kelas' => {?}request->input('kelas'), 'email' => {?}request->input('email')];

		{?}proses = tbauth::where('id', {?}id)->update({?}data);

		if ({?}proses) {
			return redirect::now('demo');
		} else {
			return "Gagal Mengupdate Data! Error : " . {?}proses;
		}
	}
}
EOT;
	$filenya = str_replace('{?}', '$', $filenya);
	if (file_put_contents($controllers , $filenya)) {

	} else {
		echo "\nKesalahan Membuat File Controllers..";exit();die();
	}
	echo "\nSukses Menginstal File Demo!! Lanjutkan Untuk Mengisntal Migrasi Database [demo]";


});