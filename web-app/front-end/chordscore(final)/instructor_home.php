<?php
	include"database.php";
	session_start();
	if(!isset($_SESSION["account_id"]))
	{
		echo"<script>window.open('index.php?mes=Access Denied..','_self');</script>";
		
	}		
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Instructor | Home</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body onload="greeting()">
		<?php include"navbar.php";?><br>
			<img src="img/1.jpg" style="margin-left:90px;" class="sha">

			<?php

						$sql="SELECT * from Instructor_Key where account_id='{$_SESSION["account_id"]}'";

						$res=$db->query($sql);
						if($res->num_rows>0)
						{
							$ro=$res->fetch_assoc();

							$_SESSION["instructor_key"]=$ro["instructor_key"];

						}
						else
						{
							echo "<div class='error'>Something Went Wrong. Please Contact Support.</div>";
						}

				?>


			
			<div id="section">
			
				<?php include"sidebar.php";?><br>
				<p id="greeting" style="display:block; float: right; margin-top: 30px; margin-right: 25px; font-size: 25px;"></p>
				
				<div class="content" style="right:0; margin-top: 50px">
						<h3>Announcements</h3><br>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultricies eget ante vulputate tempus. Ut vulputate nisl sed lorem posuere mollis. Suspendisse congue tempor arcu, at lacinia lorem sollicitudin eget. Nullam sit amet hendrerit nisi. Suspendisse feugiat ut tellus quis aliquam. Mauris euismod justo quis volutpat accumsan. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum ut hendrerit lorem.
					</p>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultricies eget ante vulputate tempus. Ut vulputate nisl sed lorem posuere mollis. Suspendisse congue tempor arcu, at lacinia lorem sollicitudin eget. Nullam sit amet hendrerit nisi. Suspendisse feugiat ut tellus quis aliquam. Mauris euismod justo quis volutpat accumsan. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum ut hendrerit lorem.
					</p>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultricies eget ante vulputate tempus. Ut vulputate nisl sed lorem posuere mollis. Suspendisse congue tempor arcu, at lacinia lorem sollicitudin eget. Nullam sit amet hendrerit nisi. Suspendisse feugiat ut tellus quis aliquam. Mauris euismod justo quis volutpat accumsan. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum ut hendrerit lorem.
					</p>
				
					<br><br>
						<p style="border-top: #5ca08e 3px solid;float:left;width:100%;"></p>
					<br><br>
				</div>
				
			</div>
	
		<?php include"footer.php";?>
	<script src="js/jquery.js"></script>
	<script>
			$(document).ready(function(){
				$(".error").fadeTo(1500, 200).slideUp(1500, function(){
						$(".error").slideUp(1000);
				});
				
				$(".success").fadeTo(1000, 100).slideUp(1000, function(){
						$(".success").slideUp(1000);
				});
			});

			jQuery(window).load(function() {
			    jQuery("body").addClass('all-loaded');
			});

			      function greeting() {
				  var greeting;
				  var time = new Date().getHours();
				  if (time < 10) {
				    greeting = "Good morning <?php echo $_SESSION["first_name"];?>.";
				  } else if (time < 20) {
				    greeting = "Good day <?php echo $_SESSION["first_name"];?>.";
				  } else {
				    greeting = "Good evening <?php echo $_SESSION["first_name"];?>.";
				  }
				  document.getElementById("greeting").innerHTML = greeting;
			   }
	</script>
	</body>
</html>