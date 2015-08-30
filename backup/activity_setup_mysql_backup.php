<?php
	$dbhost = "localhost";
	$dbusername = "root";
	$dbpassword = "";
	$dbname = "meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	$address = $_POST["address_input"];
	$activity = $_POST["activity_input"];
	
	$sql = "insert into activities (address, activity)
			values ('$address', '$activity')";
	
	if($conn -> query($sql)){
		header('Location: mainpage.php');
	}
	else{
		echo "database execution error";
	}

	$conn -> close();
?>