@extends('template')

@set('title', '')
@content('isi')
		<h1>SELAMAT DATANG!</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<p>
					Ini adalah halaman home default dari kami. anda bisa mengubahnya di :<br>
				</p><Br>
				<b>Router :</b>
				<div class="box-code">
					system\routes\app.php
				</div><Br>
				<b>View : </b>
				<div class="box-code">
					system\app\views\home.php
				</div>
				<div class="box-code">
					system\app\views\default\tentang.php
				</div>
				<div class="box-code">
					system\app\views\default\docs.php
				</div>
				<br>
				<span>Lihat Lainya :</span>
				<ul>
					<li><a href="dokumentasi">Dokumentasi</a></li>
					<li><a href="user">Live Demo - Contoh Pengaplikasian Crud + Bootstrap 4</a></li>
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
@endcontent