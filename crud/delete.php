<?php 

include("../dbconnect/dbconnect.php");

if (isset($_GET['deleteId'] )){

$itemNO = $_GET['deleteId'];


$sql = "UPDATE `stock` SET `status`=0,`qty`=0 WHERE itemNo='".$itemNO."'";

$result = mysqli_query($conn, $sql);

if ($result){


    header('Location: /sd_multicare/stocks.php');
 
    exit();

}
}


if (isset($_GET['billid'] )){

    $billID = $_GET['billid'];

    $sql = "DELETE FROM `accessoriesitem` WHERE  billNo= $billID;";

$result = mysqli_query($conn, $sql);

if ($result){

    $sql = "DELETE FROM `accessoriesbill` WHERE billNo=$billID;";

    $result = mysqli_query($conn, $sql);

    if($result){

     header('Location: /sd_multicare/report.php');
 
        exit();

    }
}
}

if (isset($_GET['billid'] )){

    $billID = $_GET['billid'];

    $sql = "DELETE FROM `reload` WHERE  billNo= $billID;";

$result = mysqli_query($conn, $sql);

if ($result){


    $sql = "DELETE FROM `accessoriesbill` WHERE billNo=$billID;";

    $result = mysqli_query($conn, $sql);

    if($result){

     header('Location: /sd_multicare/report.php');
 
        exit();

    }

   

}



}

if (isset($_GET['billid'] )){

    $billID = $_GET['billid'];

    $sql = "DELETE FROM `print_others` WHERE  billNo= $billID;";

$result = mysqli_query($conn, $sql);

if ($result){


    $sql = "DELETE FROM `accessoriesbill` WHERE billNo=$billID;";

    $result = mysqli_query($conn, $sql);

    if($result){

     header('Location: /sd_multicare/report.php');
 
        exit();

    }
}
}

if(isset($_GET['userid'])){
  
    $userID= $_GET['userid'];

   $sql="DELETE FROM `userlogin` WHERE id=$userID";

    $result = mysqli_query($conn, $sql);

    if($result){

     header('Location: /sd_multicare/usercreate.php');
 
        exit();

    }

}


?>
  



  
