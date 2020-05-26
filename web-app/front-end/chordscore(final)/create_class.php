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
		<title>Create Class</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			
			<div id="section">
			
				<?php include"sidebar.php";?><br>

				<div class="content" style="width: 60%; margin-top: 5%; margin-right:155px;">
						
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;"> New Class </h3><br>
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
										$cname = $_POST["class_name"];
										$sem = $_POST["semester"];
										$start = $_POST["start"];
										$end = $_POST["end"];

										$acc_id = $_SESSION["account_id"];
										$c_id = unique_generator(9);
										$c_key = unique_generator(15);


										$sq="INSERT INTO Class(class_id, account_id,class_key,class_name,semester,start_date,end_date) VALUES('$c_id','$acc_id','$c_key','$cname','$sem','$start','$end')";

										if($db->query($sq)){
												echo "<script>window.open('all_classes.php?mes=Class has been created.','_self');</script>";
											}
											else{
												echo "<div class='error'>Insert failed..</div>";
											}	
											
									}
									
									if(isset($_GET["mes"])){
										echo "<div class='success'>{$_GET["mes"]}</div>";
									} 
								
							?>
						
								<form id="my_form" style="background-color: transparent;height: 637px; width: 77%" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
								  <div class="form_container">
								    <hr>

								    <label for="class_name"><b>Class Name</b></label><br>
								    <input type="text" name="class_name" placeholder="Class Name" required class="input"><br>

								    <label for="semester">Choose Semester:</label><br>
								    <select id="select_item" name="semester" required>
								      <option>Select</option>
								      <option value="Summer">Summer</option>
								      <option value="Fall">Fall</option>
								      <option value="Spring">Spring</option>
								    </select><br>

								    <label for="start"><b>Start Date</b></label><br>
			    					<input type="date" name="start" required class="input"><br>
			    					<label for="end"><b>End Date</b></label><br>
			    					<input type="date" name="end" required class="input"><br>

								    <button type="submit" id="btn" class="btn" name="next" style="width: 150px; float: right;">Create Class</button>
								  </div>
								</form>
				
					
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