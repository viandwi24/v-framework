<?php

namespace vifaframework\exception;

use exception;

class handle extends exception {
	protected $msg;
	protected $file;
	protected $fix;

	public function __construct($msg = '', $file = '', $fix = '')
	{
		$this->msg = $msg;
		$this->file = $file;
		$this->fix = $fix;
	}
	public function renderError(){
		$msg = $this->msg;
		$file = $this->file;
		$fix = $this->fix;

		$log = str_replace('#', '</li><li>#', $this->__toString());

		$web = '<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
			font-family: "Roboto", sans-serif;
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
			margin-top: 15px;
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
	<div class="container">
		<div class="card" style="width: 100%;">
			<div class="card-body">
			<center>
						<h1>VIFA FRAMEWORK EXCEPTION HANDLING</h1><hr>
					</center>
					<br><b><u>Error Handle</u> </b> <br>
				<table>
					<tbody>	
					
						<tr>
							<td><b>Pesan</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$msg.'</td>
						</tr>
						<tr>
							<td><b>File</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$file.'</td>
						</tr>
						<tr>
							<td><b>Perbaikan</b></td>
							<td><pre>    </pre></td>
							<td>'.$fix.'</td>
						</tr>
					</tbody>
				</table><br><br><br>

					<b><u>Error Proses</u></b>
				<table>
					<tbody>	
					
						<tr>
							<td><b>Baris</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$this->getLine().'</td>
						</tr>
						<tr>
							<td><b>File</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$this->getFile().'</td>
						</tr>
					</tbody>
				</table><br><br>

					<b><u>Error Log</u></b><br>
						'.$log.'
					<hr>
					<center>
						<p>
							VIFA FRAMEWORK EXCEPTION HANDLING
							membantu anda untuk menyelesaikan error dengan cepat.
							<br>
							Kurang Membantu? <a href="https://vifaframework.site/feedback">Klik Disini</a> untuk menyampaikan keluhan.
						</p>
					</center>
			</div>
		</div>
</body>
</html>

		';

		die($web);
	}
}
?>
