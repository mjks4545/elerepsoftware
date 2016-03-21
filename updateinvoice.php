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
    $owner = $_REQUEST['owner'];
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
        <td>
          £<?=$cost[$i];?>
          <?php if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1){ ?>
            <form class="form-inline hidden-print pull-right" role="form" method="post" action="/track/removeinvoiceitem.php">
              <input type="hidden" name="id" value="<?=$id;?>">
              <input type="hidden" name="owner" value="<?=$owner;?>">
              <input type="hidden" name="i" value="<?=$i;?>">
              <input type="submit" class="btn btn-danger btn-xs" name="submit" value="Remove">
            </form>
          <?php } ?>
        </td>
      </tr>
<?php } } } ?>
      </tbody>
    </table>
<button type="button" class="btn btn-primary btn-block" onclick="window.open('/track/viewinvoice.php?id=<?=$id;?>&amp;owner=<?=$owner;?>', '_blank', 'location=no,scrollbars=no,status=no');">Generate Invoice</button>
<div class="panel panel-primary" style="margin-top: 20px;">
  <div class="panel-heading"><h2>Add Invoice Items</h2></div>
  <div class="panel-body">
    <form class="form center-block" role="form" method="post" action="/track/addinvoiceitem.php">
      <div class="form-group">
        <label class="sr-only" for="item">Invoiced Item:</label>
        <textarea class="form-control" rows="5" name="item" placeholder="Invoiced Item"></textarea>
      </div>
      <div class="form-group">
        <label class="sr-only" for="cost">Amount:</label>
        <div class="input-group">
          <div class="input-group-addon">£</div>
          <input type="number" class="form-control" name="cost" min="0" step="0.01" placeholder="Amount">
        </div>
      </div>
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="owner" value="<?=$owner;?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Add">
    </form>
  </div>
</div>
</div>
<? } ?>
