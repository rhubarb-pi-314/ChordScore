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
		<title>Assignments</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			
			<div id="section">

				<br><?php include"sidebar.php";?><br>
				<div class="content">
						
						<!-- enroll box-->
					    <div class="cabox" style="width: 55%;">
										<h3 style="margin-top:30px; font-size: 37px; text-align: center; "> Create New Assignment</h3>

										<form style="margin-bottom: 50; width: 75%; margin-left: auto;margin-right: auto;" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
										    

										    <label for="key" style="font-size: 20px; float: left;"><b>Assignment Title</b></label>
										    <input type="text" name="name" required class="input" id="name"><br>

										    <button type="submit" class="btn" name="submit" id="btn">Next</button><br><br><br>
										</form>

										<?php
											if(isset($_POST["submit"]))
											{
												
												$_SESSION["title"] = $_POST["name"];

												echo "<script>window.open('assignment_def.php?id=upload_define_and_create','_self');</script>";
												
											}
											
										?>

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
	</script>
	</body>
</html>