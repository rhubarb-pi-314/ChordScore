<?php
	include"database.php";
	session_start();
	
	$drop="DELETE FROM Class_List 
		WHERE class_id='{$_GET["id"]}'
		AND account_id='{$_SESSION["account_id"]}'
	";
	$db->query($drop);
	echo "<script>window.open('student_index.php?mes=Class has been dropped.','_self');</script>"
?>