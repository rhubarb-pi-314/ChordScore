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
			
				
				<?php include"class_name.php";?><br>
				<?php include"sidebar.php";?><br>
				<div class="content">
						
						<div class="tbox">
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;"> Assignments </h3><br>
							<?php
								if(isset($_GET["mes"]))
								{
									echo"<div class='error'>{$_GET["mes"]}</div>";	
								}
							
							?>
							<table class="table">
								<tr style="text-align: left;border-bottom: 1px solid black; background-color: #f5fcff;">
									<th>Assignment</th>
									<!--th>Completion Status</th-->
									<th>Score/Grade</th>
									<!--th>Status/Comment</th-->
									<th>Date Available</th>
									<th>Due Date</th>
								</tr>
								<?php

									$class="SELECT Assignment_Definition.title, Assignment.grade, Class_Assignment.open_datetime, Class_Assignment.due_datetime, Assignment_Definition.instructions, Assignment.assignment_id 
										     FROM Assignment_Definition
									         JOIN Assignment ON Assignment_Definition.assignment_def_id = Assignment.assignment_def_id
									         JOIN Class_Assignment ON Assignment.assignment_id = Class_Assignment.assignment_id
									         ";

									$res=$db->query($class);
									if($res->num_rows>0)
									{
										while($r=$res->fetch_assoc())
										{
											echo "
												<tr>
													<td><a class='assignment_link' href='view_assignment.php?id={$r["assignment_id"]}'>{$r["title"]}</a>
													<br>Attachments<br>
													{$r["instructions"]}</td>
													
													<td>{$r["grade"]}</td>
													<td>{$r["open_datetime"]}</td>
													<td>{$r["due_datetime"]}</td>
												
												</tr>
												";
										}
									}
									if($res->num_rows<=0){
										echo "
												<tr>
													<td>No Assignments Posted Yet</td>
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