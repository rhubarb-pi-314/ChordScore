

<button onclick="myFunction()" class="sidebar_dropbtn" style="color:black; font-size: 30px; background-color: transparent;">&#9776;</button>

<div class="sidebar" id="sidebar"><br>
	<a href="account_settings.php"><div class="profile" style="margin-top: 15px;">
		<img style="" src="img/avatar.png" alt="Avatar" class="avatar">
		<span style="display: inline-block;"><h4 style="text-transform:uppercase;float: left; color: #5ca08e;"><?php echo $_SESSION["first_name"];?>&nbsp;<?php echo $_SESSION["last_name"];?></h4><br>
		<p style="text-transform:lowercase;font-size: 17px;float: left; margin-top: -25px; "><?php echo $_SESSION["email"]; ?></p></span>
	</div></a>
	
	<ul style="list-style:none;">
	<?php
		if(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='instructor')
		{
			echo'
				<li class="li"><a href="instructor_home.php">Announcements</a></li>
				<li class="li"><a href="all_classes.php">All Classes</a></li>
				<li class="li"><a href="all_students.php">All Students</a></li>
				<li class="li"><a href="all_assignments.php">Assignments</a></li>
				<li class="li"><a href="new_assignment.php">Create New Assignment</a></li>
				<li class="li"><a href="#">Post Announcement</a></li>
				<li class="li"><a href="#">Messages</a></li>
				<li class="li"><a href="signin_history.php">Signin History</a></li>
				<li class="li"><a href="signout.php">Signout</a></li>
			';
		
		
		}
		else{
			echo'
				<li class="li"><a href="student_home.php">Announcements</a></li>
				<li class="li"><a href="student_assignments.php">Assignments</a></li>
				<li class="li"><a href="grades.php"> Grades</a></li>
				<li class="li"><a href="view_stud_teach.php"> Submit Assignment</a></li>
				<li class="li"><a href="tech_view_exam.php">Resources</a></li>
				<li class="li"><a href="messages.php">Messages</a></li>
				<li class="li"><a href="about_class.php">About This Class</a></li>
				<li class="li"><a href="signout.php">Signout</a></li>
			';
		}


	?>
	</ul>
</div>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("sidebar").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.sidebar_dropbtn')) {
    var dropdowns = document.getElementsByClassName("sidebar");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
