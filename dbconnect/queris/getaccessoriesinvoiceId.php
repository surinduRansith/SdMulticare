<?php 


function getaccessoriesinvoiceId($conn){

$sql ="SELECT Table_schema,Table_Name,Auto_increment
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'sdmulticarehouse'

AND TABLE_NAME = 'accessoriesbill';";

$result = mysqli_query($conn,$sql);

    return $result;

}

?>