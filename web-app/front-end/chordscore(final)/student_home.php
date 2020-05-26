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
		<title>Student | Home</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			<img src="img/1.jpg" style="margin-left:90px;" class="sha">
			
			<div id="section">
				<?php include"class_name.php";?>
				<?php include"sidebar.php";?><br>
				<div class="content">
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
	</script>
	</body>
</html>