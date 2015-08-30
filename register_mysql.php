<?php
	$dbhost = "sql208.byethost17.com";
	$dbusername = "b17_16013309";
	$dbpassword = "JSjrc4un";
	$dbname = "b17_16013309_meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	$username = $_POST["username"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	$sql = "insert into users (username, email, password)
			values ('$username', '$email', '$password')";
	
	if($conn -> query($sql)){
		header('Location: main_map.php');
	}
	else{
		echo "database execution error";
	}

	$conn -> close();
	
	
?>