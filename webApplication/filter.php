<!DOCTYPE html>
<html>
<head>
<script src="sorttable.js"></script>
<title>Sort a HTML Table Alphabetically</title>
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

<!-- https://www.kryogenix.org/code/browser/sorttable/ -->
<!-- https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_sort_table_desc -->


<table id="myTable">
  <tr>
   <!--When a header is clicked, run the sortTable function, with a parameter, 0 for sorting by names, 1 for sorting by country:-->  
    <th onclick="sortTable(0)">Name</th>
    <th onclick="sortTable(1)">Date</th>
    <th onclick="sortTable(1)">URL</th>
    <th onclick="sortTable(1)">Threat</th>
  </tr>
  <tr>
    <td>Berglunds snabbkop</td>
    <td>15 mei 2019</td>
    <td>http://apple.union</td>
    <td>Underground</td>
  </tr>
  <tr>
    <td>North/South</td>
    <td>15 mei 2019</td>
    <td>http://sonos.union</td>
    <td>Darkweb</td>
  </tr>
  <tr>
    <td>Alfreds Futterkiste</td>
    <td>25 mei 2019</td>
    <td>http://house.union</td>
    <td>Hunting</td>
  </tr>
  <tr>
    <td>Koniglich Essen</td>
    <td>15 april 2019</td>
    <td>http://mac.union</td>
    <td>Virus</td>
  </tr>
  <tr>
    <td>Magazzini Alimentari Riuniti</td>
    <td>15 mei 2018</td>
    <td>http://sentgrid.union</td>
    <td>Malware</td>
  </tr>
  <tr>
    <td>Paris specialites</td>
    <td>15 november 2019</td>
    <td>http://apple.union</td>
    <td>Threat</td>
  </tr>
  <tr>
    <td>Island Trading</td>
    <td>7 oktober 2000</td>
    <td>http://pino.union</td>
    <td>Compromised</td>
  </tr>
  <tr>
    <td>Laughing Bacchus Winecellars</td>
    <td>15 mei 2019</td>
    <td>http://bootstrap.union</td>
    <td>D DOS</td>
  </tr>
</table>

<br>
<br>
<br>

<table class="sortable">
<thead>
  <tr><th>Person</th><th>Monthly pay</th></tr>
</thead>
<tbody>
  <tr><td>Jan Molby</td><td>£12,000</td></tr>
  <tr><td>Steve Nicol</td><td>£8,500</td></tr>
  <tr><td>Steve McMahon</td><td>£9,200</td></tr>
  <tr><td>John Barnes</td><td>£15,300</td></tr>
</tbody>
<tfoot>
  <tr><td>TOTAL</td><td>£45,000</td></tr>
</tfoot>
</table>

<table class="sortable">
<tr><th>Number (spelled)</th><th>Person</th></tr>
<tr><td sorttable_customkey="2">two</td><td>Jan</td></tr>
<tr><td sorttable_customkey="3">three</td><td>Bruce</td></tr>
<tr><td sorttable_customkey="1">one</td><td>Steve</td></tr>
</table>


<script>

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

</body>
</html>
