<?php 

include("../dbconnect/dbconnect.php");

if (isset($_GET['deleteId'] )){

$itemNO = $_GET['deleteId'];


$sql = "DELETE FROM `stock` WHERE itemNo='".$itemNO."'";

$result = mysqli_query($conn, $sql);

if ($result){


    header('Location: /sd_multicare/stocks.php');
 
    exit();

}
}





?>
  