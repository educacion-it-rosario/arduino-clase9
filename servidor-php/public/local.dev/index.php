<!DOCTYPE html>
<!--
=============================================================================
GIANA Framework | Home Automation Made Easy. (LAMP || WAMP) + Arduino UNO r3.
=============================================================================
Copyright (C) 2013 Federico Pfaffendorf (www.federicopfaffendorf.com.ar)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version. 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program. If not, see http://www.gnu.org/licenses/gpl.txt
=============================================================================
-->
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Giana Framework | Home Automation Made Easy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/giana.css" rel="stylesheet">	
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">	
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
	<![endif]-->	
	<link rel="shortcut icon" href="img/favicon.png">	
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="http://fpfaffendorf.wordpress.com/giana-framework-home-automation-made-easy-lamp-wamp-arduino/">Giana Framework</a>
				<div class="nav-collapse collapse">
					<form class="navbar-form pull-right">
						<input class="span2" type="text" placeholder="EMail" id="email" name="email">
						<input class="span2" type="password" placeholder="Password" id="password" name="password">
						<button type="submit" class="btn" data-toggle="modal" href="#" id="signIn">Sign in</button>
					</form>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="container">
	
		<div class="hero-unit">
      <h1>Hi !</h1>
      <p>
				<br />
				Welcome to <strong>Giana Framework</strong>.<br />
				Home Automation Made Easy for LAMP & WAMP using Arduino UNO R3. <br />
				<br />
				Please sign in to start !<br />
				<br />
			</p>
      <p><a href="http://fpfaffendorf.wordpress.com/giana-framework-home-automation-made-easy-lamp-wamp-arduino/" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
    </div>
	
	</div>	

	<div id="signInError" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Sign in error</h3>
		</div>
		<div class="modal-body">
			<p>
				Wrong email or password. Please, try again.
			</p>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="signInErrorOk">Ok</a>
			</div>
		</div>
	</div>

	<div id="ajaxError" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>AJAX Error</h3>
		</div>
		<div class="modal-body">
			<p>
				There was an error connecting with the service.
			</p>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="ajaxErrorOk">Ok</a>
			</div>
		</div>
	</div>	
	
	<div id="loading" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Loading ...</h3>
		</div>
		<div class="modal-body">
			<p>
				Please wait.
			</p>
		</div>
	</div>
	
	<script src="js/jquery-2.0.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/knockout-2.3.0.js"></script>
		
	<script>	
		
	$("#signIn").click(function(){
	
		if (
			$.trim($("#email").val()) == "" ||
			$.trim($("#password").val()) == ""
		) return;
	
		$.ajax({
			beforeSend: function(){
				$("#loading").modal('show');
			}
		});	
	
		$.ajax({
			type: "POST",
			url: "./srv/users/",
			data: { email: $("#email").val(), password: $("#password").val() },
			success: function(data) {
				if (data.userId)
					window.location.replace("devices.php");
				else 
				{
					$("#loading").modal('hide');
					$('#signInError').modal('show');		
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$("#loading").modal('hide');
				$('#ajaxError').modal('show');		
			},
			dataType: "json"
		});
	
	});

	$("#signInErrorOk").click(function(){
		$('#signInError').modal('hide');
	});
	
	$("#ajaxErrorOk").click(function(){
		$('#ajaxError').modal('hide');
	});
		
	</script>		
	
</body>
</html>