
<?php
require_once '../assets/vendor/autoload.php';
$servername = "localhost";
$username  = "root";
$password = "";
$dbname = "sdmulticarehouse";


$conn= new mysqli($servername,$username,$password,$dbname);

if (isset($_GET['startdate']) && isset($_GET['enddate'])) {

    $startDate = $_GET['startdate'];
    $endDate = $_GET['enddate'];
   
}



function  billTotal($billID , $conn){

 
  $sql = "SELECT
  accessoriesitem.billNo,
      accessoriesitem.ItemNo,
     accessoriesitem.itemName,
     stock.SellingPrice,
     accessoriesitem.itemQty,
     accessoriesitem.discount,
     accessoriesbill.date,
     (stock.SellingPrice * accessoriesitem.itemQty) AS subTotal,
     SUM(stock.SellingPrice * accessoriesitem.itemQty) OVER () AS Total
  FROM
     accessoriesitem,accessoriesbill,stock
  
  WHERE
  
  accessoriesitem.ItemNo = stock.itemNo and accessoriesbill.billNo = accessoriesitem.billNo  AND
accessoriesitem.billNo=$billID
    
  GROUP BY
     accessoriesitem.ItemNo, accessoriesitem.itemName, stock.SellingPrice, accessoriesitem.itemQty,
     accessoriesitem.discount LIMIT 1;";

  $result = mysqli_query($conn,$sql);

return $result;

}



    function getitems($startDate,$endDate,$billID,$conn){

        $sql = "SELECT ab.billNo,ai.itemName,ai.itemQty,ai.note,ab.date FROM accessoriesitem ai 
        INNER JOIN accessoriesbill ab ON ai.billNo=ab.billNo
        WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billNo = $billID;";
        
        $result = mysqli_query($conn,$sql);
            
        return $result;
        
        
        }
        function getAllBills($startDate,$endDate,$conn){

            $sql = "SELECT *
            FROM `accessoriesbill` 
            WHERE `date` BETWEEN '".$startDate."' AND '".$endDate."';";
            
            $result = mysqli_query($conn,$sql);
            
            return $result;
        
        
        
        
        }


        function getprintData($startDate,$endDate,$itemType,$conn){

            $sql = "SELECT  po.billNo, po.itemName, po.Amount,  po.note,ab.date,ab.billtype FROM print_others po
            INNER JOIN  accessoriesbill ab 
            ON ab.billNo=po.billNo
            WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billtype=$itemType;";
            
            $result = mysqli_query($conn,$sql);
            
            return $result;
        
        
        
        
        }
        function getprintDatalist($startDate,$endDate,$billNo,$itemType,$conn){

          $sql = "SELECT  po.billNo, po.itemName, po.Amount,po.note, ab.date,ab.billtype FROM print_others po
          INNER JOIN  accessoriesbill ab 
          ON ab.billNo=po.billNo
          WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billtype=$itemType AND po.billNo=$billNo ;";
          
          $result = mysqli_query($conn,$sql);
          
          return $result;
      
      
      
      
      }
        function reloadBill($startDate,$endDate,$reportType,$conn){


            $sql = "SELECT accessoriesbill.billNo, reload.ItemName, reload.itemAmount,accessoriesbill.billtype,accessoriesbill.date 
            FROM reload,accessoriesbill WHERE  accessoriesbill.billNo = reload.billNo AND accessoriesbill.date BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billtype = ".$reportType."";
            $result = mysqli_query($conn,$sql);
        
            return $result;
        
        }
        function reloadBillitems($startDate,$endDate,$billNo,$reportType,$conn){


          $sql = "SELECT accessoriesbill.billNo, reload.ItemName, reload.itemAmount,accessoriesbill.billtype,accessoriesbill.date 
          FROM reload,accessoriesbill WHERE  accessoriesbill.billNo = reload.billNo AND accessoriesbill.date BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billtype = ".$reportType." AND accessoriesbill.billNo=$billNo ";
          $result = mysqli_query($conn,$sql);
      
          return $result;
      
      }

$billNOArrayAll = array();


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
    $companyName ="SD Multicare House Report";
    $reportType = "All Item Report";
    $companyImage = "<img src='../assets/Images/sdlogo.jpeg'  style='width:150px; height: 150px;'>";

// Import Mpdf class
use Mpdf\Mpdf;

// Instantiate Mpdf object
$mpdf = new Mpdf();

// HTML content for PDF
$html= '<style>
.tb1 {
  border-style: none;
}
</style><table class="tb1">
<tr >
<td><h1> '.$companyName.'</h1>
<h2> '.$reportType.'</h2>
<h3> Start Date :- '.$startDate.' </h3>
<h3>End Date    :-  '.$endDate.' </h3></td>
<td>'.$companyImage.'</td>
</tr>
</table><br><br>';


  $html.= '<head>
  <style>
  .tb2{
    border:1px solid black;
    border-collapse: collapse;
    text-align: center;
    
  }
  th{
    height: 50px;
    
    background-color: rgba(150, 212, 212, 0.4);
  }
  table{
    width: 120%;
  }
  th, td {
    padding: 12px;
    text-align: left;
  }
  
  </style><table class="tb2" >
  <tr class="tb2">
  <th class="tb2">Bill No</th>
  <th class="tb2">Bill Date</th>
  <th class="tb2">Bill Type</th>
  <th class="tb2">Bill Item</th>
  <th class="tb2">Bill Qty</th>
  <th class="tb2">Item Note</th>
  <th class="tb2"> Bill Total</th>
  </tr>
 
  <tbody>
';

$rangeTotal=0;
$reloadTotal = 0;
$printOthersTotal = 0;
$accesoriesamount =0;
foreach($billNOArrayAll as $index =>$value){
  
  $html.= '<tr class="tb2" >
        <td class="tb2">'.$value['billNo'].'</td>
        <td class="tb2">'.$value['date'].'</td>';
        
        $billType = $value['billtype'];
        if($billType == 0){

          $html.='<td class="tb2">Reload</td>';
        }elseif($billType == 1){

            $html.='<td class="tb2">Accessories</td>';

        }elseif($billType == 2){
            $html.='<td class="tb2">Print & Others</td>';

        }

        
      
        

        $resultitems = getitems($startDate,$endDate,$value['billNo'],$conn);
        //accesories
        $resultQty = getitems($startDate,$endDate,$value['billNo'],$conn);
        $resultNote = getitems($startDate,$endDate,$value['billNo'],$conn);
        //printOthers
        $getprintresult = getprintData($startDate,$endDate,2,$conn);
        $getprintresultnote =  getprintDatalist($startDate,$endDate,$value['billNo'],2,$conn);
        $getprintresultamount =  getprintDatalist($startDate,$endDate,$value['billNo'],2,$conn);
        //Reload
        $reloadbillresult = reloadBill($startDate,$endDate,0,$conn);
        $reloadbillresultbillamount = reloadBillitems($startDate,$endDate,$value['billNo'],0,$conn);

        $itemNames = '';
        $itemQty = '';
        $itemNote='';

        if($billType == 0){

        if($reloadbillresult->num_rows > 0){
  
            while($row = mysqli_fetch_array($reloadbillresult,MYSQLI_ASSOC)){
    
             

                $itemNames = $row['ItemName'];

           
              
            }}
        }elseif($billType == 1){
        if($resultitems->num_rows > 0){
          
          while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){

            if($value['billNo']==$row['billNo']){

                $itemNames .= $row['itemName'] . '<br>';

            }else{

                $itemNames="";
            }
            
            
        }
    }
}elseif($billType == 2){

    if($getprintresult ->num_rows > 0){
  
        while($row = mysqli_fetch_array($getprintresult ,MYSQLI_ASSOC)){

            $itemNames = $row['itemName'];

        }
    }
}
    $html .= '<td class="tb2">'.$itemNames.'<br>'.'</td>';


    if($billType == 0){

        $itemQty ="-";

    }elseif($billType == 1){
      
    if($resultQty->num_rows > 0){
    
        while($row = mysqli_fetch_array($resultQty,MYSQLI_ASSOC)){
          
         $itemQty .= $row['itemQty'] . '<br>';
         
        }
      }
    }elseif($billType == 2){

        $itemQty ="-";
    }
      
        $html.= '<td class="tb2">'.$itemQty.'</td>';

        if($billType == 0){

            $itemNote ="-";

        }elseif($billType == 1){

        if($resultNote->num_rows > 0){
    
            while($row = mysqli_fetch_array($resultNote,MYSQLI_ASSOC)){
              
                $itemNote.= $row['note']. '<br>';
             
            }
          } 
          
        }elseif($billType == 2){
            if($getprintresultnote ->num_rows > 0){
  
                while($row = mysqli_fetch_array($getprintresultnote ,MYSQLI_ASSOC)){

                    if($value['billNo']==$row['billNo']){

                        $itemNote =$row['note'];
        
                    }else{
        
                        $itemNote="-";
                    }
                    
                }
            }

        }
        $html.= '<td class="tb2">'. $itemNote.'</td>';


          if($billType == 0){
            if($reloadbillresultbillamount ->num_rows > 0){
  
                while($row = mysqli_fetch_array($reloadbillresultbillamount ,MYSQLI_ASSOC)){
        
                 
    
                    $reloadtotal = $row['itemAmount'];
    
                    
                    $reloadTotal=$reloadTotal+$reloadtotal;
                  
                }
              }
                $html.= '<td class="tb2">'. 'RS.'.$reloadtotal.'</td>';


          }elseif($billType == 1){

          $billTotalresult =  billTotal($value['billNo'], $conn);
          if($billTotalresult->num_rows > 0){
    
            while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
              $discountValue = $row['discount']; 
    
              if( $discountValue<=0){
  
                $fullTotal = $row['Total']; 
                echo "RS. ".$fullTotal;
                $html.= '<td class="tb2">'. 'RS.'.$fullTotal.'</td>';
  
            }else{
        
                $fullTotal = $row['Total']; 
                $discountPrice = ($fullTotal*$discountValue)/100;
        
                $fullTotal = $fullTotal-$discountPrice;
  
                $html.= '<td class="tb2">'. 'RS.'.$fullTotal.'</td>';        
        }
        $accesoriesamount = $accesoriesamount+ $fullTotal;
            }
          }

        }elseif($billType ==2){

            if($getprintresultamount ->num_rows > 0){
  
                while($row = mysqli_fetch_array($getprintresultamount ,MYSQLI_ASSOC)){

            $printAmount =$row['Amount'];
            $printOthersTotal=$printOthersTotal+$printAmount;

          }
        }
        
        $html.= '<td class="tb2">'. 'RS.'. $printAmount.'</td>';
      
    }
      
       $html .=  " </tr>";
       //$rangeTotal=$rangeTotal+$fullTotal;
      }
      $rangeTotal=$accesoriesamount+$printOthersTotal+$reloadTotal;
      $html .=  '
      <tr class="tb2">
        <td class="tb2" colspan ="6">Total</td>
      <td class="tb2">Rs.'.$rangeTotal.'</td> </tr>';
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>
