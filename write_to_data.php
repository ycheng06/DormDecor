<?php
	//require_once("db_connect.php");	

	$Product = array(
						'Product_type_id' => '1',
						'Product_id' => '99118394',
						'Product_desc' => 'this is a table',
						'Price' => '12.00',
						'Product_url' => 'http://www.target.com/s?keywords=desk&searchNodeID=1038576|1287991011&ref=sr_bx_1_1&x=0&y=0',
						'Vendor_id' => '1'
					);
	$Product_types = array(
						'Product_type_id' => '1',
						'Product_name' => 'awesome desk',
						'Color' => 'rainbow',
						'Width' => '10.00',
						'Height' => '10.00'
					);
					
	$Vendors = array(
						'Vendor_id' => '1',
						'Vendor_name' => 'target',
						'Latitude' => '5',
						'Longitude' => '5', 
						'url' => 'http://www.target.com/ref=nav_2_t_logo'
					);
	
	//every screen scraped product should have a final associative array like this
	$productInfo = array(
							'Product' => $Product,
							'Product_types' => $Product_types,
							'Vendors' => $Vendors
						);				
	/*
	foreach ($Product as $product){
		echo $product;
	}
	echo "<br/>";
	foreach ($Product_types as $types){
		echo $types;
	}
	echo "<br/>";
	foreach ($Vendors as $vendors){
		echo $vendors;
	}	
	echo "<br/>";
*/
		$user_name = "ycheng06";
		$password = "Jason914";
		$database = "mysql-user";
		$con = mysql_connect($database, $user_name, $password) or die("cannot connect to server because".mysql_error());	
		mysql_select_db($user_name);
		
		//insert vendor information
		$vendorid = $productInfo['Vendors']['Vendor_id'];
		$name = $productInfo['Vendors']['Vendor_name'];
		$long = $productInfo['Vendors']['Longitude'];
		$lat = $productInfo['Vendors']['Latitude'];
		$vendorurl = mysql_real_escape_string($productInfo['Vendors']['url']);
		mysql_query("INSERT INTO Vendors VALUES ('$vendorid', '$name', '$lat', '$long', '$vendorurl')")
					or die (mysql_error());
		
		//insert product type information
		$product_type_id = $productInfo['Product_types']['Product_type_id'];
		$productname = $productInfo['Product_types']['Product_name'];
		$color = mysql_real_escape_string($productInfo['Product_types']['Color']);
		$height = $productInfo['Product_types']['Height'];
		$width = $productInfo['Product_types']['Width'];
		mysql_query("INSERT INTO Product_types VALUES ('$product_type_id', '$productname', '$color',
					  '$height', '$width')") or die (mysql_error());
		
		//insert product information
		$typeid = $productInfo['Product']['Product_type_id'];
		$productid = $productInfo['Product']['Product_id'];
		$productdesc = mysql_real_escape_string($productInfo['Product']['Product_desc']);
		$price = $productInfo['Product']['Price'];
		$url = mysql_real_escape_string($productInfo['Product']['Product_url']);
		$vendorid = $productInfo['Product']['Vendor_id'];
		
		mysql_query("INSERT INTO Product (Product_type_id, Product_id, Product_desc, Price,
											Product_url, Vendor_id) VALUES ('$typeide', 
											'$productid', '$productdesc', '$price', '$url', '$vendorid')")
												  or die (mysql_error());
		mysql_close($con);

?>