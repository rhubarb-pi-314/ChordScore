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
		<title>All Classes</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			
			<div id="section">
			
				<br><?php include"sidebar.php";?>
				<div class="content">
						
						<br><a style='color: #fefefe; text-decoration:none; border:#5ca08e 1px solid; border-radius:20px; padding:7px; background-color:#5ca08e; margin-left: 10px;' href='create_class.php'>Add New Student
							</a><br><br>
						<div class="tbox">
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;"> All Students </h3>
							<?php
								if(isset($_GET["mes"]))
								{
									echo"<div class='success' style='width:99%'>{$_GET["mes"]}</div>";	
								}
							
							?>
							<table class="table">
								<tr style="text-align: left;border-bottom: 1px solid black; background-color: #f5fcff;">
									<th>First Name</th>
									<!--th>Completion Status</th-->
									<th>Last Name</th>
									<!--th>Status/Comment</th-->
									<th>Email Address</th>
									<th>DOB</th>
									<th>Remove Student</th>
								</tr>
								<?php

									$class="SELECT *
										     FROM Account 
										     JOIN Class_List
										     WHERE account_type = 'student'
									         ";
									
									$res=$db->query($class);
									
									if($res->num_rows>0)
									{
										while($r=$res->fetch_assoc())
										{	
											//$count="SELECT COUNT(account_id) as total
													//FROM Class_List
													//WHERE class_id = '{$r["class_id"]}'
													//";
											
											//$count_res=$db->query($count);
											//$rcount=$count_res->fetch_assoc();

											echo "
												<tr>
													<td>{$r["first_name"]}</td>			
													<td>{$r["last_name"]}</td>
													<td>{$r["email"]}</td>
													<td>{$r["DOB"]}</td>
													<td style='text-align:center;font-size:25px;'><a style='color:#333; text-decoration:none;' href='instructor_delete_class.php?id={$r["class_id"]}' class='fa fa-trash'></a></td>

												
												</tr>
												";
										}
									}
									if($res->num_rows<=0){
										echo "
												<tr>
													<td>No Classes Created Yet</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
												";
									}
								?>
							
							</table>
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