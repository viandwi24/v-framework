<?php

function xhandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo '<!DOCTYPE html>
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
				<h2>Vifa Framework Error Handler</h2><hr>

				<table>
					<tbody>	
						<tr>
							<td><b>Type</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>Fatal Error</td>
						</tr>
						<tr>
							<td><b>Error</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errstr.'</td>
						</tr>
						<tr>
							<td><b>Line</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errline.'</td>
						</tr>
						<tr>
							<td><b>File</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errfile.'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
</body>
</html>

		';
        exit(1);
        die();
        break;

    case E_USER_WARNING:
        echo "<b>VIFA FRAMEWORK WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>VIFA FRAMEWORK NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo '<!DOCTYPE html>
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
				<h2>Vifa Framework Error Handler</h2><hr>

				<table>
					<tbody>	
					
						<tr>
							<td><b>Type</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>Unknown error type</td>
						</tr>
						<tr>
							<td><b>Error</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errstr.'</td>
						</tr>
						<tr>
							<td><b>Line</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errline.'</td>
						</tr>
						<tr>
							<td><b>File</b></td>
							<td><pre>    </pre></td>
							<td><b>: </b>'.$errfile.'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
</body>
</html>

		';
        exit(1);
        die();
        break;
    }

	
    /* Don't execute PHP internal error handler */
    return true;
}

set_error_handler('xhandler', E_ALL);