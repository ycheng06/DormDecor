<?php
	//require_once("db_connect.php");

	session_start();
	if(isset($_SESSION['login']) == false) {
		header("Location: index.php");
	}
?>

<!DOCTYPE html> 
<html> 
<head> 
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
	<meta name="viewport" content="width=808" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<link rel="stylesheet" type="text/css" href="mobileVendors.css" media="only screen and (max-device-width:480px)" />
	<link rel="stylesheet" type="text/css" href="homepage.css" media="print, screen and (min-device-width:480px)" />
	<title>Google Map</title> 
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript" src="json2.js"></script>
	<script type="text/javascript"> 

		var map;
		var userLat;
		var userLng;
		var userLocation;
		var targetLatLngs = new Array();
		var bedbathandbeyondLatLngs = new Array();
		
		
		function addTargetLatLngs() {
			targetLatLngs[0] = new google.maps.LatLng(42.377453, -71.089707);
			targetLatLngs[1] = new google.maps.LatLng(42.401996, -71.069696);
			targetLatLngs[2] = new google.maps.LatLng(42.362755, -71.156657);
			targetLatLngs[3] = new google.maps.LatLng(42.324597, -71.065080);
		}

		function addBedBathAndBeyondLatLngs() {
			bedbathandbeyondLatLngs[0] = new google.maps.LatLng(42.394048, -71.083504);
			bedbathandbeyondLatLngs[1] = new google.maps.LatLng(42.401737, -71.069945);
			bedbathandbeyondLatLngs[2] = new google.maps.LatLng(42.344828, -71.104319);
			bedbathandbeyondLatLngs[3] = new google.maps.LatLng(42.324597, -71.065080);
			bedbathandbeyondLatLngs[4] = new google.maps.LatLng(42.269695, -71.142398);
			bedbathandbeyondLatLngs[5] = new google.maps.LatLng(42.488537, -71.017291);
		}
		
		function detectBrowser() {
			var useragent = navigator.userAgent;
			var mapdiv = document.getElementById("map_canvas");
    
			if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
				mapdiv.style.width = '100%';
				mapdiv.style.height = '100%';
			}
			else {
				mapdiv.style.width = '600px';
				mapdiv.style.height = '800px';
			}
		}
		
		function initialize() {
		// W3C Geolocation
			//detectBrowser();
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					userLat = position.coords.latitude;
					userLng = position.coords.longitude;
					userLocation = new google.maps.LatLng(userLat, userLng);
					addTargetLatLngs();
					addBedBathAndBeyondLatLngs();
					renderMap();
				});
			} 
			else {
				alert("Geolocation is not supported on your browser!");
			}
		}
		
		function renderMap() {
			var mapOptions = {
				zoom: 12,
				center: userLocation,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			placeMarkers();
		}
		
		function placeMarkers() {
			var userTitle = "You are here!";
			userMarker = new google.maps.Marker({map: map, position: userLocation, title: userTitle});
			var targetTitle = "Target";
			targetMarkers = new Array();
			targetIcon="target.png";
			for(i=0; i < targetLatLngs.length; i = i+1) {
				targetMarkers[i] = new google.maps.Marker({
					map: map,
					position: targetLatLngs[i],
					title: targetTitle,
					icon: targetIcon
				});
			}
			var bbbTitle = "Bed, Bath, and Beyond";
			bbbMarkers = new Array();
			bbbIcon = "bbb.png";
			for(i=0; i < bedbathandbeyondLatLngs.length; i = i+1) {
				console.log("printing" . bedbathandbeyondLatLngs);
				bbbMarkers[i] =new google.maps.Marker({
					map: map,
					position: bedbathandbeyondLatLngs[i],
					title: bbbTitle,
					icon: bbbIcon
				});
			}
		}

	</script> 
</head> 
<body onload="initialize()"> 
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
			<a href="products.php">Products</a><br/>
			<a href="vendors.php">Vendors</a><br/>
		</div>	
		<div id="map_div"><div id="map_canvas"></div></div><br/>
	</div>
	<div id="link">
		<div class="vendor"><a href="http://www.target.com">Visit Target</a></div><br/>
		<div class="vendor"><a href="http://www.bedbathandbeyond.com">Visit Bed, Bath, and Beyond</a></div><br/>
	</div>
	</div>
</body> 
</html> 
