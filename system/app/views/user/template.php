<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://localhost/vframework/assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="http://localhost/vframework/assets/vendor/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://localhost/vframework/assets/vendor/jquery-linedtextarea/jquery-linedtextarea.js">
	<link rel="stylesheet" href="http://localhost/vframework/assets/vendor/codemirror/lib/codemirror.css">

    <title>EXAMPLE CRUD - VIFA FRAMEWORK</title>

    <style type="text/css">
    	.btn-xs {
    		font-size: 12px;
    	}
    </style>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	  <a class="navbar-brand" href="#">EXAMPLE CRUD - VIFA FRAMEWORK 1.4.1 | @get('title')</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	    </ul>
	    <ul class="navbar-nav mr-right">
	    </ul>
	  </div>
	</nav>

  	<div class="container" style="margin-top: 25px;">
       	@get('isi')
  	</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="http://localhost/vframework/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="http://localhost/vframework/assets/vendor/popper/popper.min.js"></script>
    <script src="http://localhost/vframework/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script src="http://localhost/vframework/assets/vendor/codemirror/lib/codemirror.js"></script>
	<script src="http://localhost/vframework/assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>

    @get('custom-js')
  </body>
</html>