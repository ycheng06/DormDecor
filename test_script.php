<?php
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
	global $productInfo;	
	$productInfo = array(
							'Product' => $Product,
							'Product_types' => $Product_types,
							'Vendors' => $Vendors
						);
					
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

?>
