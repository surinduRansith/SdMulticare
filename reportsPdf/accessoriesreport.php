
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

function accessoriesbill($startDate,$endDate,$reportType,$conn){

    $sql = "SELECT *
    FROM `accessoriesbill` 
    WHERE `date` BETWEEN '".$startDate."' AND '".$endDate."' AND billtype = ".$reportType." ;";
    
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

$billNOArray = array();


$billresult =accessoriesbill($startDate,$endDate,1,$conn);

  if($billresult->num_rows > 0){
    
    while($row = mysqli_fetch_array($billresult,MYSQLI_ASSOC)){
  
      $billNOArray[]  = array(
        'billNo' => $row['billNo'],
        'date' => $row['date']
    );
  
    }
  }
  $companyName ="SD Multicare House Report";
  $reportType = "Accessories Bill";
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
<td><h1> '.$companyName.' </h1>
<h2> '.$reportType .' </h2>
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
    padding: 15px;
    text-align: left;
  }
  
  </style><table class="tb2" >
  <tr class="tb2">
  <th class="tb2">Bill No</th>
  <th class="tb2">Bill Date</th>
  <th class="tb2">Bill Item</th>
  <th class="tb2">Item Qty</th>
  <th class="tb2">Item Note</th>
  <th class="tb2"> Bill Total</th>
  </tr>
 
  <tbody>
';

$rangeTotal=0;
foreach($billNOArray as $index =>$value){
  
  $html.= '<tr class="tb2" >
        <td class="tb2">'.$value['billNo'].'</td>
        <td class="tb2">'.$value['date'].'</td>';
        

        $resultitems = getitems($startDate,$endDate,$value['billNo'],$conn);
        $resultQty = getitems($startDate,$endDate,$value['billNo'],$conn);
        $resultNote = getitems($startDate,$endDate,$value['billNo'],$conn);

        $itemNames = '';
        $itemQty = '';
        $itemNote='';
        if($resultitems->num_rows > 0){
          
          while($row = mysqli_fetch_array($resultitems,MYSQLI_ASSOC)){

            if($value['billNo']==$row['billNo']){

                $itemNames .= $row['itemName'] . '<br>';

            }else{

                $itemNames="";
            }
            
            
        }
    }
    $html .= '<td class="tb2">'.$itemNames.'<br>'.'</td>';
      
    if($resultQty->num_rows > 0){
    
        while($row = mysqli_fetch_array($resultQty,MYSQLI_ASSOC)){
          
         $itemQty .= $row['itemQty'] . '<br>';
         
        }
      }
      
        $html.= '<td class="tb2">'.$itemQty.'</td>';
        if($resultNote->num_rows > 0){
    
            while($row = mysqli_fetch_array($resultNote,MYSQLI_ASSOC)){
              
                $itemNote.= $row['note']. '<br>';
             
            }
          } 

          $html.= '<td class="tb2">'. $itemNote.'</td>';

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
            }
          }

      
       $html .=  " </tr>";
       $rangeTotal=$rangeTotal+$fullTotal;
      }
      $html .=  '
      <tr class="tb2">
        <td class="tb2" colspan ="5">Total</td>
      <td class="tb2">Rs.'.$rangeTotal.'</td> </tr>';
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>
