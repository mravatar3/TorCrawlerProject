<?php 
	include("header.php");
?>
<?php
// define variable and set to empty values
$website = $tor = $runlinux = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$website = test_input($_POST["website"]);
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = ($data);
return $data;
}
?>
<br>

<div class="container">
	<br>
	<h3>Statistics Crawler</h3>
	<br />
</div>

</body>
</html>
