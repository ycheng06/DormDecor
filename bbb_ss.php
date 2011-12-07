<?php
	require_once("db_connect.php");
/* connect to database
	$myUserName = "ycheng06";
	$myPassword = "Jason914";
	$myDatabase = "ycheng06";
	$myHost = "mysql-user";
	$db = mysql_connect($myHost, $myUserName, $myPassword);
	mysql_select_db($myDatabase) or die("Unable to select database");*/
// scrape search page	
	// desks
	$deskURL = "http://www.bedbathandbeyond.com/stylePage.asp?rn=2034&rnt=0&ipp=1000&";
	// chairs
	$chairURL = "http://www.bedbathandbeyond.com/stylePage.asp?RN=2025&";
	$url = $deskURL;
	$prodType = 'desk';
	//$url = $chairURL;
	//$prodType = 'chair';
	
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$content = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($status == 200) { 
		$imgPattern = '/(?<=class="styleitempic">)[\d\D]*(?=<\/div>)/U';
		$pricePattern = '/(?<=class="styleitemlink">).*\n.*\$[\d\D]*(?=<\/div>)/U';
		$urlPattern = '/(?<=class="styleitemlink">).*\n.*<[\d\D]*(?=<\/div>)/U';

		preg_match_all($imgPattern, $content, $matchI);
		preg_match_all($pricePattern, $content, $matchP);
		$foundItems = preg_match_all($urlPattern, $content, $matchU);
		
		if ($foundItems!=0) { 
			for($i=0; $i<$foundItems; $i++) {
					// connect to product page for description
					$url = substr($matchU[0][$i], strpos($matchU[0][$i], '"')+1);
					$url = substr($url, 0, strpos($url, '"'));
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$itemContent = curl_exec($ch);
					$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
		
				$descPattern = '/(?<=class="ppprodinfo">)[\d\D]*(?=<script)/U';
				$skuPattern = '/(?<=SKU=)[\d]+(?=&RN)/';
				preg_match($descPattern, $itemContent, $matchD);
				preg_match($skuPattern, $url, $matchSKU);
				
				$imgURL = substr($matchI[0][$i], strpos($matchI[0][$i], 'src="')+5);
				$imgURL = substr($imgURL, 0, strpos($imgURL, '"'));
			
				$itemName = strip_tags($matchU[0][$i]);
				print '<h4 class="item_name">' . $itemName . '</h4>'; // item name
				print $matchI[0][$i] . '<br>'; // item image
				print 'image url:   ' . $imgURL . '<br>'; // item image url
				$price = trim($matchP[0][$i]);
				print '$' . $price . '<br>';
				$description = substr($matchD[0], strpos($matchD[0], '<br><br>')+8); // item description
				print $description . '<br>';
				print 'SKU: ' . $matchSKU[0] . '<br>'; // SKU
				print $url . '<br>';
				print preg_replace('/(?<=>).*(?=<)/', 'View Product', $matchU[0][$i]) . '<br>'; // item link
				

				// insert into database
				$result = mysql_query('INSERT INTO Product (Product_id, Product_type, Product_desc, Product_price, Product_url, Vendor_name, Product_name, Product_image) VALUES(\'' . addslashes($matchSKU[0]) . '\', \'' . $prodType . '\', \'' . addslashes($description) . '\', \'' . $price . '\', \'' . addslashes($url) . '\', \'bed\' ,\'' . addslashes($itemName) . '\', \'' . addslashes($imgURL) . '\')');
				if (!$result) {
					echo 'error: ' . mysql_error();
				}
			}
		}
		else { 
			print "<h3>* product not found! *</h3>"; 
		}	
	}
?>
