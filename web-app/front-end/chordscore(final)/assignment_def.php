<?php
	include"database.php";
	session_start();
	if(!isset($_SESSION["account_id"]))
	{
		echo"<script>window.open('index.php?mes=Access Denied...','_self');</script>";
		
	}		
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Assignments</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
		<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/assignment_def.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			
			<div id="section">


				<br><?php include"sidebar.php";?><br>
				<div class="content" style="margin-top: 25px;">
						<button id="back_btn" type="submit" class="btn" name="back" onclick="window.history.back()" style="margin-left: 70px; margin-top: 10px;">&larr; Back</button>

						<div class="def_btns" style="right: 0; margin-right: 20px; margin-top: 50px; position: fixed;">
							<!--button class="btn" id="btn" onclick="setColor(event)" style="width: 150px; background-color: grey;">Notation</button><br>
							<button class="btn" id="btn" onclick="setColor(event)" style="width: 150px; background-color: grey;">Text/Written</button-->
							<input type="button" class="btn" id="button" value="Notation" style="width: 150px;background-color: grey;" onclick="setColor(event, 'button', '#5ca08e'); myFunction()" /><br>
							<input type="button" class="btn" id="button" value="Text/Written" style="width: 150px;background-color: grey;" onclick="setColor(event, 'button', '#5ca08e'); myFunction2()" />
						</div>
						
						<!-- enroll box-->
					    <div class="cabox" style="width: 75%; margin-top: 20px;">
					    				

					    			<?php

					    				if(isset($_FILES['file'])){
									      $errors= array();
									      $file_name = $_FILES['file']['name'];
									      $_SESSION["ak_file_name"] = $file_name;
									      $file_size =$_FILES['file']['size'];
									      $file_tmp =$_FILES['file']['tmp_name'];




									      //$file_type=$_FILES['file']['type'];
									      /*$file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));
									      
									      $extensions = array("jpeg","jpg","png");
									      
									      if(in_array($file_ext,$extensions)=== false){
									         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
									      }*/
									      
									      if($file_size > 2097152){
									         $errors[]='File size must be excately 2 MB';
									      }
									      
									      if(empty($errors)==true){
									         move_uploaded_file($file_tmp,"answerkey_images/".$file_name);
									         //copy('foo/test.php', 'bar/test.php');
									         //echo "Success";
									      }else{
									         print_r($errors);
									      }
									   }
										
										function unique_generator($length){
										      $characters = '0123456789ABCDEFGHIJ.#%*@!';
										      $string = '';

										      for ($i = 0; $i < $length; $i++) {
										          $string .= $characters[mt_rand(0, strlen($characters) - 1)];
										      }
										      
										      return $string;
									    }




										if(isset($_POST["create"])){

											//account id
											$ass_def_id = unique_generator(10);
											$acc_id = $_SESSION["account_id"];
											$title = $_SESSION["title"];
											$instructions = $_POST["instructions"];
											$file_name = $_SESSION["ak_file_name"];


											$sq="INSERT INTO Assignment_Definition(assignment_def_id,account_id,title,instructions, ak_file_name) 
												VALUES('$ass_def_id','$acc_id','$title','$instructions', '$file_name')";


											if($db->query($sq)){

													//Answer_Key table
													$ak_id = unique_generator(10);
													$path = 'answerkey_images/'.$file_name;

													
													$s="INSERT INTO Answer_Key(answer_key_id, assignment_def_id,answer_key_file_path)
														   VALUES('$ak_id','$ass_def_id', '$path')";
													$db->query($s);

													echo "<script>window.open('all_assignments.php?mes=Assignment Has Been Created','_self');</script>";
											}else{
													echo "<div class='error'style='width:99%;'>Assignment Creation Failed..</div><br>";
											}	
												
										}
									    if(isset($_GET["mes"])){
											echo "<div class='error'>{$_GET["mes"]}<br></div>";
										}
									?>

					    				<h3 style="margin-top:30px; font-size: 37px; text-align: center; "><?php echo $_SESSION["title"]; ?></h3>

										<form id="myform" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
						    

										    <br><label style="margin-left: 10%">Answer Key</label><br>
										    <input type="file" name="file" id="ass_file" onchange="readURL(this);" style="
										    margin-left: 10%">

										    <img style="margin-left: 19%;" id="blah" src="#" alt="" /><br><br>
										    
										    <label style="margin-left: 10%">Instructions</label><br>
							    			<textarea style="width: 77%; margin-left: 10%" rows="4" cols="50" name="instructions" id="instructions"></textarea><br>
										    
										    <button type="submit" class="btn" name="create" style="width: 160px; margin-left: 85%">Create</button>

										    <div id="div" hidden style="overflow: scroll;"><var id="myVar"> </var></div>
											<div id="div2" hidden style="overflow: scroll;"></div>
									 
										</form>


						</div>

				
					<br><br>
						<p style="border-top: #5ca08e 3px solid;float:left;width:100%;"></p>
					<br><br>
				</div>
				
			</div>
	
		<?php include"footer.php";?>
	<script src="js/jquery.js"></script>
	<script>
			$(document).ready(function(){
				$(".error").fadeTo(1500, 200).slideUp(1500, function(){
						$(".error").slideUp(1000);
				});
				
				$(".success").fadeTo(1000, 100).slideUp(1000, function(){
						$(".success").slideUp(1000);
				});
			});

			jQuery(window).load(function() {
			    jQuery("body").addClass('all-loaded');
			});

			function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(813)
                        .height(1050);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }


	    var div = document.getElementById('div'), x1 = 0, y1 = 0, x2 = 0, y2 = 0;
		var div2 = document.getElementById('div2');
		function reCalc() {
		    window.x3 = Math.min(x1,x2);
		    window.x4 = Math.max(x1,x2);
		    window.y3 = Math.min(y1,y2);
		    window.y4 = Math.max(y1,y2);
		    div.style.left = x3 + 'px';
		    div.style.top = y3 + 'px';
		    div.style.width = x4 - x3 + 'px';
		    div.style.height = y4 - y3 + 'px';
		}
		onmousedown = function(e) {
		    div.hidden = 0;
		    x1 = e.clientX;
		    y1 = e.clientY;
		    reCalc();
		};


		onmousemove = function(e) {
		    x2 = e.clientX;
		    y2 = e.clientY;
		    reCalc();
		};
		onmouseup = function(e) {
		    div.hidden = 1;
			div2.hidden = 0;
		    
		    div2.style.left = x3 + 'px';
		    div2.style.top = y3 + 'px';
		    div2.style.width = x2 - x1 + 'px';
		    div2.style.height = y2 - y1 + 'px';
		};

		//change this. Only for purpose of turning in video - Monday

		function setColor(e, btn, color) {
		  var target = e.target,
		      count = +target.dataset.count;
		  
		   target.style.backgroundColor = count === 1 ? "grey" : '#5ca08e';
		   target.dataset.count = count === 1 ? 0 : 1;
		}

		function myFunction() {
		  var x = document.getElementById("myVar");
		  x.innerHTML = "Select Notation";
		}
		function myFunction2() {
		  var x = document.getElementById("myVar");
		  x.innerHTML = "Select Write/Text";
		}

	</script>
	</body>
</html>