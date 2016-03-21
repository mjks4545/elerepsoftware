<?php
//session_start();
?>

<?php
require_once("config/db.php");
require_once("classes/Login.php");

$login = new Login();
if ($login->isUserLoggedIn() == true) {
include 'include/header.php';
?>
<!-- ================================================  -->
 <?php require_once 'leftsidebar.php';?>
<!-- ===================================--->
<!--<div class="container">
    <h2>Welcome, <?=$_SESSION['user_name'];?></h2>
    <div class="panel panel-default">
        <div class="panel-body"><a href="inputdevice.php">Add Device To Tracker</a></div>
        <div class="panel-body"><a href="repairlist.php">View Tracker</a></div>
        <div class="panel-body"><a href="myjobs.php">My Jobs</a></div>
        <div class="panel-body"><a href="customercollected.php">Customer Collected</a></div>
        <div class="panel-body"><a href="unabletorepair.php">Unable To Repair</a></div>
        <div class="panel-body"><a href="customers.php">Customers List</a></div>
        <div class="panel-body"><a href="revenue.php">Revenue</a></div>
        <div class="panel-body"><a href="statistics.php">Statistics</a></div>
    </div>
</div>-->
<?php
include 'include/footer.php';
}
 else
 {
//ob_start();
include 'include/header_login.php' ;
?>

<div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b>ELE</b>REP</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your Normal work</p>
        <form role="form" method="post" action="index.php" name="loginform">
          <div class="form-group has-feedback">
            <input type="text" id="login_input_username" class="form-control" name="user_name" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input id="login_input_password" name="user_password" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

        <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div><!-- /.social-auth-links -->

        <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
	
	<!-- ================================ -->

<!--
<div class="container">
    <h2>Login</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            <form role="form" method="post" action="index.php" name="loginform">
                <div class="form-group">
                    <label class="sr-only" for="login_input_username">Username</label>
                    <input class="form-control" id="login_input_username" class="login_input" type="text" name="user_name" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <label class="sr-only" for="login_input_password">Password</label>
                    <input class="form-control" id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" placeholder="Password" required />
                </div>
                <input class="btn btn-default" type="submit"  name="login" value="Log in" />
            </form>
        </div>
    </div>
</div>
-->
<?php  
include 'include/footer_login.php' ;
}

?>

