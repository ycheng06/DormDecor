<!DOCTYPE HTML>
<html>
<head>
	<title>Forgot Password</title>
	<meta name="viewport" content="width=808" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<link rel="stylesheet" type="text/css" href="mobile.css" media="only screen and (max-device-width:480px)" />
	<link rel="stylesheet" type="text/css" href="signin.css" media="print, screen and (min-device-width:480px)" />
</head>
<body>
	<div id="content_wrapper">
	<div id="banner">
		<div id="banner_color">
			<div id="banner_title"><a class="home" href="index.php"><img src="dormdecor2.png" alt="logo"/></a></div>
		</div>
	</div>
	<div id="content_bottom">

	<?php
		//require_once("db_connect.php");
	
		if($_GET['action'] == "retrieve") {
			$handle = mysql_connect("mysql-user", "ycheng06", "");
			$db = mysql_select_db("ycheng06");
			$query = "SELECT * FROM User WHERE email='" . $_POST['email'] . "'";
			$result = mysql_fetch_assoc(mysql_query($query));
			if($result == "FALSE") {
				echo "Please enter a valid email address.<br/>";
			}
			else {
				$message = "Dear " . $result['Name'] . "\n\tYour login information is:\n\t\tUsername: " . $result['Login'] . "\n\t\tPassword: " . $result['Password'] . "\n\nThank you for using Dorm Decor!";
				mail($_POST['email'], "Dorm Decor: Password Retrieval", $message);
				echo "An email containing your login info has been sent to " . $_POST['email'];
			}
		}
	?>

	<div id="loginForm">
		<form id="login" method="POST" action="passwordRetrieval.php?action=retrieve">
			What is your email address?<br/><br/>
			Email: <input type="email" name="email" />
			<input type="submit" name="submit" value="Submit" />
		</form>	
	</div>
	</div>
</body>
</html>