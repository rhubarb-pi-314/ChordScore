<?php

	include "database.php";
	session_start();

						//sessions
						$sql="SELECT * from Account where account_id='{$_SESSION["account_id"]}' and pwd='{$_SESSION["account_pwd"]}'";
						$r=$db->query($sql);
			            if($r->num_rows>0)
			            {
			              $ro=$r->fetch_assoc();
			              $_SESSION["account_id"]=$ro["account_id"];
			              //$_SESSION["account_type"]=$ro["account_type"];  //section made in previous page
			              $_SESSION["email"]=$ro["email"];
			              $_SESSION["first_name"]=$ro["first_name"];
			              $_SESSION["last_name"]=$ro["last_name"];
			              $_SESSION["DOB"]=$ro["DOB"];
			              $_SESSION["pwd"]=$ro["pwd"];

			              //unset input data from previous page
			              unset ($_SESSION["account_email"]);
						  unset ($_SESSION["account_pwd"]);

			            }

			            //direct to account
						$res=$db->query($sql);
			            if($res){
			                
			                if ($_SESSION["account_type"] == "student"){
			                  echo "<script>window.open('student_index.php','_self');</script>";
			                }
			                elseif($_SESSION["account_type"] == "instructor"){
			                  echo "<script>window.open('instructor_home.php','_self');</script>";
			                }
			              	else{
			              	  echo"<script>window.open('index.php?mes=Don't be funny, Try again...','_self');</script>";
			              	}

			            }else{
			                echo "<div class='error'>Error, Please try again.</div>";
			            }		
?>