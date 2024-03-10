<?php 
include("../dbconnect/dbconnect.php");
include("../dbconnect/queris/select.php");
include("../dbconnect/queris/updateItemData.php");


session_start();



if(isset($_GET['itemRemove'])){

            unset($_SESSION['itemList'][$_GET['itemRemove']]);
           
            unset($_SESSION['discountValue']);
            header('Location: /sd_multicare/accesoriess.php');
                  exit();   
}







?>
