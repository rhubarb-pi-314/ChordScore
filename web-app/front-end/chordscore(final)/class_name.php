<div class="class_name" onload="startTime()" style="margin-top: 50px;">
	<div class="datestamp"><?php echo date("D, M d, Y");?></div>
	<div id="txt"></div>
	<h3 class="text" style="font-size: 30px;"><?php echo $_SESSION["class_name"]; ?></h3>
</div>

<script type="text/javascript">
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
</script>