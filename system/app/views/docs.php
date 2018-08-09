<!DOCTYPE html>
<html>
<head>
	<title>SIMPEL DOKUMENTASI | VIFA FRAMEWORK</title>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Roboto');	
		* {
			padding: 0;
			margin: 0;
			font-family: 'Roboto', sans-serif;
		}	
		body {
  			background: #e2e1e0;
  		}
		header.header {
			background: #FF3D00;
			display: block;
			color: white;
			padding: 15px;
			box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			z-index: 10;
		}
		header.header .header-name {
			color: white;
			font-size: 20px;
		}
		.card {
		  	background: #fff;
		  	border-radius: 2px;
		  	display: inline-block;
			box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
			margin-top: 10px;
			margin-bottom: 10px;
		}
		.card-body {
			padding: 15px;
		}
		.container {
			margin-top: 75px;
			margin-left: 100px;
			margin-right: 110px;
			margin-bottom: 50px;
		}
		.box-code {
			background: #424242;
			padding: 10px;
			color:white;
			margin-top: 5px;
		}
		hr {
			margin-bottom: 10px;
		}
		h2 {
			margin-bottom: 5px;
		}
		ul {
			margin-top: 5px;
			margin-left: 17px;
			margin-bottom: 10px;
		}
		p {
			margin-bottom: 5px;
			margin-top: 5px;
		}
		h3 {
			margin-top: 5px;
			margin-bottom: 5px;
		}
	</style>
</head>
<body>
	<header class="header">
		<div class="header-name">VIFA FRAMEWORK</div>
	</header>	

	<div class="container">
		<h1 style="margin-top: 50px;">
			SIMPEL DOCUMENTATION 
			<small style="font-size: 14px;"><a href="<?php echo base_url();?>">Kembali Ke Home</a></small>
		</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<b>Telah Dirilis :</b>
				<ul>
					<li><a href="#router">Router</a></li>
					<li><a href="#controller">Controller</a></li>
					<li><a href="#view">View</a></li>
					<li><a href="#console">Console</a></li>
					<li><a href="#lib-db">Library - DB</a></li>
				</ul>
				<b>Coming Soon :</b>
				<ul>
					<li>Model (Dalam Pengembangan)</li>
					<li>Middleware (Telah Ada Tetapi Dalam Uji Coba)</li>
				</ul>
			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				
				<h2 id="router"># Router</h2><hr>
				<p>
					<b>Router</b> berguna untuk mengarahkan lalu lintas web agar
					dapat user yang melakukan request dapat mendapat response.<br>
					Secara default file router berada pada :
				</p>
				<pre class="box-code">system\routes\app.php</pre>

				<p>
					Kami mendukung 2 metode route yaitu <b>route::get()</b> dan <b>route::post</b>.
					<br><b>route::post()</b> akan memfilter apakah request memiliki pengiriman data
					dengan metode POST atau tidak. <b>route::post</b> juga akan lebih di prioritaskan daripada metode <b>route::get()</b>
				</p>
				<p>Secara umum route kami dituliskan seperti berikut :</p>
				<pre class="box-code">route::get('/about', 'PageController@about');</pre>
				<ul>
					<li><b>'/about'</b> adalah url yang didaftarkan/akan di route</li>
					<li><b>PageController</b> adalah nama controller</li>
					<li><b>about</b> adalah nama metode/fungsi (method/function) yang akan dipanggil didalam controller <b>PageController</b></li>
				</ul>
				<p>Kode Diatas jika diterjemahkan dalam bahasa manusia adalah "Jika ada request yang menuju ke url <b>/about</b> maka panggil metode <b>about</b> yang ada di controller <b>PageController</b></p>

				<p>Atau anda juga dapat menulis fungsi langsung kedalam route tanpa harus meneruskanya ke Controller.</p>
				<pre class="box-code">route::get('/about', function(){
	echo "Hello Wolrd!";
});</pre>		<br>
				<h3>Menerima Parameter Segmen Url</h3>
				<p>
					Berhubung dengan router, terkadang kita perlu mengambil data melalui salah satu
					segment url. Misal saja ada url seperti ini :
				</p>
				<pre class="box-code">/user/viandwicyber</pre>
				<p>
					Lalu untuk mengambil nama user di segment kedua kita perlu menambahkan seperti berikut didalam router :
				</p>
				<pre class="box-code">route::get('/user/{nama}', 'Controller@user');</pre>
				<p>Lalu gunakan kode berikut untuk mengambil $nama di controller :</p>
				<pre class="box-code">public function user ($params) {
	echo 'Nama Kamu Adalah : ' . $params->nama;
}</pre>
				<p>
					Atau anda juga bisa langsung menulisnya dalam router tanpa harus ke controller :
				</p>
				<pre class="box-code">route::get('/user/{nama}', function($params) {
	return echo "Nama Kamu Adalah : " . $params->nama;
}</pre>
				<br>
				<h3>Menerima Parameter Segmen Url Bersifat Opsional</h3>
				<p>
					Jika pada kasus diatas url harus terisi, kadang kita butuh mengambil data pada segmen url tetapi segmen tersebut bersifat opsional (bisa diisi atau tidak). Tambahkan beriktu didalam Router kalian :
				</p>
				<pre class="box-code">route::get('/page/{nomor?}', 'Controller@page');</pre>
				<p>
					Dengan begitu jika url dikosongi sebagai contoh ada yang mengakses <b>/page/</b> maka
					dia akan tetap dialihkan ke route tersebut.
				</p>
				<br><br><br>
				<h2 id="router"># Router - Error</h2><hr>
				<p>
					Tidak hanya router untuk web, kami juga pada versi <b>1.3.2</b> telah merilis fitur
					route error, yang berguna untuk menampung segala bentuk error.
				</p>
				<p>File Router Error Berada Pada : </p>
				<pre class="box-code">system\routes\error.php</pre>
				<p>
					Ada beberapa Route Error Dari Kami Untuk System Yang Tidak Boleh Dihapus! Salah satunua adalah <b>system_error</b> dan <b>404</b>, Kami
					telah menandainya dengan komentar agar kalian tidak menghapusnya.
				</p>
				<p>Untuk Membuat Custom Error Anda Sendiri, Cukup Buat Baris Baru Lalu Ketik :</p>
				<pre class="box-code">route::error("404", function($arg){
	return "Error 404! - $arg[0]";
});</pre>
			<p>Lalu untuk panggil di controller :</p>
			<pre class="box-code">error::make("404", "Url Tidak Ditemukan.");</pre>
			<p>jangan Lupakan Library Error Untuk Meloadnya di controller :</p>
			<pre class="box-code">use vframework\kernel\error;</pre>
			<br><br>
			<p>Contoh Lainya Adalah Berikut :</p>
			<pre class="box-code">####### system\routes\app.php
route::error("user_404", function($arg){
	return "Error!, User dengan nama " . $arg[0] . " dan email " . $arg[1] . " tidak ditemukan!";
});



####### Lalu Tulis berikut Di Controller
use vframework\kernel\error;

class PageController {

	public function user(){
		return error::custom("user_404", "Alfian Dwi Nugraha", "fiandwi0424@gmail.com");
	}

}



##### lalu output yang dihasilkan adalah :
Error!, User dengan nama Alfian Dwi Nugraha dan email fiandwi0424@gmail.com tidak ditemukan!</pre>

			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="controller"># Controller</h2><hr>
				<p>Folder Controller berada pada :</p>
				<pre class="box-code">system\app\controllers
				</pre>
				<p>
					Contoh Class kosong dari controller adalah sebagia berikut :
				</p>
				<pre class="box-code">&lt;?php

use vframework\kernel\request as Request;
use vframework\base\view;

class HomeController {
}</pre>
			<p>
				Nama File Dan Nama Class Juga Harus Sama Dalam Bentuk Apapun, Diatas Nama Class adalah <b>HomeController</b>, Maka Kalian Juga Harus Menyimpan Class Tersebut Dengan Nama <b>HomeController.php</b>.
			</p>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="view"># View</h2><hr>
				<p>Folder view berada pada :</p>
				<pre class="box-code">system\app\views</pre>
				<br>
				<h3>Render View Di Controller</h3>
				<p>Untuk merender view kita perlu menggunakan library berikut didalam controller anda :</p>
				<pre class="box-code">use vframework\base\view;</pre>
				<p>Lalu gunakan fungsi berikut untuk merender view di dalam metode controller:</p>
				<pre class="box-code">function home(){
	return view::make('home');
}</pre>	
				<p>
					Kode diatas akan memanggil file home.php di dalam folder <b>system\app\views</b>. 
					lalu bagaimana jika kita membuat folder baru di dalam folder views dan ingin
					memanggil salah satu file didalamnya? Cukup gunakan titik untuk membatasi dir 
					seperti berikut :
				</p>
				<pre class="box-code">view::make('page.home');</pre>
				<p>kode diatas akan memanggil file <b>system\app\views\page\home.php</b></p>
				<br>
				<h3>Mengirim data dari Controller ke View</h3>
				<p>Karena Controller tidak bisa terhubung alngsung, kalian perlu melewatkan data tersebut
					melalui parameter kedua di fungsi view::make
				</p>
				<pre class="box-code">view::make('home', ['nama' => 'alfian dwi', 'umur' => 16]);</pre>
				<p>Lalu untuk mengeluarkan data yang dikirim, tuliskan kode berikut di view :</p>
				<pre class="box-code">&lt;?php echo "Nama Mu : $nama dan Umur Mu : $umur";?&gt;</pre>
				<br>
				<h3>Lainya</h3>
				<p><b># Mengeluarkan Base Url Di View File : </b></p>
				<pre class="box-code">&lt;a href="&lt;?php echo base_url();?&gt;"&gt;Klik Disini&lt;/a&gt; </pre>
				<pre class="box-code">&lt;a href="&lt;?php echo base_url('artikel/post/1');?&gt;"&gt; GO &lt;/a&gt; </pre>
				<p><b># Mengeluarkan Url Sekarang (Now Url) Di View File : </b></p>
				<pre class="box-code">&lt;a href="&lt;?php echo now_url();?&gt;"&gt;Refresh Halaman&lt;/a&gt; </pre>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="console"># Console</h2><hr>
				<p>
					Console mode cli membantu anda dalam pembuatan projek dengan vframework.
				</p>
				<p>
					Cukup arahkan terminal / cmd ke base root projeck v-cms lalu ketik :
				</p>
				<pre class="box-code">php vifa</pre>
				<p>Lalu akan muncul tampilan berikut :</p>
				<img src="assets/img/upload/1.png">
				<br><br>
				<h4>Contoh Pengaplikasian :</h4>
				<p>Membuat File Controller Baru : </p>
				<pre class="box-code">php vifa add:controller HomeController</pre>
				<p><b>HomeController</b> merupakan nama controller yang akan dibuat.</p>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="lib-db"># Library - DB</h2><hr>
				<p>Library Ini Mendukung mysqli saja.</p>
				<p><b>Class Library DB :</b></p>
				<pre class="box-code">use vframework\lib\DB;</pre>
				<p><b>Konfigurasi Database :</b></p>
				<pre class="box-code">system\config\db.php</pre>
				
				<hr style="margin-top: 15px;">
				<h3>Menampilkan</h3>
				<p>
					Untuk menampilkan data (read) anda memerlukan fungsi GET().
				</p>
				<pre class="box-code">db::tb("tabel_admin")->get();</pre>
				<p>
					<b>tb()</b> merupakan fungsi untuk meng-set nama tabel, lalu <b>get()</b> untuk mengambil result data.
				</p>
				<br><br>
				<p><b># Dengan Where</b> :</p>
				<pre class="box-code">db::tb("tabel_admin")->where('id', 1)->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tabel WHERE id='1'</p>
				<pre class="box-code">db::tb("tabel_admin")->where('level','!=', 'user')->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tabel WHERE level!='user'</p>
				<pre class="box-code">db::tb("tabel_admin")->where('id', 1)->where('level', 'admin')->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tabel WHERE id='1' AND level='admin'</p>

				<hr style="margin-top: 15px;">
				<h3>Menambah</h3>
				<p>
					Untuk menambah data (insert) anda memerlukan fungsi INSERT().
				</p>
				<pre class="box-code">db::tb("tabel_admin")->insert($array);</pre>
				<br><br>
				<p><b># INSERT satu data</b> :</p>
				<pre class="box-code">
$data = ['nama' => 'alfian dwi', 'umur' => 16];
$proses = db::tb("tabel_admin")->insert($data);
if ($proses) {
	echo "Berhasil";
}
</pre>
				<p><b># INSERT lebih dari satu data</b> :</p>
				<pre class="box-code">
$data = [
			['nama' => 'alfian dwi', 'umur' => 16],
			['nama' => 'deasy mutiara', 'umur' => 17],
			['nama' => 'dutta sadewa', 'umur' => 18],
		];
$proses = db::tb("tabel_admin")->insert($data);
if ($proses) {
	echo "Berhasil";
}
</pre>	
				<hr style="margin-top: 15px;">
				<h3>Memperbarui</h3>
				<p>
					Untuk memperbarui data (update) anda memerlukan fungsi UPDATE().
				</p>
				<p><b># Update Satu Kolom :</b></p>
				<pre class="box-code">db::tb("tabel_admin")->where('id', 1)->update('nama', 'alfian dwi n');</pre>
				<p><b># Update lebih dari satu kolom :</b></p>
				<pre class="box-code">
$array_update = ['nama' => 'alfian dwi nugraha', 'umur' => 17];
db::tb("tabel_admin")->where('id', 1)->update($array_update);
</pre>

				<hr style="margin-top: 15px;">
				<h3>Menghapus</h3>
				<p>
					Untuk menghapus data (delete) anda memerlukan fungsi DELETE().
				</p>
				<pre class="box-code">db::tb("tabel_admin")->where('id', 1)->delete();</pre>

			</div>
		</div>
	</div>
</body>
</html>