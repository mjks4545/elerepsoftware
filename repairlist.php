<?php

session_start();
//require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
	include'include/header.php';

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
$sql = "SELECT * FROM devices";
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
<div class="col-md-12">
<div class="col-md-11"></div>
<div class="col-md-1"><a href="index.php?logout" class="btn btn-danger">Logout</a></div>
</div>

<span class="counter pull-right"></span>
<table class="table table-hover table-striped results">
  <thead>
    <tr>
      <th>Reference</th>
      <th>Model</th>
      <th>Manufacturer</th>
      <th>Identifier</th>
      <th>Status</th>
      <th>Assigned Engineer</th>
      <th style="width: 500px;">Additional Notes</th>
      <th>Options</th>
    </tr>
    <tr class="warning no-result">
      <td colspan="10"><i class="fa fa-warning"></i> No result</td>
    </tr>
  </thead>
<tbody>
    <tr><td colspan="10"><h3>Received</h3></td></tr>
<?php
    while($row = $result->fetch_assoc()) {
      $history = explode('|', $row['history']);
      $status = explode('|', $row['statushistory']);
      $owner = $row['owner'];
      if(end($status)=='Received') {
?>
<tr class="danger">
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
  <td><?=end($status);
if($row["assignedengineer"] == $_SESSION['user_name']){
?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
<?php if(empty($row["assignedengineer"])) { ?>
<td>
  <form role="form" class="form-inline" action="assignengineer.php" method="post">
    <input type="hidden" name="id" value="<?=$row["id"];?>">
    <input class="btn btn-danger btn-xs" type="submit" name="submit" value="Assign Me!">
  </form>
</td>
<?php } else { ?>
  <td><?=$row["assignedengineer"];?></td>
<?php } ?>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>

    </div>
  </td>
</tr>
<?php } } ?>
</tbody>
<tbody>
  <tr><td colspan="10"><h3>Engineer Assigned</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $history = explode('|', $row['history']);
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
if(end($status)=='Engineer Assigned') {
?>
<tr>
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
  <td><?=end($status);?>
<?php if($row["assignedengineer"] == $_SESSION['user_name']) { ?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input type="hidden" name="owner" value="<?=$owner;?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
  <td><?=$row["assignedengineer"];?></td>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>

    </div>
  </td>
</tr>
<?php } } ?>
</tbody>
<tbody>
    <tr><td colspan="10"><h3>Awaiting Parts</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $history = explode('|', $row['history']);
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
if(end($status)=='Awaiting Parts') {
?>
<tr>
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
<?php if(end($status)=='Customer Collected') { ?>
  <td><?=end($status);?></td>
<?php } else { ?>
<td><?=end($status);?>
<?php if($row["assignedengineer"] == $_SESSION['user_name']) { ?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
<?php } if(empty($row["assignedengineer"])) { ?>
<td>
  <form role="form" class="form-inline" action="assignengineer.php" method="post">
    <input type="hidden" name="id" value="<?=$row["id"];?>">
    <input class="btn btn-danger btn-xs" type="submit" name="submit" value="Assign Me!">
  </form>
</td>
<?php } else { ?>
  <td><?=$row["assignedengineer"];?></td>
<?php } ?>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>

    </div>
  </td>
</tr>
<?php } } ?>
</tbody>
<tbody>
    <tr><td colspan="10"><h3>Awaiting Repair</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $history = explode('|', $row['history']);
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
if(end($status)=='Awaiting Repair') {
?>
<tr>
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
  <td><?=end($status);?>
<?php if($row["assignedengineer"] == $_SESSION['user_name']) { ?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
  <td><?=$row["assignedengineer"];?></td>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>

    </div>
  </td>
</tr>
<?php } } ?>
</tbody>
<tbody>
    <tr><td colspan="10"><h3>Repairing</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
if(end($status)=='Repairing') {
?>
<tr>
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
<td><?=end($status);?>
<?php if($row["assignedengineer"] == $_SESSION['user_name']) { ?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
  <td><?=$row["assignedengineer"];?></td>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>

    </div>
  </td>
</tr>
<?php } } ?>
</tbody>
<tbody>
    <tr><td colspan="10"><h3>Repaired</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $history = explode('|', $row['history']);
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
  if(end($status)=='Repaired') {
?>
<tr class="success">
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
  <td><?=end($status);?>
<?php if($row["assignedengineer"] == $_SESSION['user_name']) { ?>
<form role="form" class="form-inline" action="updatestatus.php" method="post">
<select class="form-control" name="currentstatus">
  <option value="Awaiting Parts">Awaiting Parts</option>
  <option value="Awaiting Repair">Awaiting Repair</option>
  <option value="Repairing">Repairing</option>
  <option value="Repaired">Repaired</option>
  <option value="Unable to Repair">Unable to Repair</option>
  <option value="Customer Collected">Customer Collected</option>
</select>
<input type="hidden" name="id" value="<?=$row["id"];?>">
<input class="btn btn-default" type="submit" name="submit" value="Update">
</form>
<?php } ?>
  </td>
  <td><?=$row["assignedengineer"];?></td>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="5" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td>
    <div class="btn-group-vertical">
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('trackingreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Tracking Receipt</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('updateinvoice.php?id=<?=$row['id'];?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Invoice</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="window.open('viewowner.php?owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=600,height=300,scrollbars=no,status=no');">Customers Details</button>
    </div>
</td>
  </tr>
<?php } } ?>
</tbody>
</table>
</div>
</div>


<?php
} else { echo "<a href='index.php'>No devices in repair list</a>"; }
$conn->close();
} else { header( 'Location: index.php' ); }
?>

<?php include'include/footer.php';  ?>