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
		<title>Account Settings</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
			<?php include"navbar.php";?><br>
			
				<div id="section" style="width: 75%; margin-left: auto; margin-right: auto; margin-top: -5%;">
				
					<!--?php include"sidebar.php";?><br><br><br-->
				<div class="content">
						<div class="profile">
							<img style="width: 20%" src="img/avatar.png" alt="Avatar" class="avatar">
							<span style="display: inline-block; margin-top: -20px;"><h4 style="text-transform:uppercase;float: left; color: #5ca08e;font-size: 35px"><?php echo $_SESSION["first_name"];?>&nbsp;<?php echo $_SESSION["last_name"];?></h4><br>
							<p style="text-transform:lowercase;font-size: 30px;float: left; margin-top: -25px; "><?php echo $_SESSION["email"]; ?></p><br>
							<p style="font-size: 30px;float: left; margin-top: -25px; "><?php echo $_SESSION["account_id"]; ?></p>
							</span>
						</div><br><hr style="width: 79%">

					
						<h3 style="font-size: 30px;">Change Password</h3>
						<?php
							if(isset($_POST["submit"]))
							{
								$sql="select * from Account where pwd='{$_POST["current_pwd"]}' and account_id='{$_SESSION["account_id"]}'";
								$result=$db->query($sql);
								if($result->num_rows>0)
								{
									if($_POST["new_pwd"]==$_POST["confirm_pwd"]){
										$s="update Account SET pwd='{$_POST["new_pwd"]}' where account_id='{$_SESSION["account_id"]}'";
										$db->query($s);
										echo "<div class='success' style='width=70%;>Password Changed</div>";
									}
									else{
										echo "<div class='error' style='width=70%;'>Password Mismatch</div>";
									}
								}
								else{
									echo "<div class='error' style='width=70%;'>Invalid Password</div>";
								}
							}
						?>
						
						
					<form method="post" id="my_form" action="<?php echo $_SERVER["PHP_SELF"];?>">
						
						<br><hr style="width: 79%">
						<label>Current Password</label><br>
						<input type="password" id="current_pwd" placeholder="Password" name="current_pwd" style="width: 77%" onkeyup="checkPass()"><br><br>
						
					  <span id="reset_password_form">
						<label>New Password</label><br>
						<input type="password" id="password" placeholder="New Password" name="new_pwd" style="width: 77%" onkeyup="checkPass()">

							<div id="message" style="width: 79%">
							  <h3>Your password must contain the following:</h3>
							  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
							  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
							  <p id="number" class="invalid">A <b>number</b></p>
							  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
							</div>

						<label>Confirm New Password</label><br>
						<input type="password" id="password2" placeholder="Confirm Password" name="confirm_pwd" style="width: 77%" onkeyup="checkPass()">
					  </span>
						<input type="checkbox" onclick="showPass()"> Visible
										<script>
										function showPass() {
										  var x = document.getElementById("current_pwd");
										  var y = document.getElementById("password");
										  var z = document.getElementById("password2");
										  if (x.type === "password") {
										    x.type = "text";
										    y.type = "text";
										    z.type = "text";
										  } else {
										    x.type = "password";
										    y.type = "password";
										    z.type = "password";

										  }
										}
										</script><br><br>
						<button type="submit" id="btn" class="btn" style="width: 25%; margin-bottom: 15%; float: right; margin-right: 20%;" name="submit"><span> Change Password</span></button><br><br>
						
					</form>
			
				</div>	
			</div>
			<?php include"footer.php";?>
			<script src="js/jquery.js"></script>
			<script>
					/*FIX FOR RESET FORM*/
					(function() {
					   'use strict';
					   /* jshint browser: true */
					   var d=document;
					   var mf=d.getElementById('my_form');
					   mf.reset();
					}());

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

					/** password validation*/

					var myInput = document.getElementById("password");
					var letter = document.getElementById("letter");
					var capital = document.getElementById("capital");
					var number = document.getElementById("number");
					var length = document.getElementById("length");

					// When the user clicks on the password field, show the message box
					myInput.onfocus = function() {
					  document.getElementById("message").style.display = "block";
					}

					// When the user clicks outside of the password field, hide the message box
					myInput.onblur = function() {
					  document.getElementById("message").style.display = "none";
					}

					// When the user starts to type something inside the password field
					myInput.onkeyup = function() {
					  // Validate lowercase letters
					  var lowerCaseLetters = /[a-z]/g;
					  if(myInput.value.match(lowerCaseLetters)) {  
					    letter.classList.remove("invalid");
					    letter.classList.add("valid");
					  } else {
					    letter.classList.remove("valid");
					    letter.classList.add("invalid");
					  }
					  
					  // Validate capital letters
					  var upperCaseLetters = /[A-Z]/g;
					  if(myInput.value.match(upperCaseLetters)) {
					    capital.classList.remove("invalid");
					    capital.classList.add("valid");
					  } else {
					    capital.classList.remove("valid");
					    capital.classList.add("invalid");
					  }

					  // Validate numbers
					  var numbers = /[0-9]/g;
					  if(myInput.value.match(numbers)) {  
					    number.classList.remove("invalid");
					    number.classList.add("valid");
					  } else {
					    number.classList.remove("valid");
					    number.classList.add("invalid");
					  }
					  
					  // Validate length
					  if(myInput.value.length >= 8) {
					    length.classList.remove("invalid");
					    length.classList.add("valid");
					  } else {
					    length.classList.remove("valid");
					    length.classList.add("invalid");
					  }
					}

					function checkPass() {
						var get_elem = document.getElementById,
						pass1 = document.getElementById('password'),
						pass2 = document.getElementById('password2'),
						message = document.getElementById('confirmMessage'),
						button = document.getElementById('btn'),
						colors = {
							goodColor: "#f1f1f1",
							goodColored: "#087a08",
							goodBtnColor: "#1C4966",
							badColor: "pink",
							badColored:"#ed0b0b",
							badBtnColor: "#adadad"
						},
						strings = {
							"confirmMessage": ["Password matches!", "Password does not match."]
						};

						if(password.value === password2.value && (password.value + password2.value) !== "") {
							password2.style.backgroundColor = colors["goodColor"];
							message.style.color = colors["goodColored"];
							message.innerHTML = strings["confirmMessage"][0];
							button.style.backgroundColor = colors["goodBtnColor"];
							$('.btn').prop('disabled', false); //to enable button

						}
						else if(!(password2.value === "")) {
							password2.style.backgroundColor = colors["badColor"];
							message.style.color = colors["badColored"];
							message.innerHTML = strings["confirmMessage"][1];
							button.style.backgroundColor = colors["badBtnColor"];
							$('.btn').prop('disabled', true); //to disable button
						}
						else {
							message.innerHTML = "";	
						}

					}

				$(document).on('keyup',"#current_pwd",function(){
				     var val = $(this).val();
				     var htmlString="<?php echo $_SESSION["pwd"]; ?>";
				     var current_pwd_input = document.getElementById("current_pwd");

				     $("#reset_password_form").hide();
				     
				     if(current_pwd_input.value == htmlString) 
				     {
				          $("#reset_password_form").show();
				     }
				})

				$(document).on('keyup',"#password",function(){
				     var val = $(this).val();
				     
				     $("#password_label").hide();
				     $("#password2").hide();
				     $("#confirmMessage").hide();

				     if(myInput.value.length >= 8) 
				     {
				          $("#password_label").show();
				          $("#password2").show();
				          $("#confirmMessage").show();
				     }
				})
			</script>
		
	</body>
</html>