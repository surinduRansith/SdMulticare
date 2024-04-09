<?php
include("../sd_multicare/dbconnect/dbconnect.php");
$sql = "SELECT * from stock where status=1;";
$result = mysqli_query($conn,$sql);
if (isset($_POST["submit"])) {




}


if($_SESSION['userrole']=="admin"){

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

     
       
    
       
      
        <a style='text-decoration:none' href = './crud/update.php? upadateId=".$row['itemNo']."' class='text-light'><button type='submit'  class='btn btn-warning'>
        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' width='16' height='16' stroke='currentColor' class='w-6 h-6'>
  <path stroke-linecap='round' stroke-linejoin='round' d='M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99' />
</svg>
</button></a>
        
       

     
        <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal".$row['itemNo']."'>
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' fill='currentColor' class='w-6 h-6'>
        <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
      </svg>
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
<?php    


}elseif($_SESSION['userrole']=="user"){

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

     
       
    
       
       <button type='submit'  class='btn btn-warning' disabled>
        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' width='16' height='16' stroke='currentColor' class='w-6 h-6'>
  <path stroke-linecap='round' stroke-linejoin='round' d='M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99' />
</svg>
</button>
        
       

     
        <button disabled type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal".$row['itemNo']."'>
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' fill='currentColor' class='w-6 h-6'>
        <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
      </svg>
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

<?php 
}
?>