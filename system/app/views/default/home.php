<!DOCTYPE html>
<html>
<head>
	<title>VIFA FRAMEWORK</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo assets('vendor/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo assets('vendor/font-awesome/css/all.min.css');?>">

	<style type="text/css">
	* {
		padding: 0;
		margin: 0;
		font-family: 'Roboto', sans-serif;
	}

	body{
	    background-image:url('<?php echo assets('img/upload/wp.jpg');?>');
	    background-attachment:fixed;
	    background-repeat: no-repeat;
	    background-size: cover;
	    overflow: hidden;
	}

	.taskbar {
		position: fixed;
		z-index: 10;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0, 0.6);
		width: 100%;
		height: auto;
		padding: 10px;
	}


	.taskbar-menu ul {
	    list-style-type: none;
	    list-style-image: none;
	    margin: 0;
	    padding: 0;
	    overflow: hidden;
	}

	.taskbar-menu li {
	    float: left;
	    color: white;
	    list-style-type: none;
	    list-style-image: none;
	}
	.container {
		margin-left: 0;
	}
	.desktop-icon{
		text-align: center;
		font-size: 46px;
		color: white;
		width: auto;
		padding: 5px;
		margin-top: 20px;
	}
	.desktop-icon i {
		margin-top: 20px;
	}
	.desktop-icon i div {
		font-size: 12px;
		padding-top: 5px;
	}

	.window {
		height: 75vh;
		width: 75vw;
	    position: absolute;
	    z-index: 8;
	    background-color: #f1f1f1;
	    text-align: center;
	    border: 1px solid #d3d3d3;
	    left: 5vw;
	    top: 5vh;
	    overflow: hidden;
	}
	.window-header {
		padding: 10px;
	    cursor: move;
	    z-index: 9;
	    color: black;
		background-color: white;
		width: 100%;
		height: auto;
	}

	.window-body {
		text-align: left;
		width: auto;
		height: 90%;
		overflow-y: scroll;
		overflow-x: auto;
	}


.showbox {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 5%;
}

.loader {
  position: relative;
  margin: 0 auto;
  width: 100px;
}
.loader:before {
  content: '';
  display: block;
  padding-top: 100%;
}

.circular {
  -webkit-animation: rotate 2s linear infinite;
          animation: rotate 2s linear infinite;
  height: 100%;
  -webkit-transform-origin: center center;
          transform-origin: center center;
  width: 100%;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
}

.path {
  stroke-dasharray: 1, 200;
  stroke-dashoffset: 0;
  -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
          animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
  stroke-linecap: round;
}

@-webkit-keyframes rotate {
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

@keyframes rotate {
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
@-webkit-keyframes dash {
  0% {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 89, 200;
    stroke-dashoffset: -35px;
  }
  100% {
    stroke-dasharray: 89, 200;
    stroke-dashoffset: -124px;
  }
}
@keyframes dash {
  0% {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 89, 200;
    stroke-dashoffset: -35px;
  }
  100% {
    stroke-dasharray: 89, 200;
    stroke-dashoffset: -124px;
  }
}
@-webkit-keyframes color {
  100%,
  0% {
    stroke: #d62d20;
  }
  40% {
    stroke: #0057e7;
  }
  66% {
    stroke: #008744;
  }
  80%,
  90% {
    stroke: #ffa700;
  }
}
@keyframes color {
  100%,
  0% {
    stroke: #d62d20;
  }
  40% {
    stroke: #0057e7;
  }
  66% {
    stroke: #008744;
  }
  80%,
  90% {
    stroke: #ffa700;
  }
}


	</style>
</head>
<body id="body">




	<div class="container desktop">
		<div class="row">
			<div class="col-md-1">
				<div class="row">
					<div class="col-md-1">
						<a href="javascript:void();" onclick="open_window('window_welcome_page', 'Welcome Page', 'page/welcome_page');" class="desktop-icon">
							<i class="fa fa-solar-panel"><div>Welcome Page</div></i>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						<a href="javascript:void();" onclick="open_window('window_demo_page', 'Offline Demo', 'page/demo_page');" class="desktop-icon">
							<i class="fa fa-vial"><div>Offline Demo</div></i>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						<a href="javascript:void();" onclick="open_window('window_docs_page', 'Dokumentasi', 'page/docs_page');" class="desktop-icon">
							<i class="fa fa-book-open"><div>Docs</div></i>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						<a href="javascript:void();" onclick="open_window('window_tentang_page', 'Tentang', 'page/tentang_page');" class="desktop-icon">
							<i class="fa fa-question-circle"><div>Tentang</div></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="taskbar">
		<ul class="taskbar-menu">
			<li>VIFA FRAMEWORK</li>
			<li style="float: right;margin-right: 15px;">
				<i class="fa fa-clock"></i> <span id="date_time"></span>
			</li>
		</ul>
	</div>


	<script src="<?php echo assets('vendor/jquery/jquery-3.3.1.min.js');?>"></script>
    <script src="<?php echo assets('vendor/popper/popper.min.js');?>"></script>
    <script src="<?php echo assets('vendor/bootstrap/js/bootstrap.min.js');?>"></script>
	<script type="text/javascript">
		window.onload = date_time('date_time');

		open_window('window_welcome_page', 'Welcome Page', 'page/welcome_page');

		function open_window(id, title, page) {
			if (!document.getElementById(id)) {
				var idnya = "remove_window('"+id+"');";
				var div = document.createElement("div");
				div.id = id;
				div.className = 'window';
				div.innerHTML = '<div class="window-header" id="'+id+'header"><div class="window-header-title">'+title+'</div><div style="color: black;position:absolute;top:0;right:0;margin-right:11px;margin-top:11px;"><a href="javascript:void();" id="'+id+'close" onclick="'+idnya+'"><i class="fa fa-times"></i></a></div><div id="'+id+'loader" style="top: 25%;" class="showbox"><div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div></div>  </div>   <div id="'+id+'body" class="window-body"></div>';
				document.getElementById("body").appendChild(div);
				dragElement(document.getElementById(id));

				var base_url = "<?php echo base_url();?>";
				var url = base_url + '/' + page;

				setTimeout(function(){
					$('#'+id+'body').load(url, '', function(){
						$('#'+id+'loader').remove();
					});
				}, 1000);
			}
		}

		function remove_window(id){
			$('#'+id).remove();
		}

		
		function date_time(id) {
	        date = new Date;
	        year = date.getFullYear();
	        month = date.getMonth();
	        months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Octr', 'Nov', 'Dec');
	        d = date.getDate();
	        day = date.getDay();
	        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	        h = date.getHours();
	        if(h<10)
	        {
	                h = "0"+h;
	        }
	        m = date.getMinutes();
	        if(m<10)
	        {
	                m = "0"+m;
	        }
	        s = date.getSeconds();
	        if(s<10)
	        {
	                s = "0"+s;
	        }
	        result = ''+h+':'+m+'';
	        document.getElementById(id).innerHTML = result;
	        setTimeout('date_time("'+id+'");','1000');
	        return true;
	    }

	    //Make the DIV element draggagle:
		function dragElement(elmnt) {
		  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
		  if (document.getElementById(elmnt.id + "header")) {
		    /* if present, the header is where you move the DIV from:*/
		    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
		  } else {
		    /* otherwise, move the DIV from anywhere inside the DIV:*/
		    elmnt.onmousedown = dragMouseDown;
		  }

		  function dragMouseDown(e) {
		    e = e || window.event;
		    e.preventDefault();
		    // get the mouse cursor position at startup:
		    pos3 = e.clientX;
		    pos4 = e.clientY;
		    document.onmouseup = closeDragElement;
		    // call a function whenever the cursor moves:
		    document.onmousemove = elementDrag;
		  }

		  function elementDrag(e) {
		    e = e || window.event;
		    e.preventDefault();
		    // calculate the new cursor position:
		    pos1 = pos3 - e.clientX;
		    pos2 = pos4 - e.clientY;
		    pos3 = e.clientX;
		    pos4 = e.clientY;
		    // set the element's new position:
		    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
		    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
		  }

		  function closeDragElement() {
		    /* stop moving when mouse button is released:*/
		    document.onmouseup = null;
		    document.onmousemove = null;
		  }
		}

		if (document.addEventListener) { // IE >= 9; other browsers
	        document.addEventListener('contextmenu', function(e) {
	        	ctxmenu();
	            e.preventDefault();
	        }, false);
	    } else { // IE < 9
	        document.attachEvent('oncontextmenu', function() {
	        	ctxmenu();
	            window.event.returnValue = false;
	        });
	    }

	    function ctxmenu() {

	    }
	</script>
</body>
</html>