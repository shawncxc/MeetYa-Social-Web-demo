<?php
	//login, retireve username from session
	session_start();
	if(!isset($_SESSION["username"])){
		$_SESSION["username"] = "guest";
	}
	
	$dbhost = "sql208.byethost17.com";
	$dbusername = "b17_16013309";
	$dbpassword = "JSjrc4un";
	$dbname = "b17_16013309_meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	//initialize map
	if($_SESSION["username"] == "guest"){// guest login only can see public activity
		$sql = "select * from activityform where activity_level = 'public'";
	}
	else{
		$sql = "select * from activityform"; 
	}
	$result = $conn -> query($sql);
	
	$lat = array();
	$lng = array();
	$activity = array();
	$poster_username = array();
	$starting_date = array();
	$participants = array();
	$count = 0;
	if($result -> num_rows > 0){
		while($row = $result -> fetch_assoc()){
			$lat[$count] = $row["lat"];
			$lng[$count] = $row["lng"];
			$activity[$count] = $row["activity"];
			$poster_username[$count] = $row["poster_username"];
			$number_of_limit[$count] = $row["number_of_limit"];
			$number_of_participants[$count] = $row["number_of_participants"];
			$starting_date[$count] = $row["starting_date"];
			$participants[$count] = $row["participants"];
			$count++;
		}
	}
	else{
		echo "0 result";
	}
	
	
		
	
	$conn -> close();
?>
<html>
	<head>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsEJ5G0mTcF4aTHtsS7GRZP1tJ_qDXO7I"></script>
		<script type="text/javascript">
			var geocoder;
			var map;
			var lat;
			var lng;
			var activity;
			var poster_username;
			var number_of_limit;
			var number_of_participants;
			var starting_date;
			var participants;
			
			//retrieve latitude, longtitude and activity onto the map from database
			function initialize() {
				lat = <?php echo json_encode($lat); ?>;
				lng = <?php echo json_encode($lng); ?>;
				poster_username = <?php echo json_encode($poster_username); ?>;
				activity = <?php echo json_encode($activity); ?>;
				number_of_limit = <?php echo json_encode($number_of_limit); ?>;
				number_of_participants = <?php echo json_encode($number_of_participants); ?>;
				starting_date = <?php echo json_encode($starting_date); ?>;
				participants = <?php echo json_encode($participants); ?>;
				
				var latlngIni = new google.maps.LatLng(lat[0], lng[0]);
				var mapOptions = {
					zoom: 14,
					center: latlngIni
				}
				map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
				
				//add marker and infowindow
				for(var i = 0; i < lat.length; i++){
					//marker part
					var latlng = new google.maps.LatLng(lat[i], lng[i]);
					var markerOptions = {
						map: map, 
						position: latlng, 
						animation: google.maps.Animation.DROP,
						info: '<h1>' + activity[i] + '</h1>' +
							'<p>Posted By ' + poster_username[i] + '</p>' +
							'<p>Starting Date: ' + starting_date[i] + '</p>' +
							'<p>Number of Participants: ' + number_of_participants[i] + '/' + number_of_limit[i] + '</p>' +
							'<p>We have already got: ' + participants[i].split("*") + '...</p>' +
							'<p>Are You Interested?</p>' +
							'<form method="post" action="join_mysql.php">' +
							'	<input type="hidden" name="guest_username" id="guest_username" value="<?php echo $_SESSION["username"] ?>">' +
							'	<input type="hidden" name="host_lat" id="host_lat" value="' + lat[i] + '">' +
							'	<input type="hidden" name="host_lng" id="host_lng" value="' + lng[i] + '">' +
							'	<input type="hidden" name="host_username" id="host_username" value="' + poster_username[i] + '">' +
							'	<input type="hidden" name="activity" id="activity" value="' + activity[i] + '">' +
							'	<input type="submit" value="Yep!!!">' +
							'</form>'
					}
					var marker = new google.maps.Marker(markerOptions);
					//infowindow part
					var infowindow = new google.maps.InfoWindow();
					//event set
					google.maps.event.addListener(marker, 'click', function(){
						infowindow.setContent(this.info);
						infowindow.open(map, this);
						map.setZoom(15);
						map.setCenter(this.getPosition());
						this.setAnimation(google.maps.Animation.BOUNCE);
					});
					google.maps.event.addListener(marker, 'dblclick', function(){
						this.setAnimation(null);
					});
				}
			}
			
			//Geocode: transfer address into longtitude and latitude
			function codeAddress() {
				geocoder = new google.maps.Geocoder();
				var address = document.getElementById("address_input").value;
				geocoder.geocode({'address': address}, function(results, status){
					if (status == google.maps.GeocoderStatus.OK) {
						document.getElementById("lat_input").value = results[0].geometry.location.lat();
						document.getElementById("lng_input").value = results[0].geometry.location.lng();
					} 
					else{
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
			}
			google.maps.event.addDomListener(window, 'load', initialize);

			function validationI(){
				var number_of_limit; var valid_number_of_limit;
				number_of_limit = document.getElementById("number_of_limit").value;
				if(isNaN(number_of_limit) || number_of_limit > 100 || number_of_limit < 0){
					valid_number_of_limit = "The number you entered is not propriate.(The limit is 100)";
					window.alert(valid_number_of_limit);
				} 	
				//document.getElementById("valid_number_of_limit").innerHTML = valid_number_of_limit;
			}
		</script>
	</head>
	
	<body>
		<h1> MeetYa !!! </h1>
		<div id="userlog" style="">
			<?php if($_SESSION["username"] == "guest"){ ?>
			<a href="http://localhost/meetya/login.php">Sign In</a>
			&nbsp;&nbsp;
			<a href="http://localhost/meetya/register.php">Sign Up</a>
			&nbsp;&nbsp;
			<?php 
				}
				else{
					echo "Welcome, " . $_SESSION["username"];
				} 
			?>
			<a href="http://localhost/meetya/logout.php">Logout</a>
		</div>
		<br><br>
		
		<div id="address_activity_input">
			<?php if($_SESSION["username"] != "guest"){ ?>
			<form method="post" action="save_latlng_act_mysql.php">
				<input type="text" id="address_input" name="address_input" value="Enter Address">
				<!--<input type="button" value="confirm address" onclick="codeAddress()">-->
				<input type="text" id="activity_input" name="activity_input" value="Enter Activity" onclick="codeAddress()"/>
				<br>
				<input type="text" id="number_of_limit" name="number_of_limit" value="Enter Limit"/>
				<input type="text" id="starting_date" name="starting_date" value="Enter Starting Date"/> YYYY-MM-DD HH:mm:SS
				<br>
				<input type="hidden" id="lat_input" name="lat_input">
				<input type="hidden" id="lng_input" name="lng_input">
				<input type="hidden" id="poster_username" name="poster_username" value="<?php echo $_SESSION["username"]; ?>">
				<select id="activity_level" name="activity_level">
					<option value="public">Public (Everyone can see)</option>
					<option value="private">Private (Only your friends can see)</option>
				</select>
				<input type="submit" value="submit">
			</form>
			<?php } ?>
		</div>
		<div id="map-canvas" style="height: 70%; width:70%; margin-left: auto; margin-right: auto; margin-top: 70px"></div>
		<div style = "text-align: center;">Copyright Xuchang Chen, 2015 - <?php echo date("Y") ?></div>
	</body>
</html>