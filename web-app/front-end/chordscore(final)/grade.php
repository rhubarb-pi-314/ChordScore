<?php
	include"database.php";
	session_start();
	if(!(isset($_SESSION["account_id"]) && ($_SESSION["account_type"]) == 'student'))
	{
		echo"<script>window.open('index.php?mes=Access Denied..','_self');</script>";
		
	}		
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Assignments</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		
			<div><?php include"navbar.php";?><br><br>

			<div id="section">
				<?php include"sidebar.php";?><br>
			
				
				<div class="content" style="margin-top: -35px;">


						<?php
							
								echo "<div class='success' style='width:98.5%'>Assignment Successfully Graded.</div>";
	
						?><br><br>
							
						<div id="img_ans" style="margin-left:30%;margin-right: auto;width: 70%;height: auto; display: none">
							<br>
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;">Grade: C</h3><br>
							<img src='answerkey_images/graded.png' style='transition:3s;width:813px;height:1050px;'><br>
						</div>

						
				
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

			$(document).ready(function() {
			    $('#img_ans').delay(3000).fadeIn(1000);
			});
	</script>
	</body>
</html>