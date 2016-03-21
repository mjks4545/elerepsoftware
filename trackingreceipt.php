<?php
  session_start();
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style type="text/css">
     <!--
     @page { size:5.83in 8.27in; margin: 0.5cm; }
     -->
  </style>
</head>
<?php
  $id = $_GET['id'];
  $date = $_GET['date'];
  $owner = $_GET['owner'];
  include('config.php');
  $conn = new mysqli($server, $user, $pass, $db);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $result = $conn->query("SELECT * FROM `customers` WHERE id='$owner'");
  if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
?>
<body>
  <div class="container">
    <h2 class="pull-left">eLeREP</h2>
    <h4 class="pull-right text-right" style="padding-top: 25px;">Tracking Receipt</h4>
    <div class="clearfix"></div>
    <div class="panel panel-default">
      <div class="panel-body">
        <span class="pull-left">Job Reference: <strong><?=$id;?></strong></span><span class="pull-right">Date: <?=$date;?></span>
        <div class="clearfix"></div>
      </div>
      <div class="panel-body">
        <div class="pull-right text-right">
          <address style="text-transform: capitalize;">
            <?=$row['lastname'];?>, <?=$row['firstname'];?><br>
            <?=$row['address'];?><br>
            <?=$row['citytown'];?><br>
            <?=$row['postcode'];?><br>
          </address>
        </div>
      </div>
<pre>
Dear Customer,
Thank you for visiting eLeREP Digital Electronics and Repairs, We believe in providing the best quality service with efficiency and customer satisfaction in the forefront of our philosophy.
Your Product/Device has been handed over now to one of our expert engineers who will do their utmost to restore it to a working manner.

You may track your device repair progress by visiting
<h4>http://elerep.com/track</h4>
and using your job reference as is stated on this receipt as your tracking ID.

Please do not hesitate to contact us via our phone number if you have any enquiries or just pop into our store again where one of our representatives will be more than pleased to assist you.
</pre>
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
<div class="panel-footer">
  <span>Address: 351-353 Station Road, Harrow, HA1 2AW</span><br>
  <span>Contact Number: 02088613313</span>
</div>
    </div>
  </div>
</body>
<?php } } ?>
