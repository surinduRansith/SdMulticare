
<?php

function insertStockData($conn){

  $sql = "SELECT * from stock WHERE `status`= 1 ;";
$result = mysqli_query($conn,$sql);


return $result;

}



function addStockItem($itemNo, $itemName, $type, $unitCost, $sellingPrice, $qty, $conn, $itemAddSuccess)
{

  $sql =  $sql = "INSERT INTO `stock`(`itemNo`,`ItemName`, `Type`, `Cost`, `SellingPrice`, `qty`,`status`) VALUES ('$itemNo','$itemName','$type','$unitCost','$sellingPrice','$qty',1)";

  $result = mysqli_query($conn, $sql);

  if(!$result){

    $sql = "UPDATE `stock` SET `ItemName`='".$itemName."',`Type`=' ".$type."',`Cost`=$unitCost,`SellingPrice`= $sellingPrice ,`status`= 1 ,`qty`=$qty WHERE itemNo='".$itemNo."' AND `status`= 0 ";

    $result = mysqli_query($conn, $sql);
   
  }

 return $result;


}



function reloadDataAdd($billId,$reloadType, $amount, $itemType, $conn)
{

  $sql = "INSERT INTO reload(`billNo`, `ItemName`, `itemType`, `itemAmount`) VALUES ('$billId','$reloadType','$itemType','$amount')";

  $result = mysqli_query($conn, $sql);


  if ($result > 0) {

    // header('Location: /sd_multicare/dailyinvoice.php?mode=s');
    // exit();
  } else {
    // header('Location: /sd_multicare/dailyinvoice.php?mode=f');
    // exit();
 
  }
}





function accessoriesPaymentItemInsert($billType,$conn)
{

  $sqlTotalBill = "INSERT INTO `accessoriesbill`(`billtype`) VALUES ('$billType')";



  $resultTotalBill = mysqli_query($conn, $sqlTotalBill);

  if ($resultTotalBill>0 ) {

    
   
    }
  }

function accesoriesBillItemList($billID,$itemNo1 ,$itemName1 , $valueitemqty,$note,$discountType,$discountValue,$conn){

  $sqlBillItem = "INSERT INTO `accessoriesitem`(`billNo`, `ItemNo`, `itemName`, `itemQty`,`note`,`discounttype`,`discount`) VALUES ($billID,'$itemNo1','$itemName1',$valueitemqty,'$note','$discountType',$discountValue)";


  

  $resultBillItem = mysqli_query($conn, $sqlBillItem);
  if ($resultBillItem>0 ) {
//echo "<script> alert('Item add successfully')</script>";

  }else{ 
  
    //echo "<script> alert('Item add not successfully')</script>";
  }
}

function insertPrintBill($billID,$itemName,$note,$amount,$conn){

$sql = "INSERT INTO `print_others`(`billNo`, `itemName`,`note`, `Amount`) VALUES ('$billID','$itemName','$note','$amount')";


$result = mysqli_query($conn, $sql);


  if ($result > 0) {

    // header('Location: /sd_multicare/dailyinvoice.php?mode=s');
    // exit();
  } else {
    // header('Location: /sd_multicare/dailyinvoice.php?mode=f');
    // exit();
 
  }




}

function usercreate($userName,$password,$userRole,$conn){

 $sql = "INSERT INTO `userlogin`(`username`, `password`, `role`) 
 VALUES ('$userName','$password','$userRole')"; 

$result = mysqli_query($conn, $sql);


if ($result > 0) {

 return true;
} else {
return false;

}


}




?>


