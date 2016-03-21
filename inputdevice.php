<?php
session_start();
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<?php
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['submit'])){
  $result = $conn->query("SELECT * FROM `customers` WHERE firstname = '".trim($conn->real_escape_string($_POST['firstname']))."' AND lastname = '".trim($conn->real_escape_string($_POST['lastname']))."' LIMIT 1");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $custid = $row['id'];
    }
  }
  else {
    $custid = uniqid("cust_",true);
    $sql = "INSERT INTO customers (id, firstname, lastname, contactnumber, address, citytown, postcode) VALUES ('".trim($conn->real_escape_string($custid))."', '".trim($conn->real_escape_string($_POST['firstname']))."', '".trim($conn->real_escape_string($_POST['lastname']))."', '".trim($conn->real_escape_string($_POST['contactnumber']))."', '".trim($conn->real_escape_string($_POST['address']))."', '".trim($conn->real_escape_string($_POST['citytown']))."', '".trim($conn->real_escape_string($_POST['postcode']))."');";
  }
  $deviceid = uniqid();
  $now = new DateTime(null, new DateTimeZone('Europe/London'));
  $notes = $conn->real_escape_string($_POST['notes']);
  $sql .= "INSERT INTO devices (id, owner, model, manufacturer, identifier, statushistory, history, notes) VALUES ('".trim($conn->real_escape_string($deviceid))."', '".trim($conn->real_escape_string($custid))."', '".trim($conn->real_escape_string($_POST['model']))."', '".trim($conn->real_escape_string($_POST['manufacturer']))."', '".trim($conn->real_escape_string($_POST['identifier']))."', 'Received', '".trim($conn->real_escape_string($now->format('H:i d/m/Y')))."', '".trim($conn->real_escape_string($notes))."');";
  $sql .= "INSERT INTO `invoice` (id, summary, cost) VALUES ('".$conn->real_escape_string($deviceid)."', '0', '0');";
  if ($conn->multi_query($sql) === TRUE) {
    echo "<script>setTimeout(function(){location.href='/track/repairlist.php'} , 500);</script>
    ";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $conn->close();
} else {
    include 'include/header.php';
    include 'leftsidebar.php';
?>
<div class="wrapper">

 
  <!-- Left side column. contains the logo and sidebar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
            

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
        </div>
       
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
              
    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">First Name</label>
                  <input type="text" class="form-control" name="firstname" id="exampleInputEmail1" placeholder="Enter Your First Name">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Last Name</label>
                  <input type="text" class="form-control" name="lastname" id="exampleInputPassword1" placeholder="Enter your last Name">
                </div>
				<div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                  <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Enter your E-mail Address">
                </div>
				<div class="form-group">
                  <label for="exampleInputPassword1">Contact Number</label>
                  <input type="text" class="form-control" name="contactnumber" id="exampleInputPassword1" placeholder="Enter Your contact no">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Address</label>
                  <input type="text" class="form-control"name="address" id="exampleInputPassword1" placeholder="Enter your address">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">City/Town</label>
                  <input type="text" class="form-control" name="citytown" id="exampleInputPassword1" placeholder="Enter your city/town">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Postcode</label>
                  <input type="text" name="postcode" class="form-control" id="exampleInputPassword1" placeholder="Enter your postcode">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Device Manufacture</label>
                  <input type="text" name="manufacture" class="form-control" id="exampleInputPassword1" placeholder="Device Manufacture">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Device Model</label>
                  <input type="text" name="model" class="form-control" id="exampleInputPassword1" placeholder="Enter your Device Model">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Device Identifier</label>
                  <input type="text" name="identifier" class="form-control" id="exampleInputPassword1" placeholder="Enter device identifier">
                </div>
                <div class="form-group">
                </div>
				<div class="form-group">
				<label for="notes">Additional Notes:</label>
				<textarea class="form-control" name="notes" rows="4" cols="50" placeholder="Please enter additional notes for engineers here, i.e. faults, repairs to be completed etc.">
				</textarea>
				</div>
      
                
             
                <div class="checkbox">
                  <label>
                    <input type="checkbox"> Check me out
                  </label>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="submit" class="btn btn-primary" name="submit" value="Add Device to Queue">
              </div>
	
	</form>
  </div>
  </div>
  </div>
  </section>
  </div>
  </div>
<?php
include 'include/footer.php';
}
} else {
    header( 'Location: index.php' );
}
?>
