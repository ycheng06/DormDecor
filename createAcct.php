<?php
	//require_once("db_connect.php");

	function isComplete() {
		foreach($_POST as $key) {
			if($key == ""){
				echo "<div class='error'>Please complete the form below.</div><br/>";
				return false;
			}
		}
		return true;
	}
		
	function verifyPassword() {
		if($_POST['password'] != $_POST['verPassword']) {
			echo "<div class='error'>Password and Verify Password fields are different.</div><br/>";
			return false;
		}
		return true;
	}

	function checkLogin($login) {
		$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
		$db = mysql_select_db("ycheng06");
		$query="SELECT * FROM User WHERE Login='" . $login . "'";
		$result = mysql_fetch_assoc(mysql_query($query));
		if($result['Login'] == $login) {
			echo "<div class='error'>Username not available.</div><br/>";
			return false;
		} 
		return true;
		mysql_close($handle);
	}
		
	function addNewUser() {
		$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
		$db = mysql_select_db("ycheng06");
		$query="SELECT * FROM User WHERE Login='" . $login . "'";
		$createQuery = "INSERT INTO User(Login, Password, email, Name) VALUES ('" . $_POST['username'] . "', '" . $_POST['password'] . "', '" . $_POST['email'] . "', '" . $_POST['fname'] . " " . $_POST['lname'] . "')";
			if(mysql_query($createQuery)){
				header("Location: index.php?action=newAcct");
			}
			else {
				echo "<div class='error'>Your account was unable to be created. Please try again.</div>";
			}
		mysql_close($handle);
	}
?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Signup</title>
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
		if(isset($_POST['action']) AND $_POST['action'] == "create") {
			if(isComplete() AND verifyPassword() AND checkLogin($_POST['username'])) {
				addNewUser();
			}
		}
	?>
		<div id="loginForm">	
			<form id="login" method="POST" action="createAcct.php?action=create">
				<input type="hidden" name="action" value="create" />
				First Name: <input type="text" name="fname" /><br/>
				Last Name: <input type="text" name="lname" /><br/>
				Username: <input type="text" name="username" /><br/>
				Password: <input type="password" name="password" /><br/>
				Verify: <input type="password" name="verPassword" /><br/>
				Email: <input type="email" name="email" /><br/>
				<input type="submit" name="submit" value="Create Account" />
			</form>
		</div>
	</div>
	</div>
</body>
</html>