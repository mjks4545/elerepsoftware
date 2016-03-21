<?php
  session_start();
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $.fn.strech_text = function(){
    var elmt          = $(this),
        cont_width    = elmt.width(),
        txt           = elmt.html(),
        one_line      = $('<span class="stretch_it">' + txt + '</span>'),
        nb_char       = elmt.text().length,
        spacing       = cont_width/nb_char,
        txt_width;

    elmt.html(one_line);
    txt_width = one_line.width();

    if (txt_width < cont_width){
        var  char_width     = txt_width/nb_char,
             ltr_spacing    = spacing - char_width + (spacing - char_width)/nb_char ;

        one_line.css({'letter-spacing': ltr_spacing});
    } else {
        one_line.contents().unwrap();
        elmt.addClass('text-justify');
    }
  };
  $(document).ready(function () {
    $('.stretch').each(function(){
        $(this).strech_text();
    });
  });
  </script>
  <style>
    .stretch_it {
      white-space: nowrap;
    }
  </style>
</head>
<?php
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_REQUEST['id'])){
  $id = $_REQUEST['id'];
  $sql = "SELECT * FROM `devices` WHERE id='$id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
?>
  <div class="container">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>Reference</th>
          <th>Model</th>
          <th>Manufacturer</th>
          <th>Identifier</th>
          <th>Status</th>
          <th>Assigned Engineer</th>
        </tr>
      </thead>
      <tbody>
<?php
    while($row = $result->fetch_assoc()) {
      $status = explode('|', $row['statushistory']);
      global $owner;
      $owner = $row['owner'];
?>
      <tr class="<?php if(end($status)=='Repaired'){echo "success";} elseif(end($status)=='Received'){echo "danger";} ?>">
        <td><?=$row["id"];?></td>
        <td><?=$row["model"];?></td>
        <td><?=$row["manufacturer"];?></td>
        <td><?=$row["identifier"];?></td>
        <td><?=end($status);?></td>
        <td><?=$row["assignedengineer"];?></td>
      </tr>
<?php
    }
    mysqli_data_seek($result, 0);
?>
  </tbody>
  </table>
  <table class="table table-hover table-striped">
    <thead>
      <tr>
        <th>Status History</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
<?php
  while($row = $result->fetch_assoc()) {
    $history = explode('|', $row['history']);
    global $status;
    $status = explode('|', $row['statushistory']);
    for($i = 0, $size = count($status); $i < $size; $i++){
?>
    <tr class="<?php if(end($status)=='Repaired'){echo "success";} elseif(end($status)=='Received'){echo "danger";} ?>">
      <td><?=$status[$i];?></td>
      <td><?=$history[$i];?></td>
    </tr>
<?php
    }
  }
?>
  </tbody>
  </table>
  </div>
<?php
  $sql = "SELECT * FROM `invoice` WHERE id='$id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
?>
    <div class="container">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <td>Summary</td>
          <td>Cost</td>
        </tr>
      </thead>
      <tbody>
<?php
    while($row = $result->fetch_assoc()) {
      $summary = explode('|', $row['summary']);
      $cost = explode('|', $row['cost']);
      for($i = 1, $size = count($summary); $i < $size; $i++){
  ?>
        <tr>
          <td><?=$summary[$i];?></td>
          <td>£<?=$cost[$i];?></td>
        </tr>
  <?php } } ?>
</tbody>
</table>
</div>
<?php
} } else {
  goto end;
}
  $conn->close();
} else {
  end:
?>
<div class="container">
  <h2>Track Your Device</h2>
  <div class="panel panel-default">
    <div class="panel-body">
  <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div class="form-group">
      <label class="sr-only" for="id">Please enter your tracking ID/Reference:</label>
      <input class="form-control input-lg" type="text" name="id" placeholder="Tracking ID/Reference">
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Track">
  </form>
</div>
</div>
</div>
<?php
}
?>
