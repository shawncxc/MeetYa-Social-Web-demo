<?php
	session_start();
	
	$lat = $_POST['host_lat'];
	$lng = $_POST['host_lng'];
	$activity = $_POST['activity'];
	$host_username = $_POST['host_username'];
	$guest_username = $_SESSION["username"];
	
	//echo $host_username . " will host at " . $lat . "," . $lng . " for " . $activity . ", do you want to come? " . $_SESSION["username"];
	
	$dbhost = "sql208.byethost17.com";
	$dbusername = "b17_16013309";
	$dbpassword = "JSjrc4un";
	$dbname = "b17_16013309_meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	$sql = "select number_of_limit, number_of_participants from activityform 
		where lat = $lat and lng = $lng and activity = '$activity' and poster_username = '$host_username'";
	$result = $conn -> query($sql);
	$row = $result -> fetch_assoc();
	
	if($row["number_of_participants"] < $row["number_of_limit"]){
		$number_of_participants = $row["number_of_participants"] + 1;
		$participants = $row["participants"] . $guest_username . "*";
		$sql = "update activityform 
			set number_of_participants = $number_of_participants, participants = '$participants'
			where lat = $lat and lng = $lng and activity = '$activity' and poster_username = '$host_username'";
		$result = $conn -> query($sql);
		header('Location: main_map.php');
	}
	else{
		header('Location: over_limits_error.php');
	}
	
	
	
?>