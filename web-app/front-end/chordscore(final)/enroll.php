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
    <title>Student | Enroll</title>
    <link rel="shortcut icon" href="../branding/logo2.png" type="image/x-icon">
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
		<span style="display: inline-block; margin-left: 50px; margin-bottom: 30px;"><h4 style="text-transform:uppercase;float: left; color: #5ca08e;">Tanaka&nbsp; Mangadze</h4><br>
		<p style="text-transform:lowercase;font-size: 17px;float: left; color: white; ">student2@cs.com</p></span><hr>
	</div>
      <a href="student_index.php">Home</a>
      <a href="#">More</a>
      <a href="http://www.plagiarism.org" target="_blank">Plagiarism</a>
      <a href="account_settings.php">Account Settings</a>
      <a href="signout.php">Logout</a>
    </div>

    <div style="width: 50px; height: 1px; float: right">
      <h1 id="toggle" style="font-size:30px;cursor:pointer;" onclick="openNav(); ">&#9776;</h1>
    </div>
</header>

<!-- PAGE CONTENT -->
<body id="main" onload="myFunction(); startTime()">
 	
 	<p id="greeting" style="display: inline-block; float: right; margin-top: 10px; margin-right: 25px; font-size: 25px;"></p>

 	

	<!-- SUB NAVIGATION -->
	<div id="subnav">
		<ul class="">
            <li class="first_selected"><a href="student_index.php" title="All Classes" style="color:#072F5F;">&nbsp;&nbsp;All Classes&nbsp;&nbsp;</a></li>
            
            <li><a href="enroll.php" title="Enroll in Class" id="active">&nbsp;&nbsp;Enroll in Class&nbsp;&nbsp;</a></li><!--tabindex="0" for yellow highlight-->
            <li><a href="http://www.plagiarism.org" target="_blank" style="color:#072F5F;">&nbsp;&nbsp;About Plagiarism&nbsp;&nbsp;</a></li>
            <li class="last"><a href="#" title="Calendar" style="color:#072F5F;">More</a></li>
        </ul>
        <div class="nothing"></div>
    </div>

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
        <div id="txt"></div>
    </section>


<!-- enroll box-->
    <div class="tbox" style="width: 55%; margin-top: auto; margin-bottom: auto;">
					<h3 style="margin-top:30px; font-size: 37px; text-align: center; "> Enroll in a class</h3><br>
					<?php
						if(isset($_POST["enroll"]))
						{
							$sql="SELECT * from Class where class_key='{$_POST["key"]}'";
							$res=$db->query($sql);
							if($res->num_rows>0)
							{
								$ro=$res->fetch_assoc();

								$insert="
									INSERT into Class_List(account_id, class_id)
									VALUES('{$_SESSION["account_id"]}','{$ro["class_id"]}')
								   ";
								$db->query($insert);
								echo "<script>window.open('student_index.php?mes=Enrollment Successful','_self');</script>";
							}
							else
							{
								echo "<div class='error'>Invalid Class Key</div>";
							}
							
						}
						
						if(isset($_GET["mes"]))
						{
							echo "<div class='success'>{$_GET["mes"]}</div>";
						}
						
					?>
					<hr>
					<form style="text-align: center; margin-top: 50px; margin-bottom: 50;" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					  <div class="form_container">
					    

					    <label for="key" style="font-size: 20px; margin-right: 30px;"><b>Class Key</b></label>
					    <input type="text" maxlength="15" name="key" placeholder="12345-67891-01123" required class="input" id="key"><br>

					    <button type="submit" class="enroll_btn" name="enroll" id="btn">Enroll</button>
					  </div>
					</form>
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

      function myFunction() {
		  var greeting;
		  var time = new Date().getHours();
		  if (time < 10) {
		    greeting = "Good morning <?php echo $_SESSION["first_name"];?>.";
		  } else if (time < 20) {
		    greeting = "Good day <?php echo $_SESSION["first_name"];?>.";
		  } else {
		    greeting = "Good evening <?php echo $_SESSION["first_name"];?>.";
		  }
		  document.getElementById("greeting").innerHTML = greeting;
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

        function checkPass() {
        		key = document.getElementById('key'),
				button = document.getElementById('btn'),
				colors = {
					goodBtnColor: "#1C4966",
					badBtnColor: "#adadad"
				}

				if(key.maxlength == "15") {
					btn.style.backgroundColor = colors["goodBtnColor"];
					$('.enroll_btn').prop('disabled', false); //to enable button

				}
				else if(key.maxlength < "15") {
					btn.style.backgroundColor = colors["badBtnColor"];
					$('.enroll_btn').prop('disabled', true); //to disable button
				}

			}

  </script>
</html>

