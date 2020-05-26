<?php
	include"database.php";
	session_start();
	if(!(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='instructor'))
	{
		echo"<script>window.open('index.php?mes=Access Denied..','_self');</script>";
		
	}	
?>

<!DOCTYPE html>
<html>
<head>
    <title>Class Home</title>
    <link rel="shortcut icon" href="../branding/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/student_index.css">
 
<!--repeating code -->  
</head>

<header>
<!--Logo -->
    <div class="header">
      <a href="student_index.php"><img class="logo" src="img/logo.png"></a>
    </div>

<!--Navidation -->
    <div id="mySidenav" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" id="closebtn" onclick="closeNav()">&times;</a>

      <div class="profile">
		<img style="" src="img/avatar.png" alt="Avatar" class="avatar">
		<span style="display: inline-block; margin-left: 50px; margin-bottom: 30px;"><h4 style="text-transform:uppercase;float: left; color: #5ca08e;"><?php echo $_SESSION["first_name"];?>&nbsp; <?php echo $_SESSION["last_name"];?></h4><br>
		<p style="text-transform:lowercase;font-size: 17px;float: left; color: white; "><?php echo $_SESSION["email"]; ?></p></span><hr>
	</div>
      <a href="instructor_home.php">Home</a>
      <a href="my_key.php" target="_blank">My Key</a>
      <a href="account_settings.php">Account Settings</a>
      <a href="signout.php">Signout</a>
    </div>

    <div style="width: 50px; height: 1px; float: right">
      <h1 id="toggle" style="font-size:30px;cursor:pointer;" onclick="openNav(); ">&#9776;</h1>
    </div>
</header>

<!-- PAGE CONTENT -->
<body id="main" onload="startTime()">

 	<?php 
 		$_SESSION["class_id"]=$_GET["id"];

 							$class="
									SELECT *
									FROM Class c, Instructor_Key ik, Account a, Class_List cl
									WHERE c.account_id = ik.account_id
									AND ik.account_id = a.account_id
									AND cl.class_id = c.class_id
									AND cl.class_id = '{$_SESSION["class_id"]}'

								   ";

							$res=$db->query($class);
							$ro=$res->fetch_assoc();

							if($res->num_rows>0){

								$_SESSION["class_name"]=$ro["class_name"];
				                $_SESSION["class_key"]=$ro["class_key"];
				                $_SESSION["semester"]=$ro["semester"];
				                $_SESSION["start_date"]=$ro["start_date"];
				                $_SESSION["end_date"]=$ro["end_date"];

				            }else{
				            	echo "<script>window.open('all_classes.php?mes=Error, Please Contact Support.','_self');</script>";
				            }

 	?>
 	<h3 class="text" style="font-size: 35px; float: right;"><?php echo $_SESSION["class_name"]; ?></h3>

	<!-- SUB NAVIGATION -->
	<div id="subnav">
		<ul class="">
            <li class="first_selected"><a href="student_index.php" title="All Classes"  id="active">&nbsp;&nbsp;Assignments&nbsp;&nbsp;</a></li>
            
            <li><a href="enroll.php" title="Enroll in Class" style="color:#072F5F;">&nbsp;&nbsp;Students&nbsp;&nbsp;</a></li><!--tabindex="0" for yellow highlight-->
            <li><a href="http://www.plagiarism.org" target="_blank" style="color:#072F5F;">&nbsp;&nbsp;Student Grades&nbsp;&nbsp;</a></li>
            <li class="last"><a href="#" title="Calendar" style="color:#072F5F;">More</a></li>
        </ul>
        <div class="nothing"></div>
    </div>

    <button id="back_btn" type="submit" class="btn" name="back" onclick="window.location.href='all_classes.php';">&larr; Back</button>

	</div>
	<section class="dateAndTime">
        <script>
            var myDate = new Date(); 
            var myDay = myDate.getDay(); 
            
            // Array of days. 
            var weekday = ['Sunday', 'Monday', 'Tuesday', 
                'Wednesday', 'Thursday', 'Friday', 'Saturday']; 
            document.write(weekday[myDay]); 
        </script>
        <div id="txt"></div><br>
    </section>


<!-- Assignments-->
    <div class="tbox">
					<h3 style="margin-top:30px; font-size: 37px;"> Class Assignments</h3><br>
					<?php
						if(isset($_GET["mes"]))
						{
							echo"<div class='success'>{$_GET["mes"]}</div>";	
						}
					
					?>
					<table class="table">
						<tr style="text-align: left;border-bottom: 1px solid black; background-color: #dedede;">
							<th>Assignment Name</th>
							<th>Start Date</th>
							<th>Due Date</th>
							<th>Post Date</th>
							<!--th>Status</th-->
							<th>Edit Assignment</th>
							<th style="text-align:center;">Delete Assignment</th>
						</tr>
						<?php
							#FIX PLEASEd
							$assignments="
									SELECT *
									FROM Assignment a, Assignment_Definition ad, Class_Assignment ca
									WHERE a.assignment_def_id = ad.assignment_def_id
									AND a.assignment_id = ca.assignment_id
									AND ca.class_id = '{$_SESSION["class_id"]}'
								   ";

							$res=$db->query($assignments);
							if($res->num_rows>0)
							{
								while($r=$res->fetch_assoc())
								{

									echo "
										<tr>
											<td><a class='class_link' href='assignment_redirect.php?class={$r["assignment_id"]}'>{$r["title"]}</a></td>
											<td>{$r["open_datetime"]}</td>
											<td>{$r["due_datetime"]}</td>
											<td>{$r["posted_date"]}</td>
											<td>edit(will fix)</td>
											<td style='text-align:center;font-size:25px;'><a style='color:#333; text-decoration:none;' href='delete_assignment.php?id={$r["assignment_id"]}' class='fa fa-trash'></a></td>
										</tr>
										";
								}
							}
							if($res->num_rows<=0){
								echo "
										<tr>
											<td><a style='color: #fefefe; text-decoration:none; border:#5ca08e 1px solid; border-radius:20px; padding:7px; background-color:#5ca08e;' href='assign_assignment.php'>Assign Assignment</a></td>
											<td></td>
											<td></td>
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
   
</body>
<?php include"footer.php";?>
<script type="text/javascript">
      function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginRight = "250px";
        document.getElementById("main").style.marginLeft = "-250px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        document.getElementById("toggle").style.display = "none";
        document.getElementById("closebtn").style.display = "block";
      }

      function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginRight= "0";
        document.getElementById("main").style.marginLeft = "0";
        document.body.style.backgroundColor = "white";
        document.getElementById("toggle").style.display = "block";
        document.getElementById("closebtn").style.display = "none";
      }

	   function startTime() {
              var today = new Date();
              var h = today.getHours();
              var m = today.getMinutes();
              var s = today.getSeconds();
              m = checkTime(m);
              s = checkTime(s);
              document.getElementById('txt').innerHTML =
              h + ":" + m + ":" + s;
              var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }

  </script>
</html>

