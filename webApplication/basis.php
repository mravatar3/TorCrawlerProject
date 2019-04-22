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
	
	<p>Hallo, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br /> Welkom op het Tor Project dashboard.<br /> <br /></p>
  
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
				<th colspan="2"><br>Only required for a login page on the darkweb</th>
			</tr>
			<tr>
				<td><label for="username">Username:</label></td> 
				<td><input type="username" id="username" placeholder="optional username" size="30" name="username"></td> 
			</tr>
			<tr>
				<td><label for="pwd">Password:</label></td>
				<td><input type="password" id="pwd" placeholder="optional password" size="30" name="pswd"></td> 
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
		//$command = "/var/www/html/crawler/python3 main.py";
	    $output = shell_exec("/var/www/html/crawler/python3 main.py");
	    //echo $output;	
		
		// reading crawled.txt - from the directory output saved
		//$url = str_replace('http://', 'http:/', $data1 ); 
		//$dir = "/var/www/html/crawler/".$url;
		//echo $dir;
		//$crawled = fopen($dir."/crawled.txt","r");
		$crawled = fopen("/var/www/html/crawler/http:/www.hva.nl/crawled.txt","r");
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
