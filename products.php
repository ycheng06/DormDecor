<?php
	//require_once("db_connect.php");
	session_start();
	if(isset($_SESSION['login']) == false) {
		header("Location: index.php");
	}
	
	function displayAll() {
		require_once('db_connect.php');
		$result = mysql_query('SELECT * FROM Product') or die('error: ' .
mysql_error());
		echo '<table id="product_table">';
		while ($product = mysql_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>';
			echo '<a href="' . $product['Product_url'] . '"><img src="' .
$product['Product_image'] . '" alt="' . $product['Product_name'] .
'"/></a>';
			$result2 = mysql_query('SELECT * FROM Users_Products WHERE Login =
\'' . $_SESSION['login'] . '\' AND Product_id = \'' .
$product['Product_id'] . '\'');
			if (mysql_num_rows($result2) == 0) {
				echo '<form method="POST" action="products.php?watch=' . $product['Product_id'] . '">';
				echo '<input type="submit" name="watch" value="Watch this item" />';
				echo '</form>';
				echo '<form method="POST" action="' . $product['Product_url'] . '" target="_blank">';
				echo '<input type="hidden" name="prodID" value="' . $product['Product_id'] . '" />';
				echo '<input type="submit" name="buy" value="Buy this item" />';
				echo '</form>';
			} else {
				echo '<p class="watching">You are watching this item</p>';				
				echo '<form method="POST" action="' . $product['Product_url'] . '" target="_blank">';
				echo '<input type="hidden" name="prodID" value="' . $product['Product_id'] . '" />';
				echo '<input type="submit" name="buy" value="Buy this item" />';
				echo '</form>';
			}
			echo '</td>';
			echo '<td><a href="' . $product['Product_url'] . '"><h5
class="product_name">' . $product['Product_name'] . '</a></h5>';
			echo '<p class="prod_info">' . $product['Product_Price'] . ' from '
. $product['Vendor_name'] . '</p>';
			echo '<p class="prod_desc">' . $product['Product_desc'] . '</p>';
			echo '</td>';
			echo '</tr>';
		}	
		echo '</table>';
	}
	
	function displayProducts() {
		if ($_POST['view_prod'] == "all") {
			displayAll();
		}
		if ($_POST['view_prod'] == "desks") {
			$query = "SELECT * FROM Product WHERE Product_type = 'desk'";
		}
		if ($_POST['view_prod'] == "chairs") {
			$query = "SELECT * FROM Product WHERE Product_type = 'chair'";	
		}
		require_once('db_connect.php');
		$result = mysql_query($query) or die('error: ' . mysql_error());
		echo '<table>';
		while ($product = mysql_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>';
			echo '<a href="' . $product['Product_url'] . '"><img src="' .
$product['Product_image'] . '" alt="' . $product['Product_name'] .
'"/></a>';
			echo '<form method="POST" action="products.php?watch=' . $product['Product_id'] . '">';
			echo '<input type="submit" name="watch" value="Watch this item" />';
			echo '</form>';
			echo '<form method="POST" action="' . $product['Product_url'] . '" target="_blank">';
			echo '<input type="hidden" name="prodID" value="' . $product['Product_id'] . '" />';
			echo '<input type="submit" name="buy" value="Buy this item" />';
			echo '</form>';
			echo '</td>';
			echo '<td><a href="' . $product['Product_url'] . '"><h5
class="product_name">' . $product['Product_name'] . '</a></h5>';
			echo '<p class="prod_info">' . $product['Product_Price'] . ' from '
. $product['Vendor_name'] . '</p>';
			echo '<p class="prod_desc">' . $product['Product_desc'] . '</p>';
			echo '</td>';
			echo '</tr>';
		}	
		echo '</table>';
	}
	
	function displaySearchResults() {
		$query = $_POST['query'];
		require_once('db_connect.php');
		$result = mysql_query('SELECT * FROM Product WHERE Product_type LIKE
\'%' . $query . '%\' OR Product_desc LIKE \'%' . $query . '%\' OR
Product_name LIKE \'%' . $query . '%\'');
		echo '<table>';
		while ($product = mysql_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>';
			echo '<a href="' . $product['Product_url'] . '"><img src="' .
$product['Product_image'] . '" alt="' . $product['Product_name'] .
'"/></a>';
			echo '<form method="POST" action="products.php?watch=' . $product['Product_id'] . '">';
			echo '<input type="submit" name="watch" value="Watch this item" />';
			echo '</form>';
			echo '<form method="POST" action="' . $product['Product_url'] . '" target="_blank">';
			echo '<input type="hidden" name="prodID" value="' . $product['Product_id'] . '" />';
			echo '<input type="submit" name="buy" value="Buy this item" />';
			echo '</form>';
			echo '</td>';
			echo '<td><a href="' . $product['Product_url'] . '"><h5
class="product_name">' . $product['Product_name'] . '</a></h5>';
			echo '<p class="prod_info">' . $product['Product_Price'] . ' from '
. $product['Vendor_name'] . '</p>';
			echo '<p class="prod_desc">' . $product['Product_desc'] . '</p>';
			echo '</td>';
			echo '</tr>';
		}	
		echo '</table>';
	}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Dorm Decor | Products</title>
	<meta name="viewport" content="width=808" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0;maximum-scale=1.0; user-scalable=0;" />
	<link rel="stylesheet" type="text/css" href="mobileHome.css" media="only screen and (max-device-width:480px)" />
	<link rel="stylesheet" type="text/css" href="homepage.css" media="print, screen and (min-device-width:480px)" />
</head>
<body>
	<div id="outerContainer">
	<div id="banner">
		<div id="banner_color">
			<div id="banner_title"><a class="home" href="homepage.php?view=feed"><img src="dormdecor2.png" alt="logo"/></a></div>
			<a id="signout" href="index.php?action=logout">logout of <?php echo
$_SESSION['login']; ?></a>
		</div>
	</div>
	<div id="contentContainer">
		<div id="navMenu">
			<a href="homepage.php?view=feed">Home</a><br/>
			<a href="products.php">Products</a><br/>
			<a href="vendors.php">Vendors</a><br/>
		</div>
	</div>
	<div id="rightContent">	
		<div id="products">
			<div id="view_products">
				<form method="post" action="products.php">
				<select name="view_prod">
				<option value="all"> All </option>
				<option value="desks"> Desks </option>
				<option value="chairs"> Chairs </option>
				</select>
				<input type="submit" name="submit" value="View" />
				</form>
			</div>
			<div id="search_products">
			<form id="SearchBox" method="POST" action="products.php">
				<input type="search" name="query" />
				<input type="submit" name="searchProducts" value="Search" />
			</form>
			</div>
		<?php
			if ($_POST['searchProducts']) {
				echo '<h4>Your search for "' . $_POST['query'] . '" returned the
following results: </h4>';
				displaySearchResults();
			}
			else if (isset($_POST['view_prod'])) {
				displayProducts();
			} else {
				displayAll();
			}
			
			if	($_GET['watch']) {
				$prodID = $_GET['watch'];
				echo '<script type="text/javascript"> document.getElementById(\''
. $prodID . '\').innerHTML = \'<p class="watching">You are watching
this item</p>\'; </script>';
				$result = mysql_query('INSERT INTO Users_Products VALUES (\'' .
$_SESSION['login'] . '\', \'' . $prodID . '\')');
			}
			if (isset($_POST['prodID'])) {
				$result = mysql_query('UPDATE Users_Products SET Purchased=1 WHERE Product_id="' . $_POST['prodID'] . '" AND Login="' . $_SESSION['login'] . '"');
			}
		?>
		</div>
	</div>
	</div>
</body>
</html>