<?php
// Initialize the session
session_start();
include("config.php");
 
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
  <link rel="stylesheet" type="text/css" href="style.css"/>
  <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
  </head>
   
<body>
  
<div class="topnav">
  <a class="active" href="basis.php">Home</a>
  <a href="index.php">Start a crawler</a>
  <a href="statistics.php">Statistics</a>
  <a href="searchresults.php">Threats Zoeken</a>
  <div class="search-container">
    <form name="form1" method="post" action="searchresults.php" >
      <input type="text" placeholder="Threats zoeken..." name="search">
      <button type="submit">Zoeken</button>
    </form>
  </div>
  
 
</div>