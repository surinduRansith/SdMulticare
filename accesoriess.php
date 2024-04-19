<?php


  include("header.php");
  
  

include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/getaccessoriesinvoiceId.php");
include("../sd_multicare/dbconnect/queris/updateItemData.php");

$result = getaccessoriesinvoiceId($conn);
     
if($result->num_rows>0){

 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

  $billID =  intval($row['Auto_increment']);

  
 }



}
$fullTotal=0;
$quantity = 0;
$submited = true;
$inValid = false;
$fullTotal1=0;
$downloadInvoice="";
$clearInvoice = "";
$closeButton = "";
$stockdata = insertStockData($conn);

$stockArray = array();
$cartList = array();


if($stockdata->num_rows > 0){
  
  while($row = mysqli_fetch_array($stockdata,MYSQLI_ASSOC)){

    $stockArray[]  = array(
      'itemNo' => $row['itemNo'],
      'ItemName' => $row['ItemName'],
      'Type' => $row['Type'],
      'Cost' => $row['Cost'],
      'SellingPrice' => $row['SellingPrice'],
      'qty' => $row['qty']
  );
  
  
}
}


if (isset($_POST['cartList'])) {

  $cartList = $_POST['cartList'];
 

}

$duplicateCount =0;

if (isset($_POST['item'])){
  
if(empty( $cartList)){

  $cartList[] = $_POST['item'];

}else{
  foreach($cartList as $index => $value){

    if($value == $_POST['item']){

      $duplicateCount = $duplicateCount+1;


    }


  }

if($duplicateCount==0){
  $cartList[] = $_POST['item'];

}else{

  $submited = true;
  $inValid = true;
  $errorEvent = "Can't Add Duplicate Item";
}



}

}



//  print_r($cartList);

//  echo $duplicateCount;



if(isset($_POST['itemremove'])){

  $removeItem = $_POST['itemremove'];

  unset($cartList[array_search($removeItem, $cartList)]);
}



if(isset($_POST['itemadd'])){

  $cartListLength = sizeof($cartList); 
  $count =0;
  //echo $count; 

  foreach($cartList as $value =>$index){
    $quantity = $_POST['quantity'][$index];
    if(intval($quantity)>0 && intval($quantity)<=$stockArray[$index]['qty']){
    $count=$count+1;
    if($count == $cartListLength){

  accessoriesPaymentItemInsert(1,$conn);


  foreach($cartList as $value =>$index){
    
    $itemNo1= $stockArray[$index]['itemNo'];
    $itemName1 = $stockArray[$index]['ItemName'];
    
    $quantity = $_POST['quantity'][$index];
    $discountType = $_POST['discounttype'];
    $discountValue = $_POST['discount'];
    $total = $quantity*$stockArray[$index]['SellingPrice'];
    $note =$_POST['note'][$index];
    $fullTotal1 = $fullTotal1+intval($total);
//echo $_POST['note'][$index];
     itemQtyUpdate($itemNo1,$quantity,$conn);
     accesoriesBillItemList($billID,$itemNo1 ,$itemName1 , $quantity,$note,$discountType,$discountValue,$conn);
if($discountType=="presentage"){
if($discountValue>0){

  $discountprice=($fullTotal1* $discountValue)/100;

  $fullTotal = $fullTotal1-$discountprice;

}else{

  $fullTotal=$fullTotal1;
}
}elseif($discountType=="cash"){

  if($discountValue>0){

   
  
    $fullTotal = $fullTotal1-$discountValue;
  
  }else{
  
    $fullTotal=$fullTotal1;
  }



}

}
$submited = true;
$inValid = false;
$successEvent = "Item Add Successfully";




$downloadInvoice="<button type='submit' name='printinvoice' class='btn btn-warning'> Download Invoice </button>";
}
}else{

  $submited = true;
  $inValid = true;
  $errorEvent = "Can't Add (-) Quantity";

 }

  }
}


if(isset($_POST['printinvoice'])){
  
  $url = "/sd_multicare/reportsPdf/invoicegenerate.php?billid=$billID";
  echo "<script>window.open('$url', '_blank');</script>";
  


}

if(isset($_POST['invocieExit'])){

  header("Location:/sd_multicare/dailyinvoice.php");
  
  exit();

}

if(isset($_POST['clearInvoice'])){

  $cartList = array();


}


?>





<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
<link rel="stylesheet" href="style.css">
    <title>Stocks</title>
</head>
<body class="bg-secondary">

<div class='container'>
<br>
<form method = "post">
        <?php

        
echo "<table id='myTable' class='table table-striped table-dark'>";
echo "<thead>";
echo "<tr>
<th >Item Code</th>
<th >Item Name</th>
<th >Item Type</th>
<th >Selling Price</th>
<th >Quantity</th>
<th></th>

</tr>";

echo "</thead>";
echo "<tbody>";


        foreach($stockArray as $index1=>$value){

 
        echo "
        
    <tr>
        <td>".$value['itemNo']."</td>
        <td>".$value['ItemName']."</td>
        <td>".$value['Type']."</td>
        <td>Rs. ".$value['SellingPrice']."</td>
        <td>".$value['qty']."</td>
        <td> 
        <button type='submit' name='item' class='btn btn-warning' value='".$index1."'>
        add to cart
        </button>  
        </td>
    </tr>
        ";
          
    }

    foreach ($cartList as $index){
      


    echo "<input type='text' hidden name='cartList[]' value='".$index."'>"; 

    //echo $value['itemNo'];


      }
    

    echo "</tbody>";
    echo "</table>";

    

   


?>
</form>

<?php 
if(!empty($cartList)){
?>
  <div class="container text-center">
<form method="post">

  <div class="row align-items-start">
    <div class="col input-group mb-3">
    <span class="input-group-text" id="basic-addon1">Bill No</span>

<input type="text" class="form-control" name="billNumber" value="<?php echo $billID; ?>">
    </div>
    <div class="col">
     
    </div>
    <div class="col">
      
    </div>
  </div>

  <table id="myTable" class="table table-striped table-dark">
<thead>
<tr>
<th >Item Code</th>
<th >Item Name</th>
<th >Item Type</th>
<th >Selling Price</th>
<th >Quantity</th>
<th>Note</th>
<th></th>

</tr>

</thead>
<tbody>
<?php
$notetest="-";
  foreach($cartList as $index)
    echo "<tr>
        <td>".$stockArray[$index]['itemNo']."</td>
        <td>".$stockArray[$index]['ItemName']."</td>
        <td>".$stockArray[$index]['Type']."</td>
        <td>Rs. ".$stockArray[$index]['SellingPrice']."</td>
        <td><input type='number' name='quantity[$index]' value='1'></td>
        <td><input type='text' name='note[$index]' value='".$notetest."'></td>
        <td>
        <button type='submit' name='itemremove' class='btn btn-danger' value='".$index."'>
        Remove
        </button>
        </td>

    </tr>";

    foreach($cartList as $index){

      echo "<input type='hidden' name='cartList[]' value='$index'>";

    }

    echo "<tr>
    <td></td>
    <td></td>
    <td>Discount</td>
    
    <td>
    <select class='form-select form-select-sm mb-3' aria-label='Large select example' name='discounttype' > 
            
        <option value='cash'>Cash</option>
        <option  value='presentage'>Presentage(%)</option>
        </select>
          </div></td>
    <td><input type='number' name='discount' value='0'></td>
    <td></td>
    <td>
    
    </td>

</tr>";
    echo "<tr>
        <td></td>
        <td></td>
        <td></td>
        
        <td>Total</td>
        <td>RS. ".$fullTotal."</td>
        <td></td>
        <td>
        
        </td>

    </tr>";
    echo "</tbody>";
    echo "</table>";
    
    ?>
   
    <button type="submit" name="itemadd" class="btn btn-success"> Add Bill </button>
   
    <?php  

echo $downloadInvoice;
    ?>
     <button  type='submit' name='clearInvoice' class='btn btn-danger' > Clear Invoice</button>
    
</form>
<?php
}
?>
    </div>
</div>
  
   
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready( function () {
    $('#myTable').DataTable({

      "lengthMenu": [ 3 ]

    });

    $('#cartTable').DataTable();
  });
</script>

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