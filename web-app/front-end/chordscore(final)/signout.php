<?php
	include "database.php";
	session_start();
	
	unset ($_SESSION["account_id"]);
	unset ($_SESSION["account_type"]);
	unset ($_SESSION["email"]);
	unset ($_SESSION["first_name"]);
	unset ($_SESSION["last_name"]);
	unset ($_SESSION["DOB"]);
	unset ($_SESSION["pwd"]);
	
	session_destroy();
	echo "<script>window.open('index.php?mes=Sign Out Successful','_self');</script>";

?>