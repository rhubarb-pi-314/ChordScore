<?php
	include"database.php";
	session_start();
	
	$s="DELETE from Class where class_id='{$_GET["id"]}'";
	$db->query($s);
	echo "<script>window.open('all_classes.php?mes=Class has been deleted.','_self');</script>"
?>