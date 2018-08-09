<!DOCTYPE html>
<html>
<head>
	<title>VIFA FRAMEWORK - Framework PHP Sederhana Yang Meringankan Perkejaan.</title>
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
			margin-bottom: 10px;
			margin-top: 10px;
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
		h1 {
		}
		ul {
			margin-left: 17px;
		}
	</style>
</head>
<body>
	<header class="header">
		<div class="header-name">VIFA FRAMEWORK</div>
	</header>	

	<div class="container">
		<h1>SELAMAT DATANG!</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				Ini adalah halaman home defaul dari kami. anda bisa mengubahnya di :
				<div class="box-code">
					system\routes\app.php
				</div>
				<br>
				<span>Lihat Lainya :</span>
				<ul>
					<li><a href="dokumentasi">Dokumentasi</a></li>
					<li><a href="tentang">Tentang Aplikasi</a></li>
					<li><a target="_blank" href="https://github.com/viandwi24/v-framework">Sumber Kode Di Github</a></li>
					<li><a target="_blank" href="https://fb.com/viandwicyber">Facebook Penulis</a></li>
				</ul>
			</div>

		</div>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<center>&copy; 2018 Alfian Dwi Nugraha - Team Mojokerto Developers</center>
			</div>
		</div>
	</div>
</body>
</html>