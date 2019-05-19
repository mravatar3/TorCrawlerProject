<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>
  <script src="https://code.jquery.com/jquery-git.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css"/>
  <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>

  <style>
	
	.container {
		position: relative;
	}

	
	
  </style>
  

  </head>
   
<body>
  
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

	<?php
	exec("ss -aln | grep 9050", $output, $return);
		if ($return == 0) {
			echo '<div class="alert alert-success">TOR is Running Safely</div>';
		} else {
			echo '<div class="alert alert-danger">TOR is Down</div>';
		}
	?>

	<br>
	<h3>Dark Web Crawler - Team 4</h3>
  
	<form method="post">
		<table >
			<col width="80">
			<col width="80">
			<tr>
				<td>Website:</td>
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
	</form>

<?php
	if(isset($_POST['keyword'])){
		
		$data1=$_POST['website'];
		$data2=$_POST['keyword'];
		$fp = fopen("var/www/html/crawler/startpagina/crawldata.txt", "w");
		fwrite($fp, $data1);
		fwrite($fp, PHP_EOL); // an enter in line
		fwrite($fp, $data2);
		fclose($fp);
		
		echo "<br>Start website: ";
		echo($data1);
		echo "<br>Search keyword: ";
		echo($data2);
		echo "<br>crawldata.txt readout: ";
		
		// reading crawldata.txt
		$file = fopen("/var/www/html/crawler/startpagina/crawldata.txt", "r");
		//Output lines until EOF is reached
		while(! feof($file)) {
		$line = fgets($file);
		echo $line. "<br>";
		}
		fclose($file);
		
		echo "<h3>Crawled websites:</h3>\n";
		
		// run main.py
		$command = escapeshellcmd("var/www/html/crawler/python3 main.py");
	    $output = shell_exec($command);
	    //echo $output;	
		
		// reading crawled.txt - from the directory output saved
		$url = str_replace('http://', 'http:/', $data1 ); 
		$dir = "/var/www/html/crawler/".$url;
		echo $dir;
		$crawled = fopen($dir."/crawled.txt","r");
		//$crawled = fopen("/var/www/html/crawler/http:/www.hva.nl/crawled.txt","r");
		while(! feof($crawled)) {
			$line = fgets($crawled);
			echo $line. "<br>";
		}
		fclose($crawled);
		

	
		
		
}
	
	
		
	
?>
  
  
  
  

</div>

</body>
</html>
