<?php


  include("header.php");
  
  

include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/getaccessoriesinvoiceId.php");
include("../sd_multicare/dbconnect/queris/updateItemData.php");

$result = getaccessoriesinvoiceId($conn);

$resultid = getcustomerId($conn);
     
if($result->num_rows>0){

 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

  $billID =  intval($row['Auto_increment']);

  
 }

}

if($resultid->num_rows>0){

  while($row = mysqli_fetch_array($resultid, MYSQLI_ASSOC)){
 
   $CustomerID =  intval($row['Auto_increment']);
 
   
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
$subTotal = 0;
$stockdata = insertStockData($conn);
$discountValue=0;

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

$billDate="";


if(isset($_POST['itemadd'])){
  $phoneNumber=$_POST['phoneNumber'];
  $billDate= $_POST['billDate'];



  if (preg_match("/^\d{10}$/", $phoneNumber)) {

  $cartListLength = sizeof($cartList); 
  $count =0;
  //echo $count; 

  foreach($cartList as $value =>$index){
    $quantity = $_POST['quantity'][$index];
    if(intval($quantity)>0 && intval($quantity)<=$stockArray[$index]['qty']){
    $count=$count+1;
    if($count == $cartListLength){

  accessoriesPaymentItemInsert(1,$billDate,$conn);


  foreach($cartList as $value =>$index){
    
    $itemNo1= $stockArray[$index]['itemNo'];
    $itemName1 = $stockArray[$index]['ItemName'];

    $quantity = $_POST['quantity'][$index];
    $discountType = $_POST['discounttype'];
    $discountValue = $_POST['discount'];
    $total = $quantity*$stockArray[$index]['SellingPrice'];
    $note =$_POST['note'][$index];
    $fullTotal1 = $fullTotal1+intval($total);
    $subTotal = $subTotal+intval($total);
//echo $_POST['note'][$index];
     itemQtyUpdate($itemNo1,$quantity,$conn);
    
     accesoriesBillItemList($billID,$itemNo1, $quantity,$note,$discountType,$discountValue,$conn);
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

$custonmerName=$_POST['customerName'];
$phoneNumber=$_POST['phoneNumber'];

if(!empty($custonmerName) || !empty($phoneNumber)){

  customerdetails($custonmerName,$phoneNumber,$billID,$CustomerID,$conn);

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
  }else{

    $submited = true;
    $inValid = true;
    $errorEvent = "Please Enter Valid Phone Number";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
    <link rel="stylesheet" href="style.css">
    <title>Stocks</title>
</head>

<body class="bg-secondary">

    <div class='container'>
        <br>
        <form method="post" >
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
        <button type='submit' name='item' class='btn btn-warning' value='".$index1."' >
        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'  width='16' height='16' fill='currentColor'  stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
  <path stroke-linecap='round' stroke-linejoin='round' d='M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z' />
</svg>

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
        <div>
            <form method="post" id="invoiceForm">

                <div class="row align-items-start">
                    <div class="col input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Bill No</span>

                        <input type="text" class="form-control" name="billNumber" value="<?php echo $billID; ?>">
                    </div>
                    <div class="col">
                        <div class="col input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Date</span>
                            <input id="date" type="date" class="form-control" aria-label="Date"
                                name="billDate" value="<?php echo date('Y-m-d'); ?>" >
                        </div>

                    </div>
                    <div class="col">
                        <div class="col input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Customer Name</span>
                            <input type="text" class="form-control" name="customerName">
                        </div>

                    </div>
                    <div class="col">
                        <div class="col input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Phone Number</span>
                            <input id="phoneInput" type="tel" class="form-control" aria-label="Phone number"
                                name="phoneNumber">
                        </div>

                    </div>
                    
                </div>

                <table id="myTable" class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Item Type</th>
                            <th>Selling Price</th>
                            <th>Quantity</th>
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
        <td><input type='text'  name='note[$index]' value='".$notetest."'></td>
        <td>
        <button type='submit' name='itemremove' class='btn btn-danger' value='".$index."'>
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' fill='currentColor' class='w-6 h-6'>
        <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
      </svg>
        </button>
        </td>

    </tr>";

    foreach($cartList as $index){

      echo "<input type='hidden' name='cartList[]' value='$index'>";

    }

    echo "<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>Sub Total</td>
    <td>Rs. ".$subTotal."</td>
    <td></td>
    <td>
    
    </td>

</tr>";


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
    <td><input type='number' name='discount' value='".$discountValue."'></td>
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
<div class="container text-center">
                        <button type="submit" name="itemadd" class="btn btn-success" id="createInvoice"> Add Bill </button>

                        <?php  

echo $downloadInvoice;

    ?>
                        <button type='submit' name='clearInvoice' class='btn btn-danger'> Clear Invoice</button>
</div>
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
    $(document).ready(function() {
        $('#myTable').DataTable({

            "lengthMenu": [3,5,10]

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

<script>
document.getElementById("invoiceForm").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Prevents default form submission behavior
        document.getElementById("createInvoice").click(); // Clicks the "Add" button
    }
});
</script>
<?php }
        } ?>