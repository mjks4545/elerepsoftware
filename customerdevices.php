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
if(isset($_REQUEST['owner'])){
  $owner = $_REQUEST['owner'];
  $sql = "SELECT * FROM `devices` WHERE owner='$owner'";
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
?>
      <tr class="<?php if(end($status)=='Repaired'){echo "success";} elseif(end($status)=='Received'){echo "danger";} ?>">
        <td><?=$row["id"];?></td>
        <td><?=$row["model"];?></td>
        <td><?=$row["manufacturer"];?></td>
        <td><?=$row["identifier"];?></td>
        <td><?=end($status);?></td>
        <td><?=$row["assignedengineer"];?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } else {
  echo "No devices found for this customer";
}
  $conn->close();
}
?>
