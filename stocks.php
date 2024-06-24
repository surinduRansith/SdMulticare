<?php
include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/select.php");




$WarningAlertforEmpty = false;
$itemAddsuccess = false;
$submited = false;



if (isset($_POST["submitStock"])) {
    $WarningAlertforEmpty = true;
    $submited = true;
    $itemNO = trim($_POST['itemNo']);
    $itemName = trim($_POST['itemName']);
    $unitCost = trim($_POST['unitCost']);
    $sellingPrice = trim($_POST['sellingPrice']);
    $qty = trim($_POST['qty']);

    $itemAddsuccess = empty($itemNO) || empty($itemName) || empty($unitCost) || empty($sellingPrice) || empty($qty) && !is_numeric($unitCost) || !is_numeric($sellingPrice);

    if (!$itemAddsuccess) {

        $type = $_POST['data'];


        $result = addStockItem($itemNO, $itemName, $type, $unitCost, $sellingPrice, $qty, $conn, $itemAddsuccess);

        if ($result > 0) {

            $submited = true;
            $itemAddsuccess = true;
            $successEvent = "Successfully data added";
        } else {

            $errorEvent = "Already add this item No";
        }
    } else {
        $submited = false;
        $WarningAlertforEmpty = true;
        $errorEvent = "Please fill the inputs";
    }
}




if (isset($_POST['submitClose'])) {


    $itemName = "";
    $unitCost = "";
    $sellingPrice = "";
    $qty = "";
}
















?>


<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
    <title>Stocks</title>
</head>

<body class="bg-secondary">
    <div>

        <?php

        include("header.php");


        ?>
    </div>
    <br>
    <div class="container text-center">
        <form method="post">
            <div class="row">
                <div class="col">

                    <div class="input-group mb-3">

                    </div>


                </div>
                <div class="col">



                </div>
                <div class="col">




                </div>
            </div>
            <div class="row">
                <div class="col">

                    <div class="input-group mb-3">

                    </div>
        </form>
    </div>
    <div class="col">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#CreateStock">
            <img src="./assets/Images/stockadd.png" alt="invoice" class="img-fluid" style="width:35px; height: 35px;">
            Add Stock
        </button>

        <!-- Modal -->
        <div class="modal fade" id="CreateStock" tabindex="-1" aria-labelledby="CreateStockLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="CreateStockLabel">Add Stock</h1>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Item No" name="itemNo">
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Item Name" name="itemName">
                            </div>
                            <div class=" input-group mb-3">
                                <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="data">
                                    <option value="Mobile">Mobile Accessories</option>
                                    <option value="Computer">Computer Accessories</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">

                                <input type="double" class="form-control" placeholder="Unit Cost" name="unitCost">
                            </div>
                            <div class="input-group mb-3">

                                <input type="double" class="form-control" placeholder="Selling Price" name="sellingPrice">
                            </div>

                            <div class="input-group mb-3">

                                <input type="number" class="form-control" placeholder="QTY" name="qty">
                            </div>





                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="submitClose" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submitStock" class="btn btn-primary">Add Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="col">




    </div>

    <div class="row">

        <?php

        $successEvent;
        $errorEvent;



        ?>
        <script>
            swal({
                title: "<?php echo $errorEvent ?>",
                // text: "You clicked the button!",
                icon: "warning",
                button: "ok",
            });
        </script>


    </div>





    </div>
    <?php


    include("../sd_multicare/stockTable/stockTable.php");


    ?>

</body>

</html>
<?php

if ($WarningAlertforEmpty) {
?>

    <script>
        swal({
            title: "<?php echo $errorEvent ?>",
            // text: "You clicked the button!",
            icon: "warning",
            button: "ok",
        });
    </script>

<?php
}
?>

<?php
if ($submited) {
    if (!$itemAddsuccess) {

?>
        <script>
            swal({
                title: "<?php echo $errorEvent ?>",
                // text: "You clicked the button!",
                icon: "warning",
                button: "ok",
            });
        </script>

    <?php
    } else {
    ?>



        <script>
            swal({
                title: "<?php echo $successEvent ?>",
                // text: "You clicked the button!",
                icon: "success",
                button: "ok",
            });
        </script>

<?php



    }
}






?>