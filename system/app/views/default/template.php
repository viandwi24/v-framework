<!DOCTYPE html>
<html>
<head>
	<title>@get('title')VIFA FRAMEWORK - Framework PHP Sederhana Yang Meringankan Perkejaan.</title>
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
		<div class="header-name">VIFA FRAMEWORK 1.4.2</div>
	</header>	

	<div class="container">
		@get('isi')
	</div>
</body>
</html>