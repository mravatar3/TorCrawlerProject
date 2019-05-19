<!DOCTYPE html>
<html>

<?php
	include("header.php");
?>
<head>
<script src="sorttable.js"></script>
<style>

table {
	border-spacing: 0;
	width: 100%;
	border: 1px solid #ddd;
}

th {
	cursor: pointer;
}

th, td {
	text-align: left;
	padding: 16px;
}

tr:nth-child(even) {
	background-color: #f2f2f2
}

</style>
</head>

<body>
 <div class="container">
  <br>
    <!--<div class="search-container">
		<form name="form1" method="post" action="searchresults.php" >
			<input type="text" placeholder="Threats zoeken..." name="search">
			<button type="submit">Zoeken</button>
		</form>
	</div>-->
		<form name="DateFilter" method="POST">
			From:
				<input type="date" name="dateFrom" value="<?php echo ['dateFrom'] ?>"/>
			To: 
				<input type="date" name="dateTo" value="<?php echo ['dateTo'] ?>"/>
				<input type="submit" name="submit" value="Filter"/>
		</form>

<?php
    if (isset($_POST['dateFrom'])) {
		// https://stackoverflow.com/questions/49028283/how-to-filter-records-by-date-with-php
		
		$query = mysqli_query($link, "SELECT * FROM crawled");
		
		//Getting value from filter
		$new_date = date('Y-m-d', strtotime($_POST['dateFrom']));
		//echo $new_date;

		$new_date2 = date('Y-m-d', strtotime($_POST['dateTo']));
		//echo $new_date2;

		$dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
		$dateTo = date('Y-m-d', strtotime($_POST['dateTo']));

		while($row=mysqli_fetch_array($query)){

		//Filtering using dates
		if (date('Y-m-d', strtotime($row['due_date']))>=$dateFrom && date('Y-m-d', strtotime($row['due_date'])) <= $dateTo){
 ?>
			<br>
			<?php echo "Aantal zoekresultaten: "; 
			//printf(mysqli_num_rows($result));
			?>
			<table class="sortable" border="1" cellpadding="4" id="myTable">
				<tr>
					<thead>
						<tr>
							<!--When a header is clicked, run the sortTable function -->  
							<th bgcolor="#CCCCCC" onclick="sortTable(1)"><strong>URL</strong></th>
							<th bgcolor="#CCCCCC" onclick="sortTable(1)"><strong>Date</strong></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo  $row['link'] ?></td>
							<td><?php $timestamp1 = $row["due_date"]; echo date("d-m-Y", strtotime($timestamp1)); ?></td>
						</tr>
					</tbody>
    
	<?php }
		} 
		}
?>
			</table>
			
<?php
  if(isset($_POST['search'])) {
    $search = $_POST['search'];
	//$search = preg_replace("#[^0-9a-z]i#","", $search);
	// aan werken no single spaces allowed
	$search = preg_replace("#[^0-9a-z]#i", "", $search);
	//echo $search; 
	// sql injection ' and 1=1--
	$query = mysqli_query($link, "SELECT * FROM crawled WHERE content LIKE '%".$search."%' "); 
	
	/* Select queries return a resultset */
	if ($result = $query) {
		echo "<br>";
		echo "Aantal zoekresultaten: ";
		printf(mysqli_num_rows($result));
		
		$count = mysqli_num_rows($result);
    	if($count == 0){
			echo "<br>";
			echo"Geen zoekresultaten gevonden!";
    	} 
	}
		  
	$results_per_page = 20;
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
		$start_from = ($page-1) * $results_per_page;
		$sql = "SELECT * FROM crawled WHERE content LIKE '%".$search."%' "; 
		//$sql = "SELECT * FROM queue ORDER BY idqueue DESC LIMIT $start_from, ".$results_per_page;
		$rs_result = $link->query($sql);
	?> 
	<table class="sortable" border="1" cellpadding="4" id="myTable">
	<tr>
	<thead>
		<tr>
			<!--When a header is clicked, run the sortTable function, with a parameter, 0 for sorting by names, 1 for sorting by country:-->  
			<th bgcolor="#CCCCCC" onclick="sortTable(0)"><strong>Name</strong></th>
			<th bgcolor="#CCCCCC" onclick="sortTable(1)"><strong>URL</strong></th>
			<th bgcolor="#CCCCCC" onclick="sortTable(1)"><strong>Threat</strong></th>
			<th bgcolor="#CCCCCC" onclick="sortTable(1)"><strong>Date</strong></th>
		</tr>
	</thead>
	<?php 
		while($row = $rs_result->fetch_assoc()) {
	?> 
		<tbody>
			<tr>
			    <td><?php echo $str = preg_replace('#^https?://#', '', $row["link"]); ?></td>
				<td><?php echo $row["link"]; ?></td>
				<td><?php echo $search; ?></td>
				<!--<td><?php //echo $row["due_date"]; ?></td>-->
				<td><?php $timestamp = $row["due_date"]; echo date("d-m-Y", strtotime($timestamp)); ?></td>
			</tr>
          </tbody>
	<?php 
	}
		/* free result set */
		mysqli_free_result($result);
	}; 
	?> 

</table>
</div> 
</body>
</html>