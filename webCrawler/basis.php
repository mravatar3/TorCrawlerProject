<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>
  <script>
	
	.container {
		position: relative;
	}
	
  </script>
  <script type="text/javascript">
	function change( el ){
		if ( el.value === "TOR Enabled" )
			el.value = "TOR Disabled";
		else
			el.value = "TOR Enabled";
	}
  </script>
 
  </head>
   
<body>
  
<?php
// define variable and set to empty values
$website = $output = $tor = $runlinux = "";

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
	<!-- <button type="button" onclick="/path/to/name.sh" class="btn btn-success btn-sm">TOR Enabled</button> -->
	<input  type="button" id="myButton" value="TOR Enabled" onclick="return change(this);" />
	<br><br>
	<h3>Dark Web Crawler - Team 4</h3>
  
	<p>Choose a union website and a keyword:</p>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<table >
			<col width="80">
			<col width="80">
			<tr>
				<td>Search ID:</td>
				<td><input type="url" name="website" id="url" placeholder="http://example.union" pattern="http://.*.union" title="Include http://example.union" size="30" required> </td>
    		</tr>
			<tr>
				<td>Keyword:</td> 
				<td><input type="text" name="keyword" placeholder="any keyword" size="30" required></td> 
    		</tr>
			<tr>
				<td><input type="submit" name="submit" value="Search"></td>
    		</tr>
		</table>

<?php
if (filter_var($website, FILTER_VALIDATE_URL)) {
	print "<br>Start website: ";
	print htmlspecialchars($_POST['website']);
	print "<br>Search keyword: ";
	print htmlspecialchars($_POST['keyword']);
	
	echo "<h3>Crawled websites:</h2>";
	
	$command = escapeshellcmd("/crawler/python3 main.py");
	$output = shell_exec($command);
	echo $output;	
	
	print htmlspecialchars($_POST['website']);
	echo("<br>");
	$fh = fopen('/var/www/html/crawler/startpagina/crawled.txt','r');
	while ($line = fgets($fh)) {
	echo($line);
	echo("<br>");
	}
	fclose($fh);
	echo("<br>");
	
	$runlinux = shell_exec('ls');
	echo "<pre>$runlinux</pre>";	
	
}
?>
</div>

</body>
</html>
