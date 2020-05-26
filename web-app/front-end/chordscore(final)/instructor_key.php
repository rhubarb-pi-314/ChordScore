<?php
	include"database.php";
	session_start();
	if(!isset($_SESSION["account_id"]))
	{
		echo"<script>window.open('index.php?mes=Access Denied...','_self');</script>";
		
	}	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>My Key</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
			<?php include"navbar.php";?><br>
			
				<div id="section" style="width: 75%; margin-left: auto; margin-right: auto; margin-top: -5%;">
				
					<!--?php include"sidebar.php";?><br><br><br-->
				<div class="content">
						

					
						<h3 style="font-size: 50px;">My Key: <?php echo $_SESSION["instructor_key"]?></h3> 
						

			
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