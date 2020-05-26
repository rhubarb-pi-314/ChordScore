<?php
	include "database.php";
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ChordScore | Sign in</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="facade"> 
		<img style="width: 100%;margin-top: 40px;" src="img/facade.gif">
	</div>
	<div class="create_account_panel">
		<img style="width:35%; height: auto; padding: 0 0 15px 0; " src="img/logo.png">
		<h1 style="display: block; width:100% ">Create Account</h1>
		<p>Already have an account? <a href="index.php">Sign in</a>.</p>
				<?php
					function unique_generator($length){
					      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$~%&<>?.[]';
					      $string = '';

					      for ($i = 0; $i < $length; $i++) {
					          $string .= $characters[mt_rand(0, strlen($characters) - 1)];
					      }
					      
					      return $string;
				    }
				    
						if(isset($_POST["next"])){
							
							//hold input data
							$account_pwd = $_POST["account_pwd"];
							$account_email = $_POST["account_email"];


							$_SESSION["account_pwd"]=$account_pwd;
							$_SESSION["account_email"]=$account_email;
							echo "<script>window.open('add_account_info.php','_self');</script>";

						}
						
						if(isset($_GET["mes"])){
							echo "<div class='success'>{$_GET["mes"]}</div>";
						} 
					
				?>
			
					<form id="my_form" style="background-color: transparent;height: 700px;" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					  <div class="form_container">
					    <hr>

					    <label for="email"><b>Email</b></label>
					    <input type="email" name="account_email" placeholder="Enter Email" required class="input">

					    <label for="pwd"><b>Password</b></label>
					    <input type="password" name="account_pwd" placeholder="Enter Password" required class="input" id="password" onkeyup="checkPass()">

					    <div id="message">
						  <h3>Your password must contain the following:</h3>
						  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
						  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
						  <p id="number" class="invalid">A <b>number</b></p>
						  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
						</div>

					    <label for="pwd_repeat" id="password_label"><b>Repeat Password</b></label>
    					<input type="password" name="pwd_repeat" placeholder="Repeat Password" required class="input" id="password2" onkeyup="checkPass()">

    					<div id="confirmMessage" class="confirmMessage"></div>

					    <p>By signing in, you agree to our <a href="#CSterms&conditions">Terms & Privacy</a>.</p>

					    <button type="submit" id="btn" class="btn" name="next" style="width: 100px; float: right;">Next &rarr;</button>

					  </div>
					</form><br>

	</div>
			<div id="index_footer">Copyright &copy; <a style="text-decoration: none; color: #5ca08e;font-weight: bold;" href="contact.php">ChordScore</a> 2020</div>

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