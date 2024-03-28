<?php
include("../sd_multicare/dbconnect/dbconnect.php");
$sql = "SELECT * from stock where status=1;";
$result = mysqli_query($conn,$sql);
if (isset($_POST["submit"])) {




}


?>





<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" src="styletable.php">
    <title>Stocks</title>
</head>
<body class="bg-secondary">
<div class='container'>
        <?php

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
        if($result->num_rows > 0){

    

    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

        
        echo "
        
    <tr>
        <td>".$row['itemNo']."</td>
        <td>".$row['ItemName']."</td>
        <td>".$row['Type']."</td>
        <td>Rs. ".$row['Cost']."</td>
        <td>Rs. ".$row['SellingPrice']."</td>
        <td>".$row['qty']."</td>
        <td> 

     
       
    
       
        <button type='button' class='btn btn-warning' >
        <a style='text-decoration:none' href = './crud/update.php? upadateId=".$row['itemNo']."' class='text-light'>Update</a>
        </button>
        
       

     
        <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal".$row['itemNo']."'>
        Delete
      </button>
      
     
      <div class='modal fade' id='exampleModal".$row['itemNo']."' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h1 class='modal-title text-dark fs-5' id='exampleModalLabel'>Item Delete</h1>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
              // <b class='text-dark'>Do you want to delete this Item ".$row['itemNo']."?</b>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
              <button type='button' class='btn btn-danger'><a style='text-decoration:none' href = './crud/delete.php?deleteId=".$row['itemNo']."' class='text-light'>Delete</a></button>
            </div>
          </div>
        </div>
      </div>

        
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
  $(document).ready( function () {
    $('#myTable').DataTable();
  });
</script>

</body>
</html>