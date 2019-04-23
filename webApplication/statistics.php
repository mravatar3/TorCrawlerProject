<?php 
	include("header.php");
?>
<br>

<div class="container">


	<?php	$commando = "pgrep -f 'python36'";
	$output = shell_exec($commando);

	if(empty($output)){?>
		<div class="alert alert-success" style="    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;">Er is nog geen crawler actief!</div>
	<?php
	}
	else { ?>
		<div class="alert alert" style="    color: #191919;
    background-color: #ffc645;
    border-color: #ffc645;">Er is al een crawler actief!</div>
	<?php }
	?>


	<br>
	<h3>Statistics Crawler</h3>
	<br />
	<p><?php
	$qresult = mysqli_query($link, "SELECT count(*) as total FROM queue");
	$aantalWachtrij = mysqli_fetch_array($qresult);
	echo("Aantal links in de wachtrij: ".$aantalWachtrij['total']."<br />"); 
	
	$qresult = mysqli_query($link, "SELECT count(*) as total FROM crawled");
	$aantalCrawled = mysqli_fetch_array($qresult);
	echo("Aantal pagina's gecrawled: ".$aantalCrawled['total'].""); 
	?></p>
	
	<a class="pure-button" style="background: rgb(223, 117, 20); color: white; border-radius: 4px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);" href="statistics.php?verwijderwachtrij">Maak wachtrij leeg</a>
	<a class="pure-button" style="background: rgb(202, 60, 60); color: white; border-radius: 4px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);" href="statistics.php?verwijdercrawled">Maak crawled leeg</a><br /><br />
		<?php
		if (isset($_GET["verwijderwachtrij"])) { 
			$sql = "TRUNCATE TABLE queue";
			mysqli_query($link, $sql);
			print 'Wachtrij leeggemaakt, refresh in 3 seconden';
			header("Refresh:3");
		}
		if (isset($_GET["verwijdercrawled"])) { 
			$sql = "TRUNCATE TABLE crawled";
			mysqli_query($link, $sql);
			print 'Crawled leeggemaakt, refresh in 3 seconden';
			header("Refresh:3");
		}
	?>
	
	<h3>Links in de wachtrij</h3>
	
	<?php
		$results_per_page = 20;
		if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
		$start_from = ($page-1) * $results_per_page;
		$sql = "SELECT * FROM queue ORDER BY idqueue DESC LIMIT $start_from, ".$results_per_page;
		$rs_result = $link->query($sql);
	?> 
	<table class="pure-table" border="1" cellpadding="4">
	<tr>
	<thead>
		<td bgcolor="#CCCCCC"><strong>ID</strong></td>
		<td bgcolor="#CCCCCC"><strong>URL</strong></td></tr>
	</thead>
	<?php 
		while($row = $rs_result->fetch_assoc()) {
	?> 
            <tr>
            <td><?php echo $row["idqueue"]; ?></td>
            <td><?php echo $row["link"]; ?></td>
            </tr>
	<?php 
	}; 
	?> 
	
	
	<?php 
		$sql = "SELECT COUNT(*) AS total FROM queue";
		$result = $link->query($sql);
		$row = $result->fetch_assoc();
		$total_pages = ceil($row["total"] / $results_per_page);
  
		for ($i=1; $i<=$total_pages; $i++) { 
			echo "<a style='margin-left: 2px' href='statistics.php?page=".$i."'";
            if ($i==$page)  echo " class='curPage'";
            echo ">".$i."</a> "; 
		}; 
	?>
</table>
	
	
</div>

</body>
</html>
