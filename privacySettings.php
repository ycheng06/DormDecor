<?php
	function displaySettings() {
		require_once('db_connect.php');
		$settings = mysql_fetch_assoc(mysql_query('SELECT notification, display_purchase, display_contact FROM User WHERE Login = \'' . $_SESSION['login'] . '\''));
		echo '<form id="settings" method="post" action="homepage.php?view=settings">';
		echo '<input type="checkbox" name="notification" ';
			if ($settings['notification'] == 1) {
				echo 'checked';
			}
		echo '/>  Receive email notifications of sales on my watched items<br>';
		echo '<input type="checkbox" name="rec_purchases" ';
			if ($settings['display_purchase'] == 1) {
				echo 'checked';
			}
		echo '/>  Allow friends to view my recent purchases<br>';
		echo '<input type="checkbox" name="allow_search" ';
			if ($settings['display_contact'] == 1) {
				echo 'checked';
			}
		echo '/>  Allow others to search for me<br><br>';
		echo '<input type="submit" name="saveSettings" value="Save Settings">';
		echo '</form>';
	}
	
	function updateSettings() {
		if (isset($_POST['notification'])) {
			$notif = 1;
		} else { $notif = 0; }	
		if (isset($_POST['rec_purchases'])) {
			$purch = 1;
		} else { $purch = 0; }
		if (isset($_POST['allow_search'])) {
			$search = 1;
		} else { $search = 0; }
		require_once('db_connect.php');
		mysql_query('UPDATE User SET notification = ' . $notif . ' WHERE Login = \'' . $_SESSION['login'] . '\'') or die('error ' . mysql_error());
		mysql_query('UPDATE User SET display_purchase = ' . $purch . ' WHERE Login = \'' . $_SESSION['login'] . '\'') or die('error ' . mysql_error());
		mysql_query('UPDATE User SET display_contact = ' . $search . ' WHERE Login = \'' . $_SESSION['login'] . '\'') or die('error ' . mysql_error());
	}
?>