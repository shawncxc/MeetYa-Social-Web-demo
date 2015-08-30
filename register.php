<html>
	<head>
		<h1>MeetYa ---- New User Register</h1>
	</head>
	
	<body>
		<?php
			$username = $email = $password = $confrmpassword = "";
			$usernameErr = $emailErr = $passwordErr = $confrmpasswordErr = "";
			$EmptyErr = false;
			$passwordconfrmErr = false;
		
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if(empty($_POST["username"])){
					$usernameErr = "Username is required!!!";
					$EmptyErr = true;
				}
				else{
					$username = input_validation($_POST["username"]);
				}
				if(empty($_POST["email"])){
					$emailErr = "Email is required!!!";
					$EmptyErr = true;
				}
				elseif(!filter_var(input_validation($_POST["email"]), FILTER_VALIDATE_EMAIL)){
					$emailErr = "Please enter valid email address.";
					$EmptyErr = true;
				}
				else{
					$email = input_validation($_POST["email"]);
				}
				if(empty($_POST["password"])){
					$passwordErr = "Password is required!!!";
					$EmptyErr = true;
				}
				else{
					$password = input_validation($_POST["password"]);
				}
				if(empty($_POST["confrmpassword"])){
					$confrmpasswordErr = "Please confirm your password!!!";
					$EmptyErr = true;
				}
				elseif($_POST["confrmpassword"] != $password){
					$confrmpasswordErr = "Your password is not correctly confirmed!!!";
					$passwordconfrmErr = true;
				}
				else{
					$confrmpassword = input_validation($_POST["confrmpassword"]);
				}
			}
			
			function input_validation($input){
				$input = trim($input);
				$input = stripcslashes($input);
				$input = htmlspecialchars($input);
				return $input;
			}
		?>
	
		<form method = "post" action = "register_mysql.php">
			* required
			<br><br>
			Username:<input type = "text" name = "username" value = "<?php echo $username; ?>"> * 
			<?php echo $usernameErr;?>
			<br><br>
			Email:<input type = "text" name = "email" value = "<?php echo $email; ?>"> *
			<?php echo $emailErr;?>
			<br><br>
			Password:<input type = "text" name = "password"> *
			<?php echo $passwordErr;?>
			<br><br>
			Confirm Password:<input type = "text" name = "confrmpassword"> *
			<?php echo $confrmpasswordErr;?>
			<br><br>
			<input type = "submit" name = "submit" value = "submit">
		</form>
		
		<?php
			if($passwordconfrmErr == false && $EmptyErr == false){
				echo "<br><br>";
				echo "Your Username is:".$username;
				echo "<br><br>";
				echo "Your Email is:".$email;
				echo "<br><br>";
				echo "Your Password is:".$password;
				echo "<br><br>";
			}
		?>
		
		<br><br>
		<div style = "text-align: center;">Copyright Xuchang Chen, 2015 - <?php echo date("Y") ?></div>
	</body>

</html>