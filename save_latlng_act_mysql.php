<?php
	$dbhost = "sql208.byethost17.com";
	$dbusername = "b17_16013309";
	$dbpassword = "JSjrc4un";
	$dbname = "b17_16013309_meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	$lat = $_POST['lat_input'];
	$lng = $_POST['lng_input'];
	$activity = $_POST['activity_input'];
	$activity_level = $_POST['activity_level'];
	$poster_username = $_POST['poster_username'];
	$number_of_limit = $_POST['number_of_limit'];
	$starting_date = $_POST["starting_date"];
	
	$sql = "insert into activityform (lat, lng, activity, activity_level, poster_username, number_of_limit, number_of_participants, participants, starting_date) 
			values ($lat, $lng, '$activity', '$activity_level', '$poster_username', $number_of_limit, 0, '', '$starting_date')"; // starting_date
	
	if($conn -> query($sql)){
		header('Location: main_map.php');
	}
	else{
		echo "database execution error";
	}

	$conn -> close();
?>