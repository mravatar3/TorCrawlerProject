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
  </head>
   
<body>
  
<div class="topnav">
  <a class="active" href="basis.php">Crawler</a>
  <a href="statistics.php">Statistics</a>
  <div class="search-container">
    <form action="/basis.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit">Submit</button>
    </form>
  </div>
</div>