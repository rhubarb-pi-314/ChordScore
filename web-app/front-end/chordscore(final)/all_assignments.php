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
				
			

			<form id="ultimate_form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<div class="content" style="margin-top: 70px;">

						<div style="margin-left: 5%">
							<h2>Assign Work to Class/Classes</h2>
							<select name="class_name" id="select_item" style="display: inline;" placeholder="select">
								<option>Select Class</option>;
								<?php $class="SELECT * 
											     FROM Class
											     WHERE account_id = '{$_SESSION["account_id"]}'
										       ";

										$res=$db->query($class);
										if($res->num_rows>0)
										{
											while($r=$res->fetch_assoc())
											{
												$class_name = $r['class_name'];
												echo "
														<option value = '$class_name'>$class_name</option>;

													";
											}
										}
								?>
							</select>
						</div>

						<button class="btn" name="assign" style="width: 150px; float: right; margin-top: -77px; margin-right: 12%">Assign</button>
						<button class="btn" name="delete" style="width: 150px; float: right; margin-top: -77px; margin-right: 2%; background-color: red">Delete</button>
						<br>
						<br>

						
						<?php
							if(isset($_POST["assign"])){

								echo "<div class='success' style='width:98.5%'>Success: Assignment Posted.</div>";
								//$acc_id = $_SESSION["account_id"];
								//$title = $_SESSION["title"];	
							}


						?><br>
						<div class="tbox">
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;">All Assignments </h3><br>
							<?php
								if(isset($_GET["mes"]))
								{
									echo"<div class='success' style='width:99.2%'>{$_GET["mes"]}</div>";	
								}
							
							?>
							<table class="table">
								<tr style="text-align: left;border-bottom: 1px solid black; background-color: #f5fcff;">
									<th>Assignment</th>
									<th>Instructions</th>
									<th>Uploaded Answerkey File Name</th>
									<th>Date Created</th>
									<th style="text-align: right;">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
								</tr>
								<?php

									$class="SELECT * 
										     FROM Assignment_Definition
										     WHERE account_id = '{$_SESSION["account_id"]}'
									       ";

									$res=$db->query($class);
									if($res->num_rows>0)
									{
										while($r=$res->fetch_assoc())
										{
											echo "
												<tr>
													<td><a class='assignment_link' href='inst_view_assignment.php?id={$r["assignment_def_id"]}'>{$r["title"]}</a></td>
													
													<td>{$r["instructions"]}</td>
													<td>{$r["ak_file_name"]}</td>
													<td>{$r["datetime_created"]}</td>
													<td>
														<input style='float: right; width: 77px;' type='checkbox' id='checkbox' name='checkbox' value='{$r["assignment_def_id"]}'>
													</td>
												
												</tr>
												";
										}
									}
									if($res->num_rows<=0){
										echo "
												<tr>
													<td>No Assignments Created Yet</td>
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
			</form>
				
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