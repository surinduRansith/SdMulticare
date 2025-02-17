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
        stock.ItemName,
       stock.SellingPrice,
       accessoriesitem.itemQty,
       accessoriesitem.discounttype,
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
       accessoriesitem.ItemNo, stock.ItemName, stock.SellingPrice, accessoriesitem.itemQty,
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
function reloadBillitems($startDate,$endDate,$billNo,$reportType,$conn){


    $sql = "SELECT accessoriesbill.billNo, reload.ItemName, reload.itemAmount,accessoriesbill.billtype,accessoriesbill.date 
    FROM reload,accessoriesbill WHERE  accessoriesbill.billNo = reload.billNo AND accessoriesbill.date BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billtype = ".$reportType." AND accessoriesbill.billNo=$billNo ";
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

function getAllBills($startDate,$endDate,$conn){

    $sql = "SELECT *
    FROM `accessoriesbill` 
    WHERE `date` BETWEEN '".$startDate."' AND '".$endDate."';";
    
    $result = mysqli_query($conn,$sql);
    
    return $result;




}

function getItemCountofsale($startDate,$endDate,$conn){

    $sql = "SELECT ai.ItemNo,s.ItemName,ab.date, sum(ai.itemQty) AS item_count
FROM accessoriesbill ab
INNER JOIN accessoriesitem ai ON ab.billNo = ai.billNo
INNER JOIN stock s ON ai.ItemNo = s.itemNo 
where ab.date BETWEEN '".$startDate."' AND '".$endDate."'
GROUP BY ai.ItemNo 
";
    
    $result = mysqli_query($conn,$sql);
    
    return $result;
    
}

function getprintData($startDate,$endDate,$itemType,$conn){

    $sql = "SELECT  po.billNo, po.itemName, po.Amount,  po.note,ab.date,ab.billtype FROM print_others po
    INNER JOIN  accessoriesbill ab 
    ON ab.billNo=po.billNo
    WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billtype=$itemType;";
    
    $result = mysqli_query($conn,$sql);
    
    return $result;




}

function getprintDatalist($startDate,$endDate,$billNo,$itemType,$conn){

    $sql = "SELECT  po.billNo, po.itemName, po.Amount,po.note, ab.date,ab.billtype FROM print_others po
    INNER JOIN  accessoriesbill ab 
    ON ab.billNo=po.billNo
    WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billtype=$itemType AND po.billNo=$billNo ;";
    
    $result = mysqli_query($conn,$sql);
    
    return $result;




}

function getitemNotes($startDate,$endDate,$itemNO,$conn){
    $sql = "SELECT ai.ItemNo,ai.itemName,ai.note,ab.date
FROM accessoriesbill ab
INNER JOIN accessoriesitem ai ON ab.billNo = ai.billNo
where ab.date BETWEEN '".$startDate."' AND '".$endDate."'
AND ai.ItemNo = '$itemNO' 
";

$result = mysqli_query($conn,$sql);
    
return $result;

}

function getitems($startDate,$endDate,$billID,$conn){

$sql = "SELECT ab.billNo,s.ItemName,ai.itemQty,ai.note,ab.date 
FROM accessoriesitem ai 
INNER JOIN accessoriesbill ab ON ai.billNo=ab.billNo
INNER JOIN stock s ON ai.ItemNo = s.itemNo 
WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billNo = $billID;";

$result = mysqli_query($conn,$sql);
    
return $result;


}

function getitemstest($startDate,$endDate,$conn){

    $sql = "SELECT ab.billNo,ai.itemName,ai.itemQty,ai.note,ab.date FROM accessoriesitem ai 
    INNER JOIN accessoriesbill ab ON ai.billNo=ab.billNo
    WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."';";
    
    $result = mysqli_query($conn,$sql);
        
    return $result;
    
    
    }

    function getuserNames($userName,$conn){
 
        $sql ="SELECT `username` FROM `userlogin` WHERE username='$userName';";

        $result = mysqli_query($conn,$sql);
        
    return $result;


    }
    function getuserLogin($conn){
 
        $sql ="SELECT `id`, `username`, `role` FROM `userlogin`;";

        $result = mysqli_query($conn,$sql);
        
    return $result;


    }

    function getuserLoginid($userid,$conn){
 
        $sql ="SELECT `id`, `username`,`password`,  `role` FROM `userlogin` WHERE id=$userid ;";

        $result = mysqli_query($conn,$sql);
        
    return $result;


    }

    function customerlist($conn){

        $sql = "SELECT `name`, `phonenumber`
        FROM `customer`;";

$result = mysqli_query($conn,$sql);
        
return $result;


    }

function getAllItemListNote($startDate,$endDate,$conn){

    $sql = "SELECT 
    ab.billNo, 
    ai.ItemNo, 
    s.ItemName, 
    ai.itemQty, 
    ai.note, 
    ab.date 
FROM 
    accessoriesitem ai 
INNER JOIN 
    accessoriesbill ab ON ai.billNo = ab.billNo
INNER JOIN 
    stock s ON ai.ItemNo = s.itemNo 
WHERE 
    ab.date BETWEEN '".$startDate."' AND '".$endDate."' 
    AND (
        ai.ItemNo = 'SPD-002' 
        OR ai.ItemNo = 'TM-000' 
        OR ai.ItemNo = 'TM-002'
    ); ";

    $result= mysqli_query($conn,$sql);

    return $result;
}



?>