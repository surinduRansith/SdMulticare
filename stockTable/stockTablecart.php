<?php
include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");

$stockdata = insertStockData($conn);



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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Stocks</title>
</head>

<body class="bg-secondary">
    <div class='container'>
        <?php

        if($stockdata->num_rows > 0){

        echo "<table id='myTable' class='table table-striped table-dark'>";
        echo "<thead>";
      echo "<tr>
      <th >Item Number</th>
      <th >Item Name</th>
      <th >Item Type</th>
      <th >Unit Cost</th>
      <th >Selling Price</th>
      <th >Quantity</th>
      <th></th>

    </tr>";

    echo "</thead>";
    echo "<tbody>";
    

    while($row = mysqli_fetch_array($stockdata,MYSQLI_ASSOC)){

        
        echo "
        
    <tr>
        <td>".$row['itemNo']."</td>
        <td>".$row['ItemName']."</td>
        <td>".$row['Type']."</td>
        <td>Rs. ".$row['Cost']."</td>
        <td>Rs. ".$row['SellingPrice']."</td>
        <td>".$row['qty']."</td>
        <td> 

     
       
    
       
        <button type='button' name='addcart' class='btn btn-warning' >
        add to cart
        </button>
        
        
        </td>

    </tr>
        ";
    }

    echo "</tbody>";
    echo "</table>";

    

}
   


?>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({

            "lengthMenu": [3,5,10]

        });
    });
    </script>

</body>

</html>