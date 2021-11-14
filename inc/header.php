<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Expires" content="0" />
		<title><?php echo $global_event_title; ?> - Audition Suite</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<meta name="viewport" content="width=device-width, user-scalable=no" />
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400|Teko&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<script> function showDiv() {div = document.getElementById('message-box');div.style.display = "block";}</script>
	</head>
<body>
<div id="alert-wrapper"></div>
<div id="wrapper">
<div id="header">
	<span id="site-title"><?php echo $global_event_title; ?></span>
	<div id="menu">
		<?php
			//Determine what menu to show based on user_level
			switch($user_level)
			{
				case '1': //Administrator
					echo "<div class='menu-item'><a href='index.php'>Pre-Register</a></div>";
					echo "<div class='menu-item'><a href='checkin.php'>Check-In</a></div>";
					echo "<div class='menu-item'><a href='results.php'>Results</a></div>";
					echo "<div class='menu-item'><a href='rooms.php'>Rooms</a></div>";
					echo "<div class='menu-item'><a href='students.php'>Students</a></div>";
					echo "<div class='menu-item'><a href='dashboard.php'>Dashboard</a></div>";
					echo "<div class='menu-item'>Logout</div>";
					break;
				case '2': //Registration
					echo "<div class='menu-item'>Logout</div>";
					break;
				case '3': //Adjudicator and Director
					echo "<div class='menu-item'><a href='rooms.php'>Rooms</a></div>";
					echo "<div class='menu-item'><a href='students.php'>Students</a></div>";
					echo "<div class='menu-item'>Logout</div>";
					break;
				default:
					break;
			}
		?>
	</div>
</div>
