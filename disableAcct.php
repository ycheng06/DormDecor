<?php
	function disableAcct() {
		if (isset($_POST['action']) && $_POST['action'] == "disable") {
			session_start();
			if(isset($_SESSION['login']) && $_SESSION['login'] == $_POST['username'])  {
				session_unset();
				session_destroy();
			}
			$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
			$db = mysql_select_db("ycheng06");
			$user = strip_tags($_POST['username']);
			$password = strip_tags($_POST['password']);
			$loginQuery = "SELECT * FROM User WHERE Login='" . $user . "' AND Password='" . $password . "'";
			$loginResult = mysql_fetch_assoc(mysql_query($loginQuery));
			if(!$loginResult) {
				die("<div class='error'>Incorrect username or password.</div><br/>");
			}
			$enableDate = date("Y-m-d", time() + (30 * 24 * 60 * 60));
			$updateQuery = "UPDATE User SET account_disabled=1, enable_date='" . $enableDate . "' WHERE Login='" . $loginResult['Login'] . "'";
			if(mysql_query($updateQuery)) {
				echo "<div class='error'>Your account has successfully been disabled. You will not be able to sign in for 30 days.</div><br/>";
			}
			else {
				echo "<div class='error'>Unable to deactive your account at this time. Please try again later.</div><br/>";
			}
			mysql_close($handle);
		}
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
		if(isset($_POST['action']) AND $_POST['action'] == "disable") {
			disableAcct();
		}
	?>
		<div id="loginForm">
			<form id="login" method="POST" action="disableAcct.php">
				<input type="hidden" name="action" value="disable" />
				Username: <input type="text" name="username" /><br/>
				Password: <input type="password" name="password" /><br/>
				<input type="submit" name="submit" value="Disable Account" />
			</form>
	</div>
	</div>
	</div>
</body>
</html>