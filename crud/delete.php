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





?>
  