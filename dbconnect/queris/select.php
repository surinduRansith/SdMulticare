<?php



function selectLoginDetatils($conn,$userName,$password){

    $sql = "Select * from userlogin where username = '" . $userName . "' and password='" . $password . "'";


        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {




            header('Location: home.php');
        } else {
            $setusername = $userName;
            $error = 'User Name or Password Incorrect';
        }
}

function getStcokData($itemNo,$conn){

    $sql = "SELECT itemNo, `ItemName`, Type, Cost, `SellingPrice`, qty FROM stock WHERE itemNo='".$itemNo."'";
    //echo $sql;
    
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$getItemNO = $row['itemNo'];
$getItemName = $row['ItemName'];
$getItemType = $row['Type'];
$getUnitCost = $row['Cost'];
$getSellingPrice =  $row['SellingPrice'];
$getQty = $row['qty'];





return array($getItemNO,$getItemName,$getItemType,$getUnitCost,$getSellingPrice,$getQty);

}


function serchStockItemName($itemName,$conn){
    
    $sql = "SELECT * from stock  WHERE  `ItemName` LIKE '".$itemName."%';";
    $result = mysqli_query($conn,$sql);

    return $result;

}


function serchStockItemNo($itemNo,$conn){
    
    $sql = "SELECT * from stock  WHERE  itemNO LIKE '".$itemNo."%';";
    $result = mysqli_query($conn,$sql);

    return $result;

}

function serchAccessoriesItemName($itemName,$itemType,$conn){
    
    $sql = "SELECT * from stock  WHERE type='$itemType' and  `ItemName` LIKE '".$itemName."%';";
    $result = mysqli_query($conn,$sql);

    return $result;

}

function serchStockItemListArray($itemNo,$conn){
    
    $sql = "SELECT * from stock  WHERE  itemNO = '".$itemNo."';";
    $result = mysqli_query($conn,$sql);

    return $result;

}



function accessoriesReport($startDate,$endDate,$conn){

$sql = "SELECT ItemNo,itemName,itemQty,discount
FROM `accessoriesitem` 
WHERE billNo=(select billNo from accessoriesbill
WHERE `date` BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billNo='855')";

$result = mysqli_query($conn,$sql);

return $result;


}

function accessoriesbill($startDate,$endDate,$reportType,$conn){

$sql = "SELECT *
FROM `accessoriesbill` 
WHERE `date` BETWEEN '".$startDate."' AND '".$endDate."' AND billtype = ".$reportType." ;";

$result = mysqli_query($conn,$sql);

return $result;

}

function  billTotal($billID , $conn){

 
    $sql = "SELECT
    accessoriesitem.billNo,
        accessoriesitem.ItemNo,
       accessoriesitem.itemName,
       stock.SellingPrice,
       accessoriesitem.itemQty,
       accessoriesitem.discount,
       accessoriesbill.date,
       (stock.SellingPrice * accessoriesitem.itemQty) AS subTotal,
       SUM(stock.SellingPrice * accessoriesitem.itemQty) OVER () AS Total
    FROM
       accessoriesitem,accessoriesbill,stock
    
    WHERE
    
    accessoriesitem.ItemNo = stock.itemNo and accessoriesbill.billNo = accessoriesitem.billNo  AND
 accessoriesitem.billNo=$billID
      
    GROUP BY
       accessoriesitem.ItemNo, accessoriesitem.itemName, stock.SellingPrice, accessoriesitem.itemQty,
       accessoriesitem.discount LIMIT 1;";




    $result = mysqli_query($conn,$sql);

   

return $result;





}



function reloadBill($startDate,$endDate,$reportType,$conn){


    $sql = "SELECT accessoriesbill.billNo, reload.ItemName, reload.itemAmount,accessoriesbill.billtype,accessoriesbill.date 
    FROM reload,accessoriesbill WHERE  accessoriesbill.billNo = reload.billNo AND accessoriesbill.date BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billtype = ".$reportType."";
    $result = mysqli_query($conn,$sql);

    return $result;

}

function getItemCount($conn){
    
    $sql ="SELECT ItemNo, itemName, SUM(itemQty) as Total 
    FROM `accessoriesitem` 
    GROUP BY ItemNo, itemName;";

$result = mysqli_query($conn,$sql);

return $result;


}


?>