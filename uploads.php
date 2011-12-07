<?php
	function displayUploader() {
		echo "<form enctype='multipart/form-data' method='POST' action='homepage.php?view=uploads'>";
		echo "<input type='hidden' name='action' value='upload' />";
		echo "Select a file to upload: <input type='file' name='uploadedFile' />";
		echo "<br/>Do you want this file to be shared? <input type='radio' name='toShare' value=1 /> Yes  ";
		echo "<input type='radio' name='toShare' value=0 /> No<br/>";
		echo "<input type='submit' name='submit' value='Upload File' />";
		echo "</form>";
	} 

	function displayUploads() {
		session_start();
		$handle = mysql_connect("mysql-user", "ycheng06", "Jason914");
		mysql_select_db("ycheng06");
		$queryPrivate = "SELECT * FROM Uploaded_Files WHERE User='" . $_SESSION['login'] . "' AND Public=0";
		$results = mysql_query($queryPrivate);
		echo "<br/><br/>Private Files: <br/>";
		echo "<table><tr><th>Filename</th><th>Uploaded On</th><th>File Type</th><th>Location</th></tr>";     
		while ($privResults = mysql_fetch_assoc($results)) {
			echo "<tr><td>" . $privResults['File_name'] . "</td><td>" . $privResults['Timestamp'] . "</td><td>";
			echo $privResults['File_type'] . "</td><td>" . $privResults['File_location'] . "</td></tr>";  
		}
		echo "</table><br/><br/>";
		$queryPublic = "SELECT * FROM Uploaded_Files WHERE Public=1";
		$results = mysql_query($queryPublic);
		echo "Shared Files: <br/>";
		echo "<table><tr><th>Filename</th><th>Uploaded By</th><th>Uploaded On</th><th>File Type</th><th>Location</th></tr>";
		while($pubResults = mysql_fetch_assoc($results)) {
			echo "<tr><td>" . $pubResults['File_name'] . "</td><td>" . $pubResults['User'] . "</td><td>";
			echo $pubResults['Timestamp'] . "</td><td>" . $pubResults['File_type'] . "</td><td>" . $pubResults['File_location'] . "</td></tr>";
		}
		echo "</table>";
    }	
	
    function uploadFile(){
		if(isset($_FILES['uploadedFile'])) {
			$uploads_directory = 'uploads/';
			$tmpname = $_FILES['uploadedFile']['tmp_name'];
			$filename = basename($_FILES['uploadedFile']['name']);
			$path = $uploads_directory . $filename;
			$type = $_FILES['uploadedFile']['type'];
			$login = $_SESSION['login'];
			$privacy = $_POST['toShare'];
			
			require_once('db_connect.php');
			$fileQuery = "INSERT INTO Uploaded_Files VALUES ('" . $filename . "', '" . $login . "', '" . $type . "', '" . $path . "', NOW(), '" . $privacy . "')";
			mysql_query($fileQuery) or die("Error: " . mysql_error());
						
			if(move_uploaded_file($tmpname, $path)) {
				echo "<p class=\"confirmation\">" . $filename . " has been successfully uploaded.</p><br/>";
			}
			else {
				echo "File upload error! Please try again.<br/><br/><br/><br/>";
			}

			mysql_close($handle);
 		}
 	}
?>