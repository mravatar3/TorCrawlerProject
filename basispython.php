<!DOCTYPE html>
<html>
  <head>
  
 
  </head>
<body>
<br><br>

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

Enable TOR <input type="checkbox" name="tor" value="checked">
<?php //$checked = ($_POST['tor'] == ' checked'); ?>
<h2>Dark Web Crawler - Team 4 - Python</h2>
<br><br>
Choose a union website and a keyword:
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
Search ID: <input type="url" name="website" id="url" placeholder="http://example.union" pattern="http://.*.union" title="Include http://example.union" size="30" required> 
<br><br>
Keyword: <input type="text" name="keyword" placeholder="any keyword" size="31" required>
<br><br>
<input type="submit" name="submit" value="Search">
</form>

<form name="search" action="/var/www/cgi-bin/main.py" method="get">
Search: <input type="text" name="searchbox">
<input type="submit" value="Submit">
</form> 

<?php
if (filter_var($website, FILTER_VALIDATE_URL)) {
	echo "<h3>Crawled websites:</h3>";
	 
	$command = escapeshellcmd('python3 /var/www/html/crawler/main.py');
	$output = shell_exec($command);
	//$output = exec('python3 /var/www/html/crawler/main.py');
	echo $output;
	
	//echo("<br>");
	//$fh = fopen('/var/www/html/crawler/startpagina/crawled.txt','r');
	//while ($line = fgets($fh)) {
	//echo($line);
	//echo("<br>");
	//}
	//fclose($fh);
	//echo("<br>");
	
	$runlinux = shell_exec('ls');
	echo "<pre>$runlinux</pre>";
	
}

?>


</body>
</html>
