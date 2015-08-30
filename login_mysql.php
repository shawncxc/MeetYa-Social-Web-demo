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
	$password = $_POST["password"];
	
	$sql = "select * from users where username = '$username' and password = '$password'";
	$result = $conn -> query($sql);
	
	if($result -> num_rows > 0){
		$row = $result -> fetch_assoc();
		if($row["username"] == $username && $row["password"] == $password){
			session_start();
			$_SESSION["username"] = $username;
			header('Location: main_map.php');
		}
		else{
			header('Location: login_error.php');
		}
	}
	else{
		header('Location: login_error.php');
	}

	$conn -> close();
	
	
?>