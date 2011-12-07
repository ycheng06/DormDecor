<?php
	function displayFeed($login, $search) {
		$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
		mysql_select_db("ycheng06");
		$friendQuery = "SELECT friends FROM User WHERE Login='" . $login . "'";
		$friends = mysql_fetch_assoc(mysql_query($friendQuery));
		$friends = explode(",", $friends['friends']);
		array_shift($friends);
		$count = 0;		
		foreach($friends as $friend) {
			$idQuery = "SELECT * FROM Users_Products WHERE Login='" . $friend . "'";
       		$prodId = mysql_query($idQuery) or die(mysql_error());
       		while($id = mysql_fetch_assoc($prodId)) { 
               $puchased = $id['Purchased'];
               $query = "SELECT * FROM Product WHERE Product_id='" . $id['Product_id'] . "'" . $search;
               $resource = mysql_query($query) or die(mysql_error());	
        			while($result = mysql_fetch_assoc($resource)) {
           				$store;
           				switch($result['Vendor_name']) {
           	 				case "target":
              					$store = "Target";
              					break;
            				case "bed":
              					$store = "Bed, Bath, and Beyond";
              					break;
           				}
           				$action;
           				if($purchased == 1) {
           					$action = " purchased ";
           				}
           				else {
           					$action = " is watching ";
           				}
          				echo "<div class='feedData'>" . $friend . $action . "the " . $result['Product_name'] . " from " . $store . " for " . $result['Product_Price'] . "!!! Check it out for yourself <a href='" . $result['Product_url'] . "'>here</a>.</div><br/><br/>"; 
		 			}
			 	$count = $count + 1;
			}
			
		}
		mysql_close();
		return $count;
	}
?>