<?php
	$Product_desc = array();
	$Product_price = array();
	$Product_image = array();
	$Product_name = array();
	$Product_url = array();
	$Product_type = "chair";
	$Product_id = array();
	$Product_info = array();
	$All_products = array();
	//-----------------------
	$office_chair = "http://www.target.com/b/ref=in_se_pagelist_top_$i?ie=UTF8&node=3528011&searchSize=30&searchView=list&searchPage=$i&rh=&searchBinNameList=target_com_category-bin,target_com_primary_color-bin,price,material_type,collection_name-bin&searchRank=pmrank";
	
	$office_desk = "http://www.target.com/b/ref=in_se_pagelist_btm_$i?ie=UTF8&node=347642011&searchSize=30&searchView=list&searchPage=$i&rh=&searchBinNameList=target_com_category-bin,material_type,finish_types-bin,target_com_primary_color-bin,price,collection_name-bin&searchRank=pmrank";
	
	$regular_desk = "http://www.target.com/s/ref=in_se_pagelist_top_$i?ie=UTF8&keywords=desk&searchSize=30&searchView=list&searchNodeID=1038576|1287991011&searchPage=$i&fromGsearch=true&rh=subjectbin:1038614&searchBinNameList=subjectbin,price,target_com_primary_color-bin,target_com_size-bin,target_com_brand-bin&searchRank=target104545";

//-----------------------
//scrape from 9 pages	
for($i=1; $i<10; $i++){
	$url = "http://www.target.com/b/ref=in_se_pagelist_top_$i?ie=UTF8&node=3528011&searchSize=30&searchView=list&searchPage=$i&rh=&searchBinNameList=target_com_category-bin,target_com_primary_color-bin,price,material_type,collection_name-bin&searchRank=pmrank";
	$ch = curl_init(); 
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true);
	$content = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);   
	curl_close($ch);

	if ($status == 200) { 
		$valid = preg_match_all('/<div class="productTitle">\s*<a href="(\S*)">\s*(.*)\s*<\Wa>\s*<\Wdiv>/', $content, $title, PREG_SET_ORDER);
		$valid2 = preg_match_all('/(<span class="priceBlock(One|Price)(Price|Range)">\s*.*<span class="price.*?">(.*)<\Wspan>|<span class="outOfStock">\s*(.*)\s*<\Wspan>)/', $content, $price, PREG_SET_ORDER);
		$valid3 = preg_match_all('/<div class="productImage">\s*.*\s*<img\s?src="(\S*?)"/', $content, $image, PREG_SET_ORDER);
		$valid4 = preg_match_all('/<div class="features">([\d\D]*?)<\Wdiv>/', $content, $info, PREG_SET_ORDER);
		$valid5 = preg_match('/<div class="searchSlot.*?asins="(.*)">/', $content, $ID);
	}
		
		//correlate all the arrays
		if($valid && $valid2 && $valid3 && $valid4 && $valid5){
			$split_id = preg_split('/,/', $ID[1]);
			//array_push($Product_id, $split_id);
				for($j=0; $j<30; $j++){
					//echo ($split_id[$j]." <br/>");
					array_push($Product_id, strip_tags($split_id[$j]));
					array_push($Product_name, strip_tags($title[$j][2]));
					array_push($Product_url, strip_tags($title[$j][1]));
					array_push($Product_image, strip_tags($image[$j][1]));
					array_push($Product_price, strip_tags($price[$j][1]));
					array_push($Product_desc, $info[$j][1]);
				}
		}
}
    //$Product = one single product with all its property fields
    //$All_products = all the products scraped from the website
	for($k=0; $k<sizeof($Product_id); $k++){
		//echo ($Product_id[$k]." <br/>");
		//echo ($Product_name[$k]." <br/>");
		$Product = array(
						'Product_id' => $Product_id[$k],
						'Product_type' => $Product_type,
						'Product_desc' => $Product_desc[$k],
						'Product_price' => $Product_price[$k],
						'Product_image' => $Product_image[$k],
						'Product_name' => $Product_name[$k],
						'Product_url' => 'http://www.target.com'.$Product_url[$k],
						'Vendor_name' => 'target'
					);
		array_push($All_products, $Product);
	}

	//check for correct number of items. The printed number should be the same
	echo ("Number of Product ID: ".sizeof($Product_id)."<br/>");
	echo ("Number of Product URL: ".sizeof($Product_url)."<br/>");
	echo ("Number of Product image: ".sizeof($Product_image)."<br/>");
	echo ("Number of Product price: ".sizeof($Product_price)."<br/>");
	echo ("Number of Product description: ".sizeof($Product_desc)."<br/>");
	echo ("Number of items to be insert into database: ".sizeof($All_products)."<br/>");
	
	
	//---------------------------------------------------------------------
	//DATABASE 
	//------------
	$user_name = "ycheng06";
	$password = "Jason914";
	$database = "mysql-user";
	$con = mysql_connect($database, $user_name, $password) 
						or die("cannot connect to server because".mysql_error());	
	mysql_select_db($user_name);

	//insert product information
	foreach($All_products as $Single){
		$productid = $Single['Product_id'];
		$producttype = $Single['Product_type'];
		$productdesc = mysql_real_escape_string($Single['Product_desc']);
		$productprice = $Single['Product_price'];
		$productimage = mysql_real_escape_string($Single['Product_image']);
		$productname = mysql_real_escape_string($Single['Product_name']);
		$producturl = mysql_real_escape_string($Single['Product_url']);
		$vendorname = $Single['Vendor_name'];
		
		mysql_query("INSERT INTO Product (Product_id, Product_type, Product_desc, Product_Price, 
					Product_url, Vendor_name, Product_name, Product_image) 
					VALUES ('$productid', '$producttype', '$productdesc', '$productprice', '$producturl', '$vendorname', '$productname', '$productimage')")
					or die (mysql_error());
	}
	
	echo ("msyql insert passed");
	mysql_close($con);
	
	
?>