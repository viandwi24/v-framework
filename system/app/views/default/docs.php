@extends('default.template')

@set('title', 'DOKUMENTASI |')
@content('isi')
		<h1 style="margin-top: 50px;">
			DOKUMENTASI
			<small style="font-size: 14px;"><a href="<?php echo base_url();?>">Kembali Ke Home</a></small>
		</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<h2>Daftar Isi</h2><hr>
				<b>Dasar :</b>
				<ul>
					<li><a href="#router">Router</a></li>
					<li><a href="#controller">Controller</a></li>
					<li><a href="#view">View</a></li>
					<li><a href="#model">Model</a></li>
					<li><a href="#csrf">CSRF</a></li>
					<li><a href="#httprequest">HTTPRequests</a></li>
					<li><a href="#console">Vifa Console</a></li>
					<li><a href="#vte">Vifa Template Engine</a></li>
				</ul>
				<b>Library :</b>
				<ul>
					<li><a href="#lib-db">DB</a></li>
					<li><a href="#lib-session">Session</a></li>
					<li><a href="#lib-redirect">Redirect</a></li>
					<li><a href="#lib-url">Url</a></li>
				</ul>

				<hr>
				
				<b>Router Lanjutan :</b>
				<ul>
					<li><a href="#router-error">Router Error</a></li>
					<li>Router Console <b>[Coming Soon]</b></a></li>
					<li>Router API <b>[Coming Soon]</b></li>
				</ul>

				<b>View Lanjutan :</b>
				<ul>
					<li><a href="#view-url">base_url() dan now_url()</a></li>
				</ul>

				<b>Vifa Console Lanjutan :</b>
				<ul>
					<li>Daftar Perintah Console <b>[Coming Soon]</b></li>
					<li>Migration <b>[Coming Soon]</b></li>
				</ul>
			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				
				<h2 id="router"># Router</h2><hr>
				<p>
					<b>Router</b> berfungsi sebagai pengatur lalu lintas request url yang ada, Router
					akan mengambil url yang di request oleh user lalu mengarahkan nya ke tujuan yang
					sudah di tetapkan di pengaturan route.
				</p><br>
				<p>File Route Web :</p>
				<pre class="box-code">system\routes\web.php</pre><br>

				<hr>
				
				<p><b>Normal Route</b></p>
				<pre class="box-code">route::get('/artikel/html', 'HomeController@index');</pre>
				<p> <b>'/artikel/html'</b> adalah url yang akan di route kan, <b>HomeController</b> adalah Nama Controller dan <b>index</b> adalah Nama Metode Di Controller Tersebut, </p>
				<p>Dengan begitu, maksudnya dalah jika ada request url <b>'/artikel/html'</b> maka akan dialihkan ke dalam metode fungsi <b>index</b> yang ada di controller <b>HomeController</b>.</p><br>

				<p><b>Route Langsung</b></p>
				<pre class="box-code">
route::get('/', function(){
	return "Hello World!";
});
</pre>
				<p>
					Route langsung adalah route yang langsung di jalanakn aksi nya tanpa harus
					memanggil controller terlebih dahulu.					
				</p><br>

				<hr>

				<p><b>Kami Menyediakan 4 Macam Metode Route :</b></p>
				<ul>
					<li>GET</li>
					<li>POST</li>
					<li>PUT</li>
					<li>DELETE</li>
				</ul>
				<p>
					Masing - Masing Metode Route Memiliki Prioritas berdasarkan level prioritas nya :
				</p>
				<pre style="text-align: center;" class="box-code"> PUT == DELETE => POST => GET </pre>
				<p>Dilihat Dari Prioritas Level Diatas, PUT dan DELETE Sejajar. Maka Prioritas 
					Route Yang Didahulukan Terlebih dahulu adalah PUT dan DELETE, jika route tidak ada
					maka akan di arahkanke metode POST, jika route POST tidak ada maka akan dilajutkan
					diarahkan ke metode GET. Dan Jika ROute GET tidak ditemukan juga maka request akan 
					dihentikan dan ditampilkan pemberitahuan error 404.
				</p><br>

				<p><b>Contoh Dari Prioritas Route Adalah Berikut :</b></p>
				<pre class="box-code">
route::get('/form_tambah', 'PageController@tampilkan_form');
route::post('/form_tambah', 'PageController@simpan_db');</pre>
				
				<p>
					2 Route Diatas Memiliki Route Url Yang Sama Tetapi Memiliki Aksi yang berbeda,
					maka akan berlaku prioritas route yang mana route::post akan didahulukan dari pada
					route get.
				</p>
				<ul>
					<li>
						<b>route::put</b> akan dijalankan jika suatu request memiliki key post "_put".
					</li>
					<li>
						<b>route::delete</b> akan dijalankan jika suatu request memiliki key post "_delete".
					</li>
					<li>
						<b>route::post</b> akan di jalankan jika suatu request memiliki request method form post didalamnya. Jika Tidak maka akan diabaikan.
					</li>
					<li>
						<b>route::get</b> dijalankan tanpa ketentuan, dia juga dapat mengambil request metode
						post juga.
					</li>
				</ul>

				<hr>

				<p><b>Mengirim Request Method PUT atau DELETE</b></p>
				<p>Dalam View File anda diharuskan menambahkan input key dengan attribut name _put</p>
				<pre class="box-code">
&lt;form method="POST"&gt;
	&lt;input type="hidden" name="_put"&gt;
&lt;/form&gt;
</pre>
				<pre class="box-code">
&lt;form method="POST"&gt;
	&lt;input type="hidden" name="_delete"&gt;
&lt;/form&gt;
</pre>
			<p>
				Setelah itu barulah anda bisa mengatur nya dalam route
			</p>
			<pre class="box-code">route::put('/url_route', 'controller@method');
route::delete('/url_route', 'controller@method');</pre>
			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="controller"># Controller</h2><hr>
				<p>Folder Controller berada pada :</p>
				<pre class="box-code">system\app\controllers
				</pre>
				<p>Membuat Controller gunakan console vifa :</p>
				<pre class="box-code">php vifa add:controller HomeController</pre>
				<p><b>HomeController</b> adalah nama model yang akan dibuat</p>

				<p>
					Atau bisa menggunakan class kosong berikut lalu buat file controller baru :
				</p>
				<pre class="box-code">&lt;?php

use vframework\kernel\HTTPRequests as Request;
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

				
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="model"># Model</h2><hr>
				<p>Folder Model berada pada :</p>
				<pre class="box-code">system\app\models</pre>
				<p>Membuat Model gunakan console vifa :</p>
				<pre class="box-code">php vifa add:model tbauth</pre>
				<p><b>tbauth</b> adalah nama model yang akan dibuat</p>

				<br><br>
				<h3>Kostumisasi</h3><hr>
				<p><b>Nama Tabel</b></p>
				<p>
					Secara otomatis nama tabel akan sama dengan nama models yang dibuat, jika ingin mengganti nama tabel dari suatu model, anda edit bagian CONFIG bagian <b>tb_name</b> di model tersebut :
				</p>
				<pre class="box-code">class tbauth extends BaseModel {
	protected static $tb_name = 'tb_auth';
	protected static $id_collum = 'id';

}</pre>
				<br><br>
				<h3>Menggunakan Model Di Controller</h3><hr>
				<p>
					Pertama, load dulu model yang ingin digunakan :
				</p>
				<pre class="box-code">use app\models\tbauth;</pre>
				<p>
					Model hanya mendukung static class.
				</p>
				
				<br>
				<p><b># Menampilkan Data (READ)</b></p>
				<p>Normal Get :</p>
				<pre class="box-code">tbauth::get()</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tb_auth</p><br>
				
				<p>Menggunakan Where :</p>
				<pre class="box-code">tbauth::where('id', 1)->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tb_auth WHERE id='1'</p><br>
				
				<p>Menggunakan Where 3 Parameter:</p>
				<pre class="box-code">tbauth::where('id', '!=', 1)->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tb_auth WHERE id!='1'</p><br>

				<p>Find Untuk Mengambil Satu Data Pertama Dengan Id :</p>
				<pre class="box-code">tbauth::find(1);</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tb_auth WHERE id='1'</p><br>

				<p>Mengambil Data Pertama Dari Get</p>
				<pre class="box-code">tbauth::where('level', 'admin')->first()->get();</pre><br>

				<p>Order By</p>
				<pre class="box-code">tbauth::where('level', 'admin')->orderBy('id', 'DESC')->get();</pre>
				<p><b>Hasil Query :</b> SELECT * FROM tb_auth WHERE level='admin' ORDER BY id DESC</p><br>
				<br>
	<hr>			
				<p><b># Menambahkan Data (INSERT)</b></p>
				<p>Normal Insert :</p>
				<pre class="box-code">tbauth::insert(['nama' => 'alfian dwi', 'umur' => 17]);</pre>
				<p>Insert Banyak Data :</p>
				<pre class="box-code">
$user = [
			['nama' => 'alfian dwi', 'umur' => 17],
			['nama' => 'deasy mutiara', 'umur' => 17],
			['nama' => 'duta sadewa', 'umur' => 17],
		];
tbauth::insert($user);</pre>
				<br>
				<br>
<hr>	
				<p><b># Menghapus Data (DELETE)</b></p>
				<p>Gunakan Where lalu Panggil Delete() :</p>
				<pre class="box-code">tbauth::where('id', 1)->delete();</pre>
				<br>
				<br>
<hr>	
				<p><b># Memperbarui Data (UPDATE)</b></p>
				<p>Normal Update :</p>
				<pre class="box-code">tbauth::where('id', 1)->update('nama', 'Alfian Dwi');</pre>
				<p>Update banyak kolom :</p>
				<pre class="box-code">tbauth::where('id', 1)->update( ['nama' => 'Alfian Dwi', 'umur' => 18] );</pre>




			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="csrf"># CSRF</h2><hr>
				<p>
					Kami melindungi data POST anda menggunakan CSRF. Oleh Karena Itu Wajib
					ketika anda menggunakan form dengan method post anda harus mencantumkan
					CSRF Token.
				</p>
				<pre class="box-code">&lt;form method="POST"&gt;
	@csrf
&lt;/form&gt;</pre>
				<p>
					Kode <b>@csrf</b> didalam elemen form akan otomatis di render menjadi
					sebuah input dengan atribut type hidden dan otomatis terisi value CSRF Token.
				</p>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="httprequest"># HTTPRequests</h2><hr>
				<p>Class :</p>
				<pre class="box-code">Use vframework\request\HTTPRequests as Request;</pre>
				
				<hr style="margin-top: 15px;">
				<h3>Mengambil Input Form</h3>
				<p>Mengambil Input Form hanya Dapat Dilakukan Melalui Metode POST.</p>
				<p>Contoh Dalam Controller :</p>
				<pre class="box-code">public function insert_data($params, Request $request){
	return "Nama Mu : " . $request->input('nama');
}</pre>			<p>Berikut File View Nya :</p>
				<pre class="box-code">&lt;form action="url_route_controller" method="POST"&gt;
	@csrf
	&lt;label&gt;Nama Mu :&lt;/label&gt;
	&lt;input type="text" name="nama"&gt;
&lt;/form&gt;</pre>
				<hr style="margin-top: 15px;">
				<h3>Validasi Input</h3>
				<p>Contoh Dalam Controller :</p>
				<pre class="box-code">public function insert_data($params, Request $request){
	$request->validate(['nama' => 'required|min:10']);

	return "Nama Mu : " . $request->input('nama');
}</pre>
				<p>
					Jika Validasi Tidak Diterima, Maka Akan Otomatis Di Redirect ke Form Semula,
					Wajib bagi form semula untuk menampilkan error, Berikut Contoh View Untuk Menampilkan Error Jika Terjadi Saat Validasi :
				</p>
				<pre class="box-code">
&lt;?php if (count($error['validation']) &gt; 0) {?&gt;
	&lt;div style="border: 1px dotted red;padding-bottom: 0;margin-bottom: 10px;"&gt;
        &lt;ul&gt;
            &lt;?php
    	    foreach ($error['validation'] as $error) {
        	    echo "&lt;li&gt;".$error['str']."&lt;/li&gt;";
            }?&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
&lt;?php }?&gt;
</pre>
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
				<h2 id="vte"># Vifa Template Engine</h2><hr>
				<p>
					Template Engine dapat membantu pengerjaan anda khususnya di
					pengerjaan file view.
				</p>
				<hr>
				<h3>Contoh</h3>
				<p>
					# Buat File View Baru Bernama <b>template_master.php</b> yang berisi :
				</p>
				<img src="assets/img/upload/2.png">
				<p>
					Fungsi Get Hanya Dapat Digunakan Di View Yang Bertidank Sebagai Master
					Template seperti diatas.
				</p>

				<br>
				<p>
					# Lalu buat File View Lagi Bernama <b>home</b> :
				</p>
				<img src="assets/img/upload/3.png">
				<p>
					Fungsi <b>set</b> dan <b>content</b> hampir sama, yaitu melakukan set content
					ke dalam variabel. yang mana nantinya dapat dipanggil di master template
					melalui fungsi get.
				</p>

				<br>
				<p>
					# Panggil File View Child Seperti Biasa Di Controller :
				</p>
				<pre class="box-code">return view::make('home');</pre>

				<br>
				<p>
					# Hasilnya :
				</p>
				<img src="assets/img/upload/4.png">

			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="lib-db"># Library - DB (Mendukung Mysql Saja) </h2><hr>
				<div style="border: dashed 1px red;padding: 10px;background: pink;text-align: center;">
					Sudah DiHapus Di Versi 1.4.1
				</div>
				<p>Library Ini Mendukung mysqli saja. (Pengembangan Dihentikan Sejak Versi 1.3.5)</p>
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
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="lib-session"># Session</h2><hr>
				
				<p><b>Class Library Session :</b></p>
				<pre class="box-code">use vframework\lib\session;</pre>

				<br>
				<hr>
				<h3># Set</h3>
				<p><b>Single Set :</b></p>
				<pre class="box-code">session::set('key', 'value');</pre>
				<p><b>Multi Set :</b></p>
				<pre class="box-code">session::set(['key' => 'value', 'key2' => 'value2']);</pre>

				<br><hr>
				<h3># Get</h3>
				<p><b>Lihat Semua Sessi :</b></p>
				<pre class="box-code">session::get();</pre>
				<p><b>Lihat Normal :</b></p>
				<pre class="box-code">session::get('key');</pre>

				<br><hr>
				<h3># Delete</h3>
				<pre class="box-code">session::delete('key');</pre>

				<br><hr>
				<h3># Destroy</h3>
				<pre class="box-code">session::destroy();</pre>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="lib-redirect"># Redirect</h2><hr>
				<p><b>Class Library Redirect :</b></p>
				<pre class="box-code">use vframework\lib\redirect;</pre>
				<p>Contoh Melakukan Redirect : </p>
				<pre class="box-code">redirect::now('user/tambah_data/');</pre>
				<pre class="box-code">redirect::now('https://www.google.com/');</pre>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="lib-url"># Url</h2><hr>
				<p><b>Class Library Redirect :</b></p>
				<pre class="box-code">use vframework\lib\url;</pre>
				<p>Contoh: </p>
				<pre class="box-code">$base_url = url::base();</pre>
				<pre class="box-code">$now_url = url::now();</pre>
			</div>
		</div>

		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="router-error"># Router Error</h2><hr>
				<p>
					Router Error Bertugas memanajemen lalu lintas error yang anda
					buat.
				</p>
				<p>Lokasi :</p>
				<pre class="box-code">system\routes\error.php</pre>
				<p>
					Error Route Action hanya mendukung fungsi langsung, jadi harus
					anda beri fungsi langsung pada aksi.
				</p>
				<pre class="box-code">error::set('404', function(){
	return "404";
});</pre>

				<p>Untuk memanggil error tersebut melalui controller anda load lib :</p>
				<pre class="box-code">use vframework\base\error;</pre>
				<p>Lalu Gunakan fungsi :</p>
				<pre class="box-code">error::make('404')</pre>
			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="view-url"># Base URL dan Now URL</h2><hr>
				<p>Gunakan Fungsi base_url() Pada File View Untuk Mengambil Base Url</p>
				<pre class="box-code">&lt;?php echo base_url();?&gt;</pre>
				<p>Gunakan Fungsi now_url() Pada File View Untuk Mengambil Url Sekarang</p>
				<pre class="box-code">&lt;?php echo now_url();?&gt;</pre>
@endcontent