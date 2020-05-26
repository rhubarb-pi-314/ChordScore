<div class="navbar">
	
			<ul class="list">

			<?php

				if(isset($_SESSION["account_id"]) && $_SESSION["account_type"] =='instructor')
				{
					echo'

						<a href="instructor_home.php"><img style="float:left; height: 50px; margin-left:-25px;" src="img/logo.png"></a>
				
						<li><a href="instructor_home.php">Home</a></li>
						<li><a href="instructor_key.php">My Key</a></li>
						<li><a href="account_settings.php">Account Settings</a></li>
						<li><a href="signout.php">Signout</a></li>
					';
				}
				elseif(isset($_SESSION["account_id"]) && $_SESSION["account_type"] =='student')
				{
					echo'

						<a href="student_index.php"><img style="float:left; height: 50px; margin-left:-25px;" src="img/logo.png"></a>
				
						<li><a href="student_index.php">Home</a></li>
						<li><a href="account_settings.php">Account Settings</a></li>
						<li><a href="signout.php">Signout</a></li>
					';
				}
				else{
					echo'
				<li><a href="contact.php">Contact Us</a></li>';
				}
			?>
				
			</ul>
</div>
		