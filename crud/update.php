<?php 
include("../dbconnect/dbconnect.php");
include("../dbconnect/queris/select.php");
include("../dbconnect/queris/updateItemData.php");


$submited = true;
$inValid = true;
$closeButton="";

if (isset($_GET["upadateId"])){

    $itemNoId = $_GET["upadateId"];
    

$itemlist_array = getStcokData($itemNoId,$conn);

$getItemNo = $itemlist_array[0];
$getItemName = $itemlist_array[1];
$getItemType = $itemlist_array[2];
$getItemCost = $itemlist_array[3];
$getItemSellingPrice = $itemlist_array[4];
$getItemQty = $itemlist_array[5];









}






if ($getItemType == 'Mobile'){

    $itemvalue="selected";
    
}else{

    $itemvalue="";
   
}

if ($getItemType == 'Computer'){

    $itemvalue="selected";
    
}else{

    $itemvalue="";
 
}

if(isset($_POST['sumbitUpdate'])){
    $ItemNO = $_POST['itemNo'];
    $ItemName = $_POST['itemName'];
    $ItemType = $_POST['data'];
    $UnitCost = $_POST['unitCost'];
    $SellingPrice = $_POST['sellingPrice'];
    $Qty = $_POST['qty'];


 $result =  itemUpdate($ItemNO,$ItemName,$ItemType,$UnitCost,$SellingPrice,$Qty,$conn);

    if($result === true){

      echo "Done";
      $submited = true;
      $inValid = false;
      $successEvent = "Item Update Successfully";


      $closeButton = "<button class='btn btn-danger' name='sumbitClose'><b> Close </b></button>";

 
    }


}

if(isset($_POST['sumbitClose'])){

    header('Location: /sd_multicare/stocks.php');
 exit();

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Stocks Update</title>
</head>
<body class="bg-secondary">

<div class="container text-center position-absolute top-50 start-50 translate-middle w-25 p-3 border border-warning rounded">
    <form  method="post">
    
        <br>
        <div class="input-group mb-3">
  <input type="text" class="form-control disable" placeholder="Item No" name="itemNo" value="<?php echo $getItemNo ?>">
</div>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Item Name" name="itemName" value="<?php echo $getItemName ?>">
</div>
 <div class=" input-group mb-3">
        <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="data" > 
            
        <option <?php echo $itemvalue ?>  value="Mobile">Mobile Accessories</option>
        <option  <?php echo $itemvalue ?> value="Computer">Computer Accessories</option>
        </select>
          </div>
          <div class="input-group mb-3">
  
  <input type="double" class="form-control" placeholder="Unit Cost" name="unitCost" value="<?php echo $getItemCost ?>">
</div>
<div class="input-group mb-3">

  <input type="double" class="form-control" placeholder="Selling Price" name="sellingPrice" value="<?php echo $getItemSellingPrice?>">
</div>

<div class="input-group mb-3">

  <input type="number" class="form-control" placeholder="QTY"  name="qty" value="<?php echo $getItemQty?>">
</div>
        <button class="btn btn-success" name="sumbitUpdate"><b> Update </b></button>

        <?php 

        echo $closeButton;
?>
        </div>


        <br><br>

       
        
            
          
    </form>



    </div>
</body>
</html>
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


