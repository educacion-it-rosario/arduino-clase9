<?php
  include ("./inc/facade.inc.php");
  $name = getName();
  if ($name == "") header ("location: index.php");
  $devices = getDevices();
?>
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
			<div class="container-fluid">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="http://fpfaffendorf.wordpress.com/giana-framework-home-automation-made-easy-lamp-wamp-arduino/">Giana Framework</a>
				<div class="nav-collapse collapse">
					<p class="navbar-text pull-right">
						Logged in as <a href="#" class="navbar-link"><strong><?PHP echo ($name); ?></strong></a> | 
						<a href="sign-out.php" class="navbar-link">sign out</a>
					</p>
					<ul class="nav">
						<li class="active"><a href="devices.php">Devices</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    
	<div class="container">
	
		<div class="row-fluid">

			<?PHP 
			
				$c = 1;
			
				foreach ($devices as $i => $device) { 
				
			?>
		
			<div class="span3 btn <?PHP echo ($device["readOnly"] == "Y" ? "btn-info" : "btn-success"); ?> device">
				<h4><?PHP echo ($device["name"]); ?></h4>
				<h2 id="device<?PHP echo ($device["deviceId"]); ?>Value"><?PHP echo ($device["value"] == "" ? "-" : $device["value"]); ?></h2>
				<h5 id="device<?PHP echo ($device["deviceId"]); ?>DateTime"><?PHP echo ($device["dateTime"] == "" ? "-" : $device["dateTime"]); ?></h5>
				<p>
					<div class="btn-group">

						<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">Actions <span class="caret"></span></a>					
					
						<ul class="dropdown-menu">
							<li><a href="#<?PHP echo ($device["deviceId"]); ?>" id="device<?PHP echo ($i); ?>Get"><i class="icon-upload"></i> Get</a></li>
							<?PHP if ($device["readOnly"] != "Y") { ?>
							<li><a href="#<?PHP echo ($device["deviceId"]); ?>" id="device<?PHP echo ($i); ?>Set"><i class="icon-download"></i> Set</a></li>
							<?PHP } ?>						
							<li><a href="#<?PHP echo ($device["deviceId"]); ?>" id="device<?PHP echo ($i); ?>Update"><i class="icon-repeat"></i> Update</a></li>
						</ul>
					</div>
				</p>
			</div>		

			<?PHP if ($c == 4) { ?>
			
			</div>	
			<div class="row-fluid">

			<?PHP $c = 1; } ?>
			
			<?PHP 
			
					$c++;
			
				} 
				
			?>
			
		</div>	
		
	</div>	

	<div id="requestSent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Request sent</h3>
		</div>
		<div class="modal-body">
			<p>
				Your request has been sent. Please, update the device in a few seconds to get the results.
			</p>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="requestSentOk">Ok</a>
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
	
	var devices = <?PHP echo (count($devices)); ?>;
	for (var i = 0; i < devices; i ++)
	{
	
		$("#device" + i + "Get").click(function(){
		
			$.ajax({
				beforeSend: function(){
					$("#loading").modal('show');
				}
			});			
		
		
			$.ajax({
				type: "POST",
				url: "./srv/request/",
				data: { 
					deviceId: $(this).attr("href").replace("#", ""), 
					action: "G"
				},
				success: function(data) {
					$("#loading").modal('hide');
					$('#requestSent').modal('show');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#loading").modal('hide');
					$('#ajaxError').modal('show');		
				},
				dataType: "xml"
			});				
		
		});	
	
		$("#device" + i + "Set").click(function(){

			$.ajax({
				beforeSend: function(){
					$("#loading").modal('show');
				}
			});			
		
		
			$.ajax({
				type: "POST",
				url: "./srv/request/",
				data: { 
					deviceId: $(this).attr("href").replace("#", ""), 
					value: ($("#device" + $(this).attr("href").replace("#", "") + "Value").html() == "Off" ? "1" : "0"),
					action: "S"
				},
				success: function(data) {
					$("#loading").modal('hide');
					$('#requestSent').modal('show');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#loading").modal('hide');
					$('#ajaxError').modal('show');		
				},
				dataType: "xml"
			});			
		
		});	
	
		$("#device" + i + "Update").click(function(){
		
			$.ajax({
				beforeSend: function(){
					$("#loading").modal('show');
				}
			});			
			
			$.ajax({
				type: "GET",
				url: "./srv/devices/",
				data: { deviceId: $(this).attr("href").replace("#", ""), random: Math.random() },
				success: function(data) {
					$("#loading").modal('hide');			
					$("#device" + data.deviceId + "Value").html(data.value);
					$("#device" + data.deviceId + "DateTime").html(data.dateTime);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#loading").modal('hide');
					$('#ajaxError').modal('show');		
				},
				dataType: "json"
			});			
			
		});	
	
	}

	$("#requestSentOk").click(function(){
		$('#requestSent').modal('hide');
	});
	
	$("#ajaxErrorOk").click(function(){
		$('#ajaxError').modal('hide');
	});
	
	</script>		
		
</body>
</html>