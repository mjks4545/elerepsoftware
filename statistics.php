
<?php
session_start();
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
	 require_once("include/header.php");
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
    $(".search").keyup(function () {
      var searchTerm = $(".search").val();
      var listItem = $('.results tbody').children('tr');
      var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
          return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });

    $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','false');
    });

    $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','true');
    });

    var jobCount = $('.results tbody tr[visible="true"]').length;
      $('.counter').text(jobCount + ' item');

    if(jobCount == '0') {$('.no-result').show();}
      else {$('.no-result').hide();}
  		  });
  });
  </script>
  <style>
    .results tr[visible='false'],
  .no-result{
  display:none;
  }

  .results tr[visible='true']{
  display:table-row;
  }

  .counter{
  padding:8px;
  color:#ccc;
  }
 
 .container{
	  padding-top: 44px;
	   padding-buttom: 44px;
	 
 }
  </style>
 </head>

<?php
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT COUNT(`manufacturer`) AS count, `manufacturer` FROM `devices` GROUP BY `manufacturer` ORDER BY COUNT(`manufacturer`) DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
 


include'leftsidebar.php';
?>

<div class="container col-md-12">

<div class="col-md-2"></div>
<div class="col-md-10">
<div class="form-group pull-right">
    <input type="text" class="search form-control" placeholder="What you looking for?">
</div>
<span class="counter pull-right"></span>

 
<table class="table table-hover table-striped results">
  <thead>
    <tr>
      <th>Manufacturer</th>
      <th>count</th>
	  </tr>

    
  </thead>
  <tbody>
<?php
    while($row = $result->fetch_assoc()) {
?>
<tr>
  <td><?=$row['manufacturer'];?></td>
  <td><?=$row['count'];?></td>
</tr>

<?php
    }
?>
</tbody>
<!--</table>-->
<?php
}
$sql = "SELECT COUNT(`model`) AS count, `model` FROM `devices` GROUP BY `model` ORDER BY COUNT(`model`) DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
<!--<table class="table table-hover table-striped results">-->
 <!-- <thead>-->
    <tr>
      <th>Model</th>
      <th>count</th>
    </tr>
 <!-- </thead>-->
  <tbody>
<?php
    while($row = $result->fetch_assoc()) {
?>
<tr>
  <td><?=$row['model'];?></td>
  <td><?=$row['count'];?></td>
</tr>
<?php
    }
?>
</tbody>
</table>
<?php
}
$conn->close();
}
?>
</div>

</div>


<?php 
require_once('include/footer.php');
?>
