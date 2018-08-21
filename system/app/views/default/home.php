@extends('default.template')

@set('title', '')
@content('isi')
		<h1>SELAMAT DATANG!</h1><hr>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<h2>Apa Yang Baru Di V1.4.3 ?</h2><hr>
				<ul>
					<li><a href="dokumentasi#csrf">CSRF</a></li>
					<li><a href="dokumentasi#httprequest-validator">Form Validator</a></li>
					<li><a href="dokumentasi#httprequest">Remake HTTPRequests</a></li>
					<li><a href="dokumentasi#router-routename">Route Name</a></li>
					<li><a href="dokumentasi#lib-redirect">Route Redirect</a></li>
					<li>
						<a href="dokumentasi#router-routegroup">Route Group - Prefix</a>
					</li>
					<li><a href="dokumentasi#router-routemethod">Route Method Baru [PATCH, OPTION]</a></li>
				</ul>
			</div>
		</div>
		<div class="card" style="width: 100%;">
			<div class="card-body">
				<span>Ini adalah halaman home default dari kami. anda bisa mengubahnya di :</span><hr>
				<b>Router :</b>
				<div class="box-code">
					system\routes\web.php
				</div><Br>
				<b>Controller :</b>
				<div class="box-code">
					system\app\controllers\HomeController.php
				</div><Br>
				<b>View : </b>
				<div class="box-code">
					system\app\views\default\home.php
				</div>
				<div class="box-code">
					system\app\views\default\docs.php
				</div>
				<div class="box-code">
					system\app\views\default\404.php
				</div>
				<br>
				<span>Lihat Lainya :</span>
				<ul>
					<li><a href="dokumentasi">Dokumentasi</a></li>
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