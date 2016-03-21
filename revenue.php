<?php include'include/header.php';?>



<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style>
  
  </style>
</head>
<?php
  include('config.php');
  $conn = new mysqli($server, $user, $pass, $db);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
   include'leftsidebar.php';
?>
<!--<div class="col-md-12 btn">
<div class="col-md-11"></div>
<div class="col-md-1"><a href="index.php?logout" class="btn btn-danger">Logout</a></div>
</div>-->
<div class="container col-md-12">

<div class="col-md-2"></div>
<div class="col-md-10"><table class="table table-hover table-striped ">
  <thead>
    <tr>
      <th>id</th>
      <th>Cost</th>
    </tr>
  </thead>
  <tbody>
<?php
$sql = "SELECT * FROM `invoice`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
  $cost = explode('|', $row['cost']);
    for($i = 1, $size = count($cost); $i < $size; $i++){
      $totalcost += $cost[$i];
      $grandtotal += $cost[$i];
    }
?>
<tr class="<?php if($totalcost==0){echo 'danger';} ?>">
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td>£<?=$totalcost;?></td>
</tr>
<?php
  $totalcost = 0;
  }
}
?>
    <tr class="success"><td><h1>Grand Total</h1></td><td><h1>£<?=$grandtotal;?></h1></td></tr>
  </tbody>
</table></div>
</div>
<?php 
require_once('include/footer.php');
?>

