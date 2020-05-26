<?php
	include "database.php";
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>CS | Personal Information</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
		<button id="back_btn" type="submit" class="btn" name="back" onclick="window.history.back()">&larr; Back</button>
	<div class="creating_account_panel">
				
				<?php
					function unique_generator($length){
					      $characters = '0123456789ABC';
					      $string = '';

					      for ($i = 0; $i < $length; $i++) {
					          $string .= $characters[mt_rand(0, strlen($characters) - 1)];
					      }
					      
					      return $string;
				    }

					if(isset($_POST["create_account"])){

						$acc_type = $_POST["account_type"];
						$email = $_SESSION["account_email"];
						$fname = $_POST["fn"];
						$lname = $_POST["ln"];
						$DOB = $_POST["DOB"];
						$passw = $_SESSION["account_pwd"];

						//account id
						if ($acc_type == 'student'){
						    $acc_id = 'stud' . unique_generator(5);
						}elseif ($acc_type == 'instructor'){
						    $acc_id = 'inst' . unique_generator(5);
						}else echo "<script>window.open('index.php?mes=Access Denied..','_self');</script>";

						$_SESSION["account_id"] = $acc_id;
						$_SESSION["account_type"] = $acc_type;

						$sq="INSERT INTO Account(account_id,account_type,email,first_name,last_name,DOB,pwd) VALUES('$acc_id','$acc_type','$email','$fname','$lname','$DOB','$passw')";

						if($db->query($sq))
							{
								echo "<script>window.open('new_user_redirect.php','_self');</script>";
							}
							else
							{
								echo "<div class='error'>Insert failed..</div>";
							}	
							
						}
				        if(isset($_GET["mes"])){
						echo "<div class='error'>{$_GET["mes"]}</div>";
						}
				?>
			
					<form id="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
						  <img style="width:50%; height: auto; padding: 0 0 15px 0; " src="img/logo.png">
						  <h1 style="display: block; width:100%;">Personal Information</h1>
						  <div class="form_container">
						    <hr>

						    <label for="fn"><b>First Name</b></label><br>
						    <input type="text" name="fn" placeholder="Enter first name" required class="input">

						    <label for="ln"><b>Last Name</b></label><br>
						    <input type="text" name="ln" placeholder="Enter last name" required class="input">

						    <label for="DOB"><b>D.O.B<b></label><br>
	  						<input type="date" id="DOB" type="date" name="DOB" required><br>

	    					<label for="select_item">Choose Account Type:</label><br>
						    <select id="select_item" name="account_type" required>
						      <option>Select</option>
						      <option value="instructor">Instructor</option>
						      <option value="student">Student</option>
						    </select>

						    <span id="key">
						    	<br><label for="instructor_key"><b>Instructor Key</b></label>
	    						<input type="text" name="instructor_key" placeholder="Enter Key" class="input">
	    					</span>

						    <button type="submit" class="btn" name="create_account">Create Account</button>
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

			(function() {
			   'use strict';
			   /* jshint browser: true */

			   var d=document;
			   var mf=d.getElementById('myform');
			   var key=d.getElementById('key');
			   var item=d.getElementById('select_item')
			   var temp;

			   mf.reset(); 
			   key.className='hide';
			   item.onchange=function() {
			if(this.value==='instructor') {
			   key.className=key.className.replace('hide','');
			 }
			else {
			   temp=this.value;
			   key.className='hide';
			   item.value=temp;
			  }
			 };
			}());
	</script>
</body>

</html>