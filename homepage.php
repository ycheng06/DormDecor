<?php
	session_start();
	if(isset($_SESSION['login']) == false) {
		header("Location: index.php");
	}

	require_once("privacySettings.php");
	require_once("friends.php");
	require_once("uploads.php");
	require_once("feed.php");
	require_once("myProducts.php");
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Dorm Decor | <?php echo $_SESSION['login']; ?>'s Homepage</title>
		<meta name="viewport" content="width=808" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<link rel="stylesheet" type="text/css" href="mobileHome.css" media="only screen and (max-device-width:480px)" />
	<link rel="stylesheet" type="text/css" href="homepage.css" media="print, screen and (min-device-width:480px)" />
</head>
<body>
	<div id="outerContainer">
	<div id="banner">
		<div id="banner_color">
			<div id="banner_title"><a class="home" href="homepage.php?view=feed"><img src="dormdecor2.png" alt="logo"/></a></div>
			<a id="signout" href="index.php?action=logout">logout of <?php echo $_SESSION['login']; ?></a>
		</div>
	</div>
	<div id="contentContainer">
		<div id="navMenu">
			<a href="homepage.php?view=feed">Home</a><br/>
			<a class="subMenu" href="homepage.php?view=my_prods">My Products</a><br/>
			<a class="subMenu" href="homepage.php?view=friends">Friends</a><br/>
			<a class="subMenu" href="homepage.php?view=uploads">File Uploads</a>
			<a class="subMenu" href="homepage.php?view=settings">Settings</a><br>
			<a href="products.php">Products</a><br/>
			<a href="vendors.php">Vendors</a><br/>
		</div>
		<div id="rightContent">
			<?php
				$login = $_SESSION['login'];
				if(isset($_GET['view'])) {
					if ($_GET['view'] == "feed") {
						echo "<form id='feedSearch' method='POST' action='homepage.php?view=activitySearch'>";
						echo "Search activity: <input type='search' name='activitySearch' />";
							echo "<select name='searchType'>";
								echo "<option></option>";
								echo "<option value='Chairs'>Chairs</option>";
								echo "<option value='Desks'>Desks</option>";
								echo "<option value='Target'>Target</option>";
								echo "<option value='Bed'>Bed, Bath, and Beyond</option>";
							echo "</select>";
							echo "<input type='submit' name='submit' value='Search' />";
						echo "</form>";
						echo "<h2>See how much your friends are saving:</h2><br/>";
                        $query = $_POST['searchType'];
						$count = displayFeed($login, $query);
						if ($count == 0){
							echo "No entries to display.";
						}
					}
					elseif($_GET['view'] == "activitySearch") {
						$search = $_POST['activitySearch'];
						$query;
						if($_POST['searchType'] == "Chairs"){
							$query = " AND Product_type='chair' AND Product_name LIKE '%" . $search . "%'";
						}
						elseif($_POST['searchType'] == "Desks"){
							$query = " AND Product_type='desk' AND Product_name LIKE '%" . $search . "%'";
						}
						elseif($_POST['searchType'] == "Target") {
							$query = " AND Vendor_name='target' AND Product_name LIKE '%" . $search . "%'";
						}
						elseif($_POST['searchType'] == "Bed") {
							$query = " AND Vendor_name='bed' AND Product_name LIKE '%" . $search . "%'";
						}
						$count = displayFeed($login, $query);
						if ($count == 0){
							echo "No entries to display.";
						}
					}
					elseif($_GET['view'] == "uploads") {
						echo "<div id=receipts>Tired of having your receipts scattered?<br/>";
						echo "Upload them here to keep track of your spending!<br/><br/>";
						displayUploader();
						if(isset($_POST['action'])) {
							if ($_POST['action'] == "upload") {
								uploadFile();
							}
						}
						echo "</div>";
						displayUploads();												
					}
					elseif($_GET['view'] == "friends") {
						echo "<h2>friends</h2>";
						displayFriendsAndRequests();
						if ($_POST['friendSearch']) {
							displayFriendSearchResults();
						}
						if (isset($_GET['add'])) {
							addFriend();
						}
						if (isset($_GET['fconfirm'])) {
							confirmFriend();
						}
						if (isset($_GET['rem_req'])) {
							removeRequest();
						}
					}
					else if($_GET['view'] == "settings") {
						echo "<h2>Privacy Settings</h2>";
						if ($_POST['saveSettings']) {
							updateSettings();
							echo '<p class="confirmation">Your settings have been saved.</p>';
						}
						displaySettings();
					}
					else if($_GET['view'] == "my_prods") {
                       displayProducts();
                    }
				}
			?>
		</div>
	</div>
	</div>
</body>
</html>
