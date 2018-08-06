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
			<small style="font-size: 14px;"><a href=".">Kembali Ke Home</a></small>
		</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<ul>
					<li><a href="#router">Router</a></li>
					<li><a href="#controller">Controller</a></li>
					<li><a href="#view">View</a></li>
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
});</pre>

			</div>
		</div>
		<div class="card" style="width: 100%;">				
			<div class="card-body">
				<h2 id="controller"># Controller</h2><hr>
				<p>Folder Controller berada pada :</p>
				<pre class="box-code">system\app\controllers
				</pre>
				<br>
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
	</div>
</body>
</html>