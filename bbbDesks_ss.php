<?php

	$url = "http://www.bedbathandbeyond.com/stylePage.asp?rn=2034&rnt=0&ipp=1000&";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$content = curl_exec($ch); // return type: big long string
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);


	echo '<h2>desks!</h2>';
	if ($status == 200) { 
		//$SKUstr = "$SKU";
		//(?<=class="styleitemlink">)
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
					$itemContent = curl_exec($ch); // return type: big long string
					$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
		
				$descPattern = '/(?<=class="ppprodinfo">)[\d\D]*(?=<script)/U';
				preg_match($descPattern, $itemContent, $matchD);
			
				print '<h4 class="item_name">' . strip_tags($matchU[0][$i]) . '</h4>'; // item name
				print $matchI[0][$i] . '<br>'; // item image
				print $matchP[0][$i] . '<br>'; // item price
				print substr($matchD[0], strpos($matchD[0], '<br><br>')+8) . '<br>'; // item description
				print preg_replace('/(?<=>).*(?=<)/', 'View Product', $matchU[0][$i]) . '<br>'; // item link
			}
		}
		else { 
			print "<h3>* product not found! *</h3>"; 
		}
		// preg_match(string $pattern, string $subject [,array &$matches [, int $flags [, int $offset ]]])
				
	}
?>
