<?php

function itemUpdate($ItemNO,$ItemName,$ItemType,$UnitCost,$SellingPrice,$Qty,$conn){

    $sql = "UPDATE `stock` SET `itemNo`='".$ItemNO."',`ItemName`='".$ItemName."',`Type`='".$ItemType."',`Cost`='".$UnitCost."',`SellingPrice`='".$SellingPrice."',`qty`='".$Qty."' WHERE `itemNo`='".$ItemNO."'";

    if($conn->query($sql)){

    //     header('Location: /sd_multicare/stocks.php');
 
    // exit();
return true;


    }


}



function accesoriessPurchaceStockChange($itemNO,$Qty,$conn){

    $sql = "UPDATE `stock` SET `qty`='".$Qty."' WHERE `itemNo`='".$itemNO."'";

    if($conn->query($sql)){

        

        header('Location: /sd_multicare/dailyinvoice.php');
 
    exit();



    }


}

function itemQtyUpdate($ItemNoUpdate,$Qty,$conn){


    $sql = "UPDATE `stock`
    SET `qty` = CASE 
                    WHEN (`qty` - ".$Qty.") >= 0 THEN `qty` -".$Qty."
                    ELSE `qty` 
                END
    WHERE `itemNo` = '".$ItemNoUpdate."' AND `qty` > 0; ";

    

    if($conn->query($sql)){

     



    }


}

// function discountvalueUpdate($discountValue,$billID,$conn){

//     echo $discountValue;
//     //$billID1 = $billID-1;

//     echo $billID;

//     $sql = "UPDATE `accessoriesbill` SET `discount`=$discountValue WHERE `billNo`=$billID";

    

//     if($conn->query($sql)){

//      unset($_SESSION['discount']);



//     }


// }




?>