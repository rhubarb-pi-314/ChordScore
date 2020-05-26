<?php
	include "database.php";
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>ChordScore | Sign in</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="facade" style="width: 65%;"> 
		<img style="width: 100%; margin-left: 25px;" src="img/facade.gif">
	</div>
	<div class="login_panel">
		<img style="width:75%; height: auto; padding: 0 0 80px 0;" src="img/logo.png">
				<?php
					if(isset($_POST["login"]))
					{
						$sql="SELECT * from Account where email='{$_POST["email"]}' and pwd='{$_POST["pwd"]}'";

						$res=$db->query($sql);
						if($res->num_rows>0)
						{
							$ro=$res->fetch_assoc();

							$_SESSION["account_id"]=$ro["account_id"];
			                $_SESSION["account_type"]=$ro["account_type"];
			                $_SESSION["email"]=$ro["email"];
			                $_SESSION["first_name"]=$ro["first_name"];
			                $_SESSION["last_name"]=$ro["last_name"];
			                $_SESSION["DOB"]=$ro["DOB"];
			                $_SESSION["pwd"]=$ro["pwd"];

							echo "<script>window.open('redirecting.php','_self');</script>";
						}
						else
						{
							echo "<div class='error'>Invalid Username or Password</div>";
						}
						
					}
					if(isset($_GET["mes"]))
					{
						echo "<div class='success'>{$_GET["mes"]}</div>";
					}
					
				?>
			
					<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					  <div class="form_container">
					    <hr>

					    <label for="email"><b>Email</b></label>
					    <input type="email" name="email" placeholder="Enter Email" required class="input">

					    <label for="pwd"><b>Password</b></label>
					    <input type="password" name="pwd" placeholder="Enter Password" required class="input">

					    <hr>
					    <p>By signing in, you agree to our <a href="#CSterms&conditions">Terms & Privacy</a>.</p>

					    <button type="submit" class="btn" name="login">Sign in</button>
					  
					   <div class="options">
					     <p><a href="#forgotpassword">Forgot Password?</a></p><p>New? <a href="create_account.php">Sign Up</a>.</p>
					   </div>
					  </div>
					</form>

	</div>
			<div id="index_footer">Copyright &copy; <a style="text-decoration: none; color: #5ca08e;font-weight: bold;" href="contact.php">ChordScore</a> 2020</div>

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