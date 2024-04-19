<?php
include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/select.php");

include("header.php");


    ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
    <title>User Create</title>
</head>

<body class="bg-secondary">



    <div class=" container">
    <div class="input-group mb-3 pt-2">
  <h2> Customer Table </h2>
</div>  
<form  method="post">
    <table id='myTable' class='table table-striped table-dark'>
  <thead>
    <tr>
      <th></th>
      <th>Customer Name</th>
      <th>Phone Number</th>
   
    </tr>
  </thead>
  <tbody>
    <?php 
    $customerCount = 0;
       $customerlistresult=customerlist($conn);

       if($customerlistresult->num_rows > 0){

        while($row = mysqli_fetch_array($customerlistresult,MYSQLI_ASSOC)){
          $customerCount++;
            echo "<tr>
            <td>".$customerCount."</td>
      <td>".$row['name']."</td>
      <td>".$row['phonenumber']."</td>
    
      
    </tr>";


        }
    }

?>
    
    
  </tbody>
</table>
    


</form>
    </div>


</body>

</html>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready( function () {
    $('#myTable').DataTable();
  });
</script>



