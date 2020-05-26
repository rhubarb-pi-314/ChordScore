<?php
	session_start();  
	if(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='instructor')
	{
		echo "<script>window.open('instructor_home.php','_self');</script>";
	}elseif(isset($_SESSION["account_id"]) && $_SESSION["account_type"]=='student')
	{
		echo "<script>window.open('student_index.php','_self');</script>";
	}else
	{
		echo"<script>window.open('index.php?mes=Access Denied..','_self');</script>";
	}
?>