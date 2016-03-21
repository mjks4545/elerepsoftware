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
  $owner = $_GET['owner'];
  $id = $_GET['id'];
  $result = $conn->query("SELECT * FROM `customers` WHERE id='$owner'");
  if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
?>
  <div class="container">
    <h2>Invoice</h2>
    <div class="panel panel-default">
      <div class="panel-body pull-left">
        <address style="text-transform: capitalize;">
          <?=$row['lastname'];?>, <?=$row['firstname'];?><br>
          <?=$row['address'];?><br>
          <?=$row['citytown'];?><br>
          <?=$row['postcode'];?><br>
          <?=$row['contactnumber'];?><br>
        </address>
      </div>
      <div class="panel-body pull-right text-right">
        <address>
          eLEReP<br>
          351 - 353 Station Road<br>
          Harrow<br>
          London<br>
          HA1 2AW
        </address>
      </div>
      <div class="clearfix"></div>
      <div class="center-block" style="width: 200px;">
        <img alt="<?=$id;?>" src="../barcode.php?text=<?=$id;?>" />
        <div class="text-center stretch"><?=$id;?></div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <td>Summary</td>
            <td>Cost</td>
          </tr>
        </thead>
        <tbody>
<?php
$sql = "SELECT * FROM `invoice` WHERE id='$id'";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $summary = explode('|', $row['summary']);
      $cost = explode('|', $row['cost']);
      for($i = 1, $size = count($summary); $i < $size; $i++){
?>
        <tr>
          <td><?=$summary[$i];?></td>
          <td>£<?=$cost[$i];?></td>
        </tr>
<?php
      }
    }
  }
?>
        </tbody>
      </table>
<pre>
LIABILITY EXCLUSIONS

Although we do not have specific knowledge of your computer configuration we will attempt to minimise disruption to your system as much as we can but we cannot be responsible for any unforeseen issues that may arise from any of our services.

Please note that if your computer system or equipment is under manufacturer warranty our services may affect manufacturer warranty validity.
It is your responsibility to assess the effect of our services on any manufacturers warranty.

We cannot be held responsible or liable to any service performed for you regarding:
<ul>
<li>any loss data, data corruption, loss of images, documents or information.</li>
<li>any financial loss, or loss and interruption to business or contracts.</li>
<li>any failure by you to follow our reasonable recommendations or instructions.</li>
<li>any losses or issues you may suffer due to your use of (or failure to use) any anti-virus software.</li>
<li>any loss that is not reasonably foreseeable.</li>
</ul>
</pre>
    </div>
  </div>
<?php
  }
}
?>
