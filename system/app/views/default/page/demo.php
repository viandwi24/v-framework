<div style="background-color: deepskyblue;text-align: center;color: white;padding: 10px;">
	<h1>OFFLINE DEMO</h1>
	<p>Berisi Live Demo penggunaan VIFA Framework</p>
</div>

<div class="container" style="margin-top: 15px;">
	<p>
		<b>Halaman ini</b> akan memberikan live demo secara offline yang ada di VIFA Framework ini.
		Tetapi sebelum anda mencobanya ada langkah langkah yang harus anda lakukan terlebih dahulu.
		<br>
		Kami memisahkan file demo dari file utama VIFA Framework ini agar tidak tercampur. Untuk menggunakanya anda harus membuka VIFA Console terlebih dahulu lalu mengetik :
	</p>
	<p style="border: 1px dotted red;padding: 10px;">
		perhatian! Menginstal Demo Live ini akan mereset Routes Web Anda.
	</p>
	<pre><code>php vifa install_demo</code></pre>
	<p>
		Setelah muncul pesan sukses menginstall demo, maka anda harus menlanjutkan dengan mengetik :
	</p>
	<pre><code>php vifa migration:install demo</code></pre>
	<p style="border: 1px dotted red;padding: 10px;">
		Perhatikan juga pengaturan database kami, Live Demo Offline ini akan membuat database baru di server anda dengan nama "vifaframework". pastikan tidak ada nama database "vifaframework" di server anda dan anda harus mengatur pengaturan database terlebih dahulu di <b>system/config/database.php</b> dan hidupkan server database kalian.
	</p>
	<p>
		Setelah Migrasi Database Sukses, maka anda bisa langsung mencoba fitur Live Demo Secara Offline, Berikut :
	</p>

	<b>Daftar Demo :</b>
	<ul>
		<li><a href="<?php echo base_url('demo');?>">Demo CRUD</a></li>
	</ul>
</div>