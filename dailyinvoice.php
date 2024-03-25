<?php
include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/select.php");
include("../sd_multicare/dbconnect/queris/getaccessoriesinvoiceId.php");
$result = getaccessoriesinvoiceId($conn);
     
if($result->num_rows>0){

 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

  $billID =  intval($row['Auto_increment']);
  
}
}

$inValid = false;
$submited = false;
$successEvent;
$errorEvent;
if (isset($_POST["submitreload"])) {
  $submited = true;
  $inValid = empty($_POST["data"]) || empty(trim($_POST["reloadAmount"]));
  if (!$inValid) {

    if (!is_numeric($_POST["reloadAmount"])) {


      $submited = true;
      $inValid = true;
      $errorEvent = "Please Enter Numbers";
    } else {
      

     accessoriesPaymentItemInsert(0,$conn);
     
      $reloadType = $_POST["data"];
      $amount = $_POST["reloadAmount"];
      $itemType = 'reload';
      if ($amount > 0) {
      
        reloadDataAdd($billID,$reloadType, $amount, $itemType, $conn);
    $submited = true;
    $inValid = false;
    $successEvent = "Payment Successfully Added";
      } else {
        $submited = true;
        $inValid = true;
        $errorEvent = "Please Enter Positive Numbers";
      }
    }
  } else {
    $errorEvent = "Please Enter the Reload  Amount";
  }
}






if (isset($_POST["closebutton"])) {
  header('Location: /sd_multicare/dailyinvoice.php');
  exit();
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
  <title>Daily Invoice</title>
</head>

<body class="bg-secondary">
  <div>

    <?php
    include("header.php");


    ?>
  </div>



  <div class="container text-center">
    <div class="row">

      <div class="col">
        <br>
        <br>
        <br>
        <br>

        <div>
          <a data-bs-toggle="modal" data-bs-target="#ReloadModal" data-bs-whatever="@mdo"><img src="./assets/Images/reload.png" alt="invoice" class="img-fluid" style="width:300px; height: 300px;">

            <div style="padding-top: 10px; ">
              <p class="fw-bold fs-1 text-dark    ">Reload</p>
            </div>
          </a>


          <div class="modal fade" id="ReloadModal" tabindex="-1" aria-labelledby="ReloadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ReloadModalLabel">Reload Payment</h1>
                  <!-- <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close" name="closebutton"></button> -->
                </div>
                <div class="modal-body">

                  <form method="post">
                    <div class="mb-3">
                      <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="data">
                        <option value="Dialog">Dialog</option>
                        <option value="Mobitel">Mobitel</option>
                        <option value="Hutch">Hutch</option>
                        <option value="Airtel">Airtel</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <input type="float" name="reloadAmount" class="form-control " placeholder="Please enter the Amount">

                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal" name="closebutton">Close</button>
                  <button type="submit" name="submitreload" class="btn btn-primary">Add</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="col">
        <br>
        <br>
        <br>
        <br>

        <div>
          <a href="accesoriess.php" style="text-decoration:none"><img src="./assets/Images/accessories.jpg" alt="invoice" class="img-fluid" style="width:300px; height: 300px;">

            <div style="padding-top: 10px; ">
              <p class="fw-bold fs-1 text-dark    ">Accessories</p>
            </div>
          </a>


        </div>
        <?php
        if ($submited) {
          if ($inValid) {
        ?>
            <script>
              swal({
                title: "<?php echo $errorEvent ?>",
                // text: "You clicked the button!",
                icon: "warning",
                button: "ok",
              });
            </script>
          <?php } else { ?>
            <script>
              swal({
                title: "<?php echo $successEvent ?>",
                // text: "You clicked the button!",
                icon: "success",
                button: "ok",
              });
            </script>
        <?php }
        } ?>
</body>

</html>