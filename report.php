<?php 

include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/select.php");
$reportType ="";

if(isset($_POST['printinvoice'])){
        
        $billID= $_POST['printinvoice'];
      
      
        $url = "/sd_multicare/reportsPdf/invoicegeneratereport.php?billid=$billID";
        echo "<script>window.open('$url', '_blank');</script>";
       

}

$billNOArray = array();
$reloadBillArray = array();


if(isset($_POST['search'])){
  
  
  if($_POST['reportType']==1){
    $reportType=$_POST['reportType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate']; 

$billresult =accessoriesbill($startDate,$endDate,$reportType,$conn);



if($billresult->num_rows > 0){
  
  while($row = mysqli_fetch_array($billresult,MYSQLI_ASSOC)){

    $billNOArray[]  = array(
      'billNo' => $row['billNo'],
      'date' => $row['date']
      
    
  );

  }
}
}elseif($_POST['reportType']==0){
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $reportType=$_POST['reportType'];
  $reloadbillresult = reloadBill($startDate,$endDate,$reportType,$conn);

if($reloadbillresult->num_rows>0){
  
  while($row = mysqli_fetch_array($reloadbillresult,MYSQLI_ASSOC)){

    $reloadBillArray[]  = array(
      'billNo' => $row['billNo'],
      'itemName' => $row['ItemName'],
      'itemAmount' => $row['itemAmount'],
      
      
    
  );

  }
}
}
//print_r($reloadBillArray);
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Stocks</title>
</head>
<body class="bg-secondary">
    <div>

        <?php
    include("header.php");
    

    ?>
    </div>
<br>
<form method="post">
<div class="container text-center">

<div class="row">

<div class="col-3 ">
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">Start Date</span>
  <input type="date" class="form-control"  name="startDate">
</div>

</div>
<div class="col-3">
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">End Date</span>
  <input type="date" class="form-control"  name="endDate">
</div>

</div>
<div class="col-4">

<input class="form-check-input" type="radio" name="reportType" value="Reload & Accesoriess Bill">
  <label class="form-check-label" for="reportType1">
    All 
  </label>

  <input class="form-check-input" type="radio" name="reportType" value="0">
  <label class="form-check-label" for="reportType1">
    Reload
  </label>

  <input class="form-check-input" type="radio" name="reportType" value="1">
  <label class="form-check-label" for="reportType1">
   Accesoriess
  </label>



</div>
<div class="col-2">

<button type="submit" class="btn btn-primary" name="search" >search</button>
</div>
</div>
<div class="row">


<?php
$fullTotal=0;
$discountValue = 0;
$rangeTotal=0;
if(isset($_POST['search'])){
  
  if($_POST['reportType']==1){

echo "<P class='fs-1'> Accessories Bill <P>";

  echo "<table id='myTable' class='table table-striped table-dark'>
  <thead>
  <tr>
  <th >Bill No</th>
  <th >Bill Date</th>
  <th> Total Price </th>
  <th></th>

  
  </tr>
  
  </thead>
  <tbody>
";


//$result = accessoriesReport($startDate,$endDate,$conn);

foreach($billNOArray as $index =>$value){
  
 
  echo "<tr>
        <td>".$value['billNo']."</td>
        <td>".$value['date']."</td>";
        
        echo " <td>";

        $billTotalresult =  billTotal($value['billNo'], $conn);
        if($billTotalresult->num_rows > 0){
  
          while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
            $discountValue = $row['discount']; 
            //echo $row['Total'];

            if( $discountValue<=0){

              $fullTotal = $row['Total']; 
              echo $fullTotal;

          }else{
      
              $fullTotal = $row['Total']; 
              $discountPrice = ($fullTotal*$discountValue)/100;
      
              $fullTotal = $fullTotal-$discountPrice;

              echo $fullTotal;
          
       
                 
      }

      
          }
        }
        echo " </td>
        <td>
        <button type='submit' name='printinvoice' class='btn btn-warning' value='".$value['billNo']."'>
        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z'/>
  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0'/>
</svg>
         </button>
        </td>
        
        
        
        ";
      
       echo " </tr>";
        
        $rangeTotal=$rangeTotal+$fullTotal;

      
        
      }
     
      echo " <div class='alert alert-warning ' role='alert'>
      Total :-  RS. ".$rangeTotal."
      </div>";   
 }elseif($_POST['reportType']==0){

  echo "<P class='fs-1'>Reload <P>";
  
    echo "<table id='myTablerelod' class='table table-striped table-dark'>
    <thead>
    <tr>
    <th >Bill No</th>
    <th >Reload Type</th>
    <th> Total Price </th>

  
    
    </tr>
    
    </thead>
    <tbody>
  ";
  $reloadTotal=0;
  foreach($reloadBillArray as $index =>$value){
    
    $reloadTotal=$reloadTotal+$value['itemAmount'];
  
    echo "<tr>
          <td>".$value['billNo']."</td>
          <td>".$value['itemName']."</td>
          <td>Rs. ".$value['itemAmount']."</td>
          </tr>

       
          ";

        }
        echo "
        </tbody>
        </table>";
        echo " <div class='alert alert-warning ' role='alert'>
        Total :- ".$reloadTotal."
      </div>";
      echo " <div >
      <button type='submit' name='reloadbillreportprint' class='btn btn-warning'>
      Print Bill Report
      </button>
    </div>";
       

   }elseif($_POST['reportType']=="Reload & Accesoriess Bill"){

    echo "<P class='fs-1'>".$_POST['reportType']." <P>";
    
      echo "<table id='myTable' class='table table-striped table-dark'>
      <thead>
      <tr>
      <th >Bill No</th>
      <th >Bill Date</th>
      <th> Total Price </th>
      <th></th>
    
      
      </tr>
      
      </thead>
      <tbody>
    ";
    $result = accessoriesReport($startDate,$endDate,$conn);
    
    foreach($billNOArray as $index =>$value){
      
    
      echo "<tr>
            <td>".$value['billNo']."</td>
            <td>".$value['date']."</td>";
            
            echo " <td>";
    
            $billTotalresult =  billTotal($value['billNo'], $conn);
            if($billTotalresult->num_rows > 0){
      
              while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
                $discountValue = $row['discount']; 
                //echo $row['Total'];
    
                if( $discountValue<=0){
    
                  $fullTotal = $row['Total']; 
                  echo $fullTotal;
              }else{
          
                  $fullTotal = $row['Total']; 
                  $discountPrice = ($fullTotal*$discountValue)/100;
          
                  $fullTotal = $fullTotal-$discountPrice;
    
                  echo $fullTotal;
              
           
                     
          }
    
              }
            }
            echo " </td>
            <td>
            <button type='submit' name='printinvoice' class='btn btn-warning' value='".$value['billNo']."'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
      <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z'/>
      <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0'/>
    </svg>
             </button>
            </td>
            
            
            
            ";
          
           echo " </tr>";
            
           
            
          }
         
    
     }else{
      echo "please Select report Type";
     }

}
?>

</tbody>
</table>

</div>


</div>

</form>


</body>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready( function () {
    $('#myTable').DataTable({

      "lengthMenu": [ 50,100,200]

    });

    $('#myTablerelod').DataTable({

"lengthMenu": [ 50,100,200]

});
  });
</script>
</html>

