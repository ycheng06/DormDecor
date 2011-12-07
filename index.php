<?php
	//require_once("db_connect.php");

	function verifyLogin() {
		$login = strip_tags($_POST['username']);
		$password = strip_tags($_POST['password']);
		$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
		$db = mysql_select_db("ycheng06");
		$query="SELECT * FROM User WHERE Login='" . $login . "' AND Password='" . $password . "'";
		$result = mysql_fetch_assoc(mysql_query($query));
		if(!$result) {
			mysql_close($handle);
			echo "<div class='error'>Incorrect username or password</div>";
			return false;
		}
		if($result['account_disabled'] == 1) {
			if(strtotime($result['enable_date']) > time()) {
				mysql_close($handle);
				echo "<div class=error>You have temporarily deactivated your account.</div>";
				return false;
			}
			else {
				$updateQuery = "UPDATE User SET account_disabled=0 WHERE Login='" . $result['Login'] . "'";
				mysql_query($updateQuery) or die("Error: " . mysql_error());
			}
		} 
		mysql_close($handle);
		session_regenerate_id();
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
		session_write_close();
		return true;
	}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Sign In</title>
	<meta name="viewport" content="width=808" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<link rel="stylesheet" type="text/css" href="mobile.css" media="only screen and (max-device-width:480px)" />
	<link rel="stylesheet" type="text/css" href="signin.css" media="print, screen and (min-device-width:480px)" />
</head>
<body>
	<div id="content_wrapper">
	<div id="banner">
		<div id="banner_color">
			<div id="banner_title"><img src="dormdecor2.png" alt="logo"></div>
		</div>
	</div>
	<div id="content_bottom">
	<?php
		session_start();
		if($_POST['action'] == "verifyLogin") {
			if(verifyLogin()) {
				header("Location: homepage.php?view=feed");
			}
		}
		elseif($_GET['action'] == "logout") {	
			session_unset();
			if(session_destroy()) {
				echo "<div class='error'>You have successfully logged out</div>";
			}
		}
		elseif($_GET['action'] == "newAcct") {
			echo "<div class='error'>Your account has successfully been created. Login now!</div>";
		}
	?>

		<div id="loginForm">
			<form id="login" method="POST" action="index.php">
				<input type="hidden" name="action" value="verifyLogin" />
				Username: <input type="text" name="username" /><br/>
				Password: <input type="password" name="password" /><br/><br/>
				<input type="submit" name="submit" value="Login" /><br/><br/>
			</form>
			<a href="passwordRetrieval.php">Forgot your password?</a> | <a href="createAcct.php">Signup</a><br/>
			<a href="disableAcct.php">Disable Account for 30 Days</a>
		</div>
	</div>
	</div>
</body>
</html>
