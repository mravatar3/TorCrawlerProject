<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>
  <script src="https://code.jquery.com/jquery-git.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css"/>
  <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
	* {box-sizing: border-box;}

	body {
		margin: 0;
		font-family: Arial, Helvetica, sans-serif;
	}

	.topnav {
		overflow: hidden;
		background-color: #e9e9e9;
	}

	.topnav a {
		float: left;
		display: block;
		color: black;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
		font-size: 17px;
	}

	.topnav a:hover {
		background-color: #ddd;
		color: black;
	}

	.topnav a.active {
		background-color: #2196F3;
		color: white;
	}

	.topnav .search-container {
		float: right;
	}

	.topnav input[type=text] {
		padding: 6px;
		margin-top: 8px;
		font-size: 17px;
		border: none;
	}

	.topnav .search-container button {
		float: right;
		padding: 6px;
		margin-top: 8px;
		margin-right: 16px;
		background: #ddd;
		font-size: 17px;
		border: none;
		cursor: pointer;
	}

	.topnav .search-container button:hover {
		background: #ccc;
	}

	@media screen and (max-width: 600px) {
		.topnav .search-container {
			float: none;
		}
		
		.topnav a, .topnav input[type=text], .topnav .search-container button {
			float: none;
			display: block;
			text-align: left;
			width: 100%;
			margin: 0;
			padding: 14px;
		}
		
		.topnav input[type=text] {
			border: 1px solid #ccc;  
		}
	}
	
	.container {
		position: relative;
	}

	
	
  </style>

  </head>
   
<body>
  
<div class="topnav">
  <a class="active" href="#home">Crawler</a>
  <div class="search-container">
    <form action="/basis.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit">Submit</button>
    </form>
  </div>
</div>
  
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
