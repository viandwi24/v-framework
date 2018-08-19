<?php

use vframework\request\request as Request;
use vframework\base\view;

use app\models\tbauth;			# File Modelnya Untuk Terhubung Ke Table tb_auth
use vframework\lib\redirect;

class UserController {
	public function index(){
		return view::make('user.home', ['data_user' => tbauth::get() ]);
	}

	public function delete($params){
		$proses = tbauth::where('id', $params->id_user)->delete();

		if ( $proses ) {
			return redirect::now('../');
		} else {
			return "Gagal Dihapus : " . $proses;
		}
	}

	public function insert(){
		return view::make('user.form', ['card_title' => "Tambah Data"]);
	}
	public function proses_insert($params, Request $request){
		$data = ['nama' => $request->nama, 'kelas' => $request->kelas, 'email' => $request->email];
		$proses = tbauth::insert($data);

		if ($proses) {
			return redirect::now('../user');
		} else {
			return "Gagal Menambah Data! Error : " . $proses;
		}
	}

	public function update($params){
		$id = $params->id_user;

		$user = tbauth::where('id', $id)->first()->get();

		return view::make('user.form', ['card_title' => "Update User : " . $user->nama, 'user' => $user]);
	}


	public function proses_update($params, Request $request){
		$id = $params->id_user;
		$data = ['nama' => $request->nama, 'kelas' => $request->kelas, 'email' => $request->email];

		$proses = tbauth::where('id', $id)->update($data);

		if ($proses) {
			return redirect::now('../');
		} else {
			return "Gagal Mengupdate Data! Error : " . $proses;
		}
	}
}