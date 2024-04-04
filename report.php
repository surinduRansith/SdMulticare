<?php 

include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/select.php");

if(isset($_POST['printinvoice'])){
  
  $billID= $_POST['printinvoice'];

  $url = "/sd_multicare/reportsPdf/invoicegeneratereport.php?billid=$billID";
  echo "<script>window.open('$url', '_blank');</script>";
 
}

if(isset($_POST['reloadbillreportprint'])){

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate']; 

  $url = "/sd_multicare/reportsPdf/reloadgeneratereport.php?startdate=$startDate&enddate= $endDate";
  echo "<script>window.open('$url', '_blank');</script>";


}

if(isset($_POST['accessoriesbillreportprint'])){
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];

  $url = "/sd_multicare/reportsPdf/accessoriesreport.php?startdate=$startDate&enddate=$endDate";
  echo "<script>window.open('$url', '_blank');</script>";


}
if(isset($_POST['allbillreportprint'])){

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate']; 

  $url = "/sd_multicare/reportsPdf/allbillreport.php?startdate=$startDate&enddate= $endDate";
  echo "<script>window.open('$url', '_blank');</script>";


}
if(isset($_POST['Accesoriessitemreportprint'])){

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate']; 

  $url = "/sd_multicare/reportsPdf/accesoriessitemlist.php?startdate=$startDate&enddate= $endDate";
  echo "<script>window.open('$url', '_blank');</script>";


}
if(isset($_POST['printandOthersprint'])){

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate']; 

  $url = "/sd_multicare/reportsPdf/printothersreport.php?startdate=$startDate&enddate= $endDate";
  echo "<script>window.open('$url', '_blank');</script>";


}





$billNOArray = array();
$billNOArrayAll = array();
$reloadBillArray = array();
$accesoriesitemArray = array();
$printOthersArray = array();
$accesoriesamount=0;
$reportType;
$submited = "";
$inValid = "";
$errorEvent = "";
$reloadAmount=0;
$reloadAmountTotal = 0;
$printAmountTotal = 0;

if(isset($_POST['search'])){
  $reportType=$_POST['reportType'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate']; 

 if(!empty( $startDate) && !empty($endDate)){

    if($_POST['reportType']==1){

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
  }elseif($_POST['reportType']==2){

    $allBillresult =getAllBills($startDate,$endDate,$conn);
    if($allBillresult->num_rows > 0){
    
      while($row = mysqli_fetch_array($allBillresult,MYSQLI_ASSOC)){
    
        $billNOArrayAll []  = array(
          'billNo' => $row['billNo'],
          'date' => $row['date'],
          'billtype'=> $row['billtype']
      );
    
      }
    }


  }elseif($_POST['reportType']==3){

    $accesoriesitemresult =getItemCountofsale($startDate,$endDate,$conn);
    if($accesoriesitemresult->num_rows > 0){
    
      while($row = mysqli_fetch_array($accesoriesitemresult,MYSQLI_ASSOC)){
    
        $accesoriesitemArray []  = array(
          'ItemNo' => $row['ItemNo'],
          'itemName' => $row['itemName'],
          'date'=> $row['date'],
          'item_count'=> $row['item_count']
      );
    
      }
    }


  }elseif($_POST['reportType']==4){

    $printOtherResults =getprintData($startDate,$endDate,2,$conn);
    if($printOtherResults->num_rows > 0){
    
      while($row = mysqli_fetch_array($printOtherResults,MYSQLI_ASSOC)){
    
        $printOthersArray []  = array(
          'ItemNo' => $row['billNo'],
          'itemName' => $row['itemName'],
          'date'=> $row['date'],
          'amount'=> $row['Amount']
      );
    
    }
  } 
}
}else{
  $submited = true;
    $inValid = true;
    $errorEvent = "Please Select Report type And Dates";
}  
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Invoice Report</title>
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
  <input type="date" class="form-control"  name="startDate" value="<?php echo $startDate; ?>">
</div>
</div>
<div class="col-3">
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">End Date</span>
  <input type="date" class="form-control"  name="endDate" value="<?php echo $endDate; ?>">
</div>
</div>
<div class="col-4">
<select class="form-select form-select mb-3" name="reportType" aria-label="Large select example">
  <option selected>Select Report Types</option>
  <option value="0">Reload</option>
  <option value="1">Accesoriess Bill</option>
  <option value="2">All Item Bill</option>
  <option value="3">Accesoriess Items</option>
  <option value="4">Print & Others</option>
</select>
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
 if(!empty( $startDate)&&!empty($endDate)){

  if($_POST['reportType']==1){
echo "<P class='fs-1'> Accessories Bill <P>";
echo "<P class='fs-3'> ".$startDate." To ".$endDate." <P>";
echo " <div >
<button type='submit' name='accessoriesbillreportprint' class='btn btn-warning'>
Save Report
</button>
</div>";
  echo "<table id='myTable' class='table table-striped table-dark'>
  <thead>
  <tr>
  <th >Bill No</th>
  <th >Bill Date</th>
  <th>Bill Item</th>
  <th>Item Qty</th>
  <th>Item Note</th>
  <th> Bill Total</th>
  <th></th>
  </tr>
  </thead>
  <tbody>
";

foreach($billNOArray as $index =>$value){
  $itemBillNo = $value['billNo'];
  echo "<tr >
        <td >".$value['billNo']."</td>
        <td>".$value['date']."</td>";
        echo "<td>";
        
        $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
  if($resultitems->num_rows > 0){
    
    while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
      
      echo $row['itemName']."<br>";
     
    }
  }
  
        echo "</td>
        ";
        echo "<td>";
        
        $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
  if($resultitems->num_rows > 0){
    
    while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
      
      echo $row['itemQty']."<br>";
     
    }
  }
  
        echo "</td>
        ";
        echo "<td>";
        
        $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
  if($resultitems->num_rows > 0){
    
    while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
      
      echo $row['note']."<br>";
     
    }
  }
  
        echo "</td>
        ";
        
        echo " <td>";

        $billTotalresult =  billTotal($value['billNo'], $conn);
        if($billTotalresult->num_rows > 0){
  
          while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
            $discountValue = $row['discount']; 
  
            if( $discountValue<=0){

              $fullTotal = $row['Total']; 
              echo "RS. ".$fullTotal;

          }else{
      
              $fullTotal = $row['Total']; 
              $discountPrice = ($fullTotal*$discountValue)/100;
      
              $fullTotal = $fullTotal-$discountPrice;

              echo "RS. ".$fullTotal;         
      }
          }
        }
        echo " </td>
        <td >
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
      echo "
      <tr>
      <th >Total</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      <td>Rs. ".$rangeTotal."</td> 
      <td></td> </tr>";
      echo "
      </tbody>
      </table>";
     
 }elseif($_POST['reportType']==0){

  echo "<P class='fs-1'>Reload <P>";
  echo "<P class='fs-3'> ".$startDate." To ".$endDate." <P>";
  echo " <div >
  <button type='submit' name='reloadbillreportprint' class='btn btn-warning'>
  Save Report
  </button>
</div>";
    echo "<table id='myTablerelod' class='table table-striped table-dark'>
    <thead>
    <tr>
    <th >Bill No</th>
    <th >Reload Type</th>
    <th> Bill Total</th>
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
          </tr>";}
        echo "
        <tr>
        <th >Total</th>
        <th></th>
        <td>Rs. ".$reloadTotal."</td> </tr>";
        echo "
        </tbody>
        </table>";
    
   }elseif($_POST['reportType']=="2"){

    echo "<P class='fs-1'> All Items Bill Report <P>";
    echo "<P class='fs-3'> ".$startDate." To ".$endDate." <P>";
    echo " <div >
      <button type='submit' name='allbillreportprint' class='btn btn-warning'>
      Save Report
      </button>
    </div>";
      echo "<table id='myTable' class='table table-striped table-dark'>
      <thead>
      <tr>
      <th >Bill No</th>
      <th >Bill Date</th>
      <th>Bill Type </th>
      <th>Bill Items</th>
      <th>Bill Qty</th>
      <th>Bill Note </th>
      <th> Total Price </th>
      </tr>
      </thead>
      <tbody>
    ";
    $AllBillTotal =0;
    foreach($billNOArrayAll as $index =>$value){


      $itemBillNo=$value['billNo'];
      $reloadbillresult = reloadBillitems($startDate,$endDate, $itemBillNo,0,$conn);
      $getprintresult = getprintDatalist($startDate,$endDate,$itemBillNo,2,$conn);
      $resultreload = reloadBill($startDate,$endDate,0,$conn);
      $resultPrint = getprintData($startDate,$endDate,2,$conn);
      $billTotalresult =  billTotal($value['billNo'], $conn);
      echo "<tr>
            <td>".$value['billNo']."</td>
            <td>".$value['date']."</td>
            <td>";
            $billType = $value['billtype'];
            if($billType == 0){

              echo "Reload";
            }elseif($billType == 1){


              echo "Accessories";
            }elseif($billType == 2){

              echo "Print & Others";
            }

           echo  "</td>";
           if($billType == 1){
           echo "<td>";
           $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
     if($resultitems->num_rows > 0){
       
       while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
         
         echo $row['itemName']."<br>";
        
       }
     }
     
           echo "</td>
           ";
           echo "<td>";
           
           $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
     if($resultitems->num_rows > 0){
       
       while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
         
         echo $row['itemQty']."<br>";
        
       }
     }
     
           echo "</td>
           ";
           echo "<td>";
           
           $resultitems = getitems($startDate,$endDate,$itemBillNo,$conn);
     if($resultitems->num_rows > 0){
       
       while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){
         
         echo $row['note']."<br>";
        
       }
     }
     echo "</td>
     ";
    }if($billType == 0){

      echo "<td>";
     
      if($reloadbillresult->num_rows > 0){
  
        while($row = mysqli_fetch_array($reloadbillresult,MYSQLI_ASSOC)){

          echo $row['ItemName']; 
          
        }}

      echo "</td>";
      echo "<td>";
      echo "-";
      
    

      echo "</td> ";
     
      echo "<td>";
      echo "-";
echo "</td>";

    }elseif($billType==2){
      echo "<td>";
     
if($getprintresult ->num_rows > 0){
  
  while($row = mysqli_fetch_array($getprintresult ,MYSQLI_ASSOC)){
    
    echo $row['itemName'];

      echo "</td>";

      echo "<td>";
      echo "-";
      echo "</td>";

      echo "<td>";
      
      echo $row['note'];
echo "</td>";
}
}
    }
            echo " <td>";
if($billType == 1){
  if($billTotalresult->num_rows > 0){
        
    while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
      $discountValue = $row['discount']; 
      //echo $row['Total'];

      if( $discountValue<=0){

        $fullTotal = $row['Total']; 
       echo "RS. ".$fullTotal;

    }else{

        $fullTotal = $row['Total']; 
        $discountPrice = ($fullTotal*$discountValue)/100;

        $fullTotal = $fullTotal-$discountPrice;

        echo "RS. ".$fullTotal;         
}

        
$accesoriesamount = $accesoriesamount+ $fullTotal;

    }
  }






}elseif($billType == 0){

  if($resultreload->num_rows > 0){
      
    while($row = mysqli_fetch_array($resultreload,MYSQLI_ASSOC)){

        if( $value['billNo'] == $row['billNo']){
            $reloadAmount = $row['itemAmount'];
         echo "RS. ".$reloadAmount;


         $reloadAmountTotal = $reloadAmountTotal+$reloadAmount;
        }
        
        

       
    
    }}
}elseif($billType == 2){
  if($resultPrint->num_rows > 0){
      
    while($row = mysqli_fetch_array($resultPrint,MYSQLI_ASSOC)){

        if( $value['billNo'] == $row['billNo']){
            $printAmount = $row['Amount'];
         echo "RS. ".$printAmount;


         $printAmountTotal = $printAmountTotal+$printAmount;
         
        }
        
        

       
    
    }}



}
            

           
            echo " </td>"; 
           echo " </tr>";
           
          //echo $fullTotalAll."<br>";
        // echo $reloadAmountAll."<br>";
           
          }
          $AllBillTotal = $accesoriesamount+$reloadAmountTotal+$printAmountTotal;

          echo "
        <tr>
        <th >Total</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>Rs. ".$AllBillTotal."</td> </tr>";
        echo "
        </tbody>
        </table>";
        
     }elseif($_POST['reportType']=="3"){

      echo "<P class='fs-1'>  Accesoriess Item <P>";
      echo "<P class='fs-3'> ".$startDate." To ".$endDate." <P>";
      echo " <div >
        <button type='submit' name='Accesoriessitemreportprint' class='btn btn-warning'>
        Save Report
        </button>
      </div>";
        echo "<table id='myTable' class='table table-striped table-dark'>
        <thead>
        <tr>
        <th >Item Number</th>
        <th >Item Name</th>
        <th>Item Count </th>
        </tr>
        </thead>
        <tbody>
      ";
    $itemTotal = 0;
      foreach($accesoriesitemArray as $index =>$value){

        echo "<tr>
              <td>".$value['ItemNo']."</td>
              <td>".$value['itemName']."</td>
              <td>".$value['item_count']."</td>";
             echo " </tr>";

             $itemTotal = $itemTotal+$value['item_count'];
            }
          
  
            echo "
          <tr>
          <th >Total</th>
          <th></th>
          <td>".$itemTotal." </td> </tr>";
          echo "
          </tbody>
          </table>";
          } elseif($_POST['reportType']=="4"){

            echo "<P class='fs-1'>  Print & Others Report <P>";
            echo "<P class='fs-3'> ".$startDate." To ".$endDate." <P>";
            echo " <div >
              <button type='submit' name='printandOthersprint' class='btn btn-warning'>
              Save Report
              </button>
            </div>";
              echo "<table id='myTable' class='table table-striped table-dark'>
              <thead>
              <tr>
              <th >Bill No</th>
              <th >Item Name</th>
              <th>Date</th>
              <th>Bill Note</th>
              <th>Amount</th>
              </tr>
              </thead>
              <tbody>
            ";
          $itemTotal = 0;
            foreach($printOthersArray as $index =>$value){

              $itemBillNo=$value['ItemNo'];
              $getprintresult = getprintDatalist($startDate,$endDate,$itemBillNo,2,$conn);
              echo "<tr>
                    <td>".$value['ItemNo']."</td>
                    <td>".$value['itemName']."</td>
                    <td>".$value['date']."</td>";
                    echo " <td>";
                    if($getprintresult ->num_rows > 0){
  
                      while($row = mysqli_fetch_array($getprintresult ,MYSQLI_ASSOC)){
                        
                        echo $row['note'];
                      }}
                    echo "</td>";
                   echo " <td>".$value['amount']."</td>";
                   echo " </tr>";
      
                   $itemTotal = $itemTotal+$value['amount'];
                  }
                
        
                  echo "
                <tr>
                <th >Total</th>
                <th></th>
                <th></th>
                <th></th>
                <td>".$itemTotal." </td> </tr>";
                echo "
                </tbody>
                </table>";
                }   
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
<?php
        if ($submited) {
          if ($inValid) {
        ?>
            <script>
              swal({
                title: "<?php echo $errorEvent ?>",
                // text: "You clicked the button!",
                icon: "warning",
                button: "ok",
              });
            </script>
          <?php } else { ?>
            <script>
              swal({
                title: "<?php echo $successEvent ?>",
                // text: "You clicked the button!",
                icon: "success",
                button: "ok",
              });
            </script>
        <?php }
        } ?>
