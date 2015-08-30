
<?php
	$dbhost = "localhost";
	$dbusername = "root";
	$dbpassword = "";
	$dbname = "meetya";
	
	$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
	
	if($conn -> connect_error){
		die("Connection failed: " . $conn -> connect_error);
	}
	
	$sql = "select * from activities";
	$result = $conn -> query($sql);
	
	$address = array();
	$activity = array();
	$count = 0;
	if($result -> num_rows > 0){
		while($row = $result -> fetch_assoc()){
			$address[$count] = $row["address"];
			$activity[$count] = $row["activity"];
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
		<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAw-UplLZBIMTrN7tuGfCKMPX1Pbcp2A6A">
		</script>
		
		<script>
			//load saved activities and address
			
			var geocoder;
			var map;
			
			function initialize() {
				geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(-34.397, 150.644);
				var mapOptions = {
				  zoom: 12,
				  center: latlng
				}
				map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			}

			function codeAddress() {
				var address = <?php echo json_encode($address); ?>;
				var activity = <?php echo json_encode($activity); ?>;

				for(var i = 0; i < address.length; i++){
					var activity_content = '<p>'+activity[i]+'</p>';
					geocoder.geocode( { 'address': address[i]}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							map.setCenter(results[0].geometry.location);
							var marker = new google.maps.Marker({
								map: map,
								position: results[0].geometry.location,
								info: activity_content
							});
							var infowindow = new google.maps.InfoWindow();
							google.maps.event.addListener(marker, 'click', function(){
								infowindow.setOptions({
									content: activity_content
								});
								infowindow.open(map, marker);
							});	
						} 
						else{
							alert("Geocode was not successful for the following reason: " + status);
						} 
					});
					//sleep(20);
					//setTimeout(function(){return;},2000);
					//var currentTime = new Date().getTime();
				    //while (currentTime + 200 >= new Date().getTime()) {}
				}
			}
			
			
			// function SetActivity(activityi){
				// var activity = '<p>' + activityi + '</p>';
				// var infowindow = new google.maps.InfoWindow({
					// content: activity
				// });
				// google.maps.event.addListener(marker, 'click', function(){
					// infowindow.open(map, marker);
				// });	
			// }
		</script>
	</head>
		<body onload="initialize();codeAddress();">
			<div id="address_activity_input">
				<form method="post" action="save_address_activity_mysql.php">
					<p id="activity_info"></p>
					<input type="text" id="address_input" name="address_input">
					<input type="text" id="activity_input" name="activity_input">
					<input type="submit" value="submit">
				</form>
			</div>
			<div id="map-canvas" style="height: 70%; width:70%; margin-left: auto; margin-right: auto; margin-top: 70px"></div>
		</body>
</html>
