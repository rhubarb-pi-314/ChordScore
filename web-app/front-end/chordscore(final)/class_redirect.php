<?php
	include"database.php";
	session_start();
	
	if(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='instructor'){
		
		$a=1; //fix 


	}elseif(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='student'){
		$_SESSION["class_name"] = $_GET["class"];
		$class="
				SELECT c.class_name, a.first_name, a.last_name, c.start_date, c.end_date, c.class_id
				FROM Class c, Instructor_Key ik, Account a, Class_List cl
				WHERE c.account_id = ik.account_id
				AND ik.account_id = a.account_id
				AND cl.class_id = c.class_id
				AND cl.account_id = '{$_SESSION["account_id"]}'
				AND c.class_name = '{$_SESSION["class_name"]}'

									   ";

				$result=$db->query($class);
				if($result->num_rows>0){
					
					$ro=$result->fetch_assoc();

					$_SESSION["inst_first_name"]=$ro["first_name"];
					$_SESSION["inst_last_name"]=$ro["last_name"];
					$_SESSION["course_start_date"]=$ro["start_date"];
					$_SESSION["course_end_date"]=$ro["end_date"];
					$_SESSION["class_id"]=$ro["class_id"];

					echo "<script>window.open('student_home.php','_self');</script>";
				}
	}else{
		echo"<div class='error'>Something went wrong. Try again later</div>";
	}

?>