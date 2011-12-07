<?php 
	function displayFriendsAndRequests() {
		echo '<form id="friend_search" method="post" action="homepage.php?view=friends">';
		echo '<input type="search" name="query" />';
		echo '<input type="submit" name="friendSearch" value="Search" />';
		echo '</form>';
		require_once('db_connect.php');
		
		// friends
		echo '<div id="my_friends">';
		echo '<h4>My Friends</h4>';
		$result = mysql_fetch_assoc(mysql_query('SELECT friends, Login FROM User WHERE Login = \'' . $_SESSION['login'] . '\''));
		$friends = explode(',', $result['friends']);
		array_shift($friends);
		echo '<table>';
		foreach ($friends as $friend) {
			$name = mysql_fetch_assoc(mysql_query('SELECT Name FROM User WHERE Login = \'' . $friend . '\''));
			echo '<tr><td>' . $name['Name'] . '</td></tr>';
		}
		echo '</table></div>';
		if (count($friends) == 0) { 
			echo '<tr><td>Use the search feature to find and add friends</td></tr>';
		}
		
		// friend requests
		echo '<div id="friend_requests">';
		echo '<h4>Friend Requests</h4>';
		$result = mysql_fetch_assoc(mysql_query('SELECT friend_requests, Login FROM User WHERE Login = \'' . $_SESSION['login'] . '\''));
		$requests = explode(',', $result['friend_requests']);
		array_shift($requests);
		echo '<table>';
		foreach ($requests as $friend) {
			$name = mysql_fetch_assoc(mysql_query('SELECT Name FROM User WHERE Login = \'' . $friend . '\''));
			echo '<tr><td>' . $name['Name'] . '</td><td> <a href="homepage.php?view=friends&fconfirm=' . $friend . '">Confirm</a></td><td> <a href="homepage.php?view=friends&rem_req=' . $friend . '">Remove Request</a> </td></tr>';
		}
		if (count($requests) == 0) { 
			echo '<tr><td>No Friend Requests at this time.</td></tr>';
		}
		echo '</table></div>';
	}
		

	function displayFriendSearchResults() {
	// NOTHING ECHOED IN THIS FUNCTION DISPLAYS IN THE SOURCE CODE AND I'M NOT SURE WHY
		require_once('db_connect.php');
		echo '<div id="friend_search_results">';
		$query = $_POST['query'];
		$result = mysql_query('SELECT * FROM User WHERE Name LIKE \'%' . $query . '%\' AND Login <> \'' . $_SESSION['login'] . '\'');
		echo '<h4>Your search for "' . $query . '" returned the following results:</h4>';
		echo '<table>';
		while ($user = mysql_fetch_assoc($result)) {
			echo '<tr><td>' . $user['Name'] . '</td><td><a href="homepage.php?view=friends&add=' . $user['Login'] . '">Add</a></td></tr>'; 
		}
		if (!$result) {
			echo '<tr><td>Your search returned no results.</td></tr>';
		}
		echo '</table></div>';
	}
		
	function addFriend() {
		require_once('db_connect.php');
		$requestLogin = $_GET['add'];
		$result = mysql_query('UPDATE User SET friend_requests = CONCAT_WS(\',\', friend_requests, \'' . $_SESSION['login'] . '\') WHERE Login = \'' . $requestLogin . '\' AND friend_requests NOT LIKE \'%' . $_SESSION['login'] . '%\'') or die('error ' . mysql_error());
		if (!$result) {
			echo '<p class="error">Sorry, your friend request was not sent.</p>';
		}
		else {
			echo '<p class="confirmation">Your friend request was sent and is awaiting confirmation.</p>';
		}	
	}
		
	function confirmFriend() {
		require_once('db_connect.php');
		$friendLogin = $_GET['fconfirm'];
		// update user friend list
		mysql_query('UPDATE User SET friends = CONCAT_WS(\',\', friends, \'' . $friendLogin . '\') WHERE Login = \'' . $_SESSION['login'] . '\' AND friends NOT LIKE \'%' . $friendLogin . '%\'') or die('error ' . mysql_error());
		// remove new friend from request list
			// get current friend_request list:
			$result = mysql_fetch_assoc(mysql_query('SELECT friend_requests, Login FROM User WHERE Login = \'' . $_SESSION['login'] . '\''));
			$requests = explode(',', $result['friend_requests']);
			array_shift($requests);
			// remove from array:
		$reqKey = array_search("$friendLogin", $requests);
		unset($requests[$reqKey]);
		array_unshift($requests, ''); // keep field format the same
			// reset friend_request field sans confirmed request
		$friendReqs = implode(',', $requests);
		mysql_query('UPDATE User SET friend_requests = \'' . $friendReqs . '\'') or die('error ' . mysql_error());
		// update new friends' friend list
		mysql_query('UPDATE User SET friends = CONCAT_WS(\',\', friends, \'' . $_SESSION['login'] . '\') WHERE Login = \'' . $friendLogin . '\'') or die('error ' . mysql_error());
		header("Location: homepage.php?view=friends");
	}
	
	function removeRequest() {
		require_once('db_connect.php');
		$friendLogin = $_GET['rem_req'];
		// remove login from request list
			// get current friend_request list:
			$result = mysql_fetch_assoc(mysql_query('SELECT friend_requests, Login FROM User WHERE Login = \'' . $_SESSION['login'] . '\''));
			$requests = explode(',', $result['friend_requests']);
			array_shift($requests);
			// remove from array:
			$reqKey = array_search("$friendLogin", $requests);
			unset($requests[$reqKey]);
			array_unshift($requests, ''); // keep field format the same
			// reset friend_request field sans unwanted request
		$friendReqs = implode(',', $requests);
		mysql_query('UPDATE User SET friend_requests = \'' . $friendReqs . '\'') or die('error ' . mysql_error());
		// update new friends' friend list
		mysql_query('UPDATE User SET friends = CONCAT_WS(\',\', friends, \'' . $_SESSION['login'] . '\') WHERE Login = \'' . $friendLogin . '\'') or die('error ' . mysql_error());
		header("Location: homepage.php?view=friends");
	}
?>