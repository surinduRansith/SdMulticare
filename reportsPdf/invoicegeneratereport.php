<?php 
require_once '../assets/vendor/autoload.php';
$servername = "localhost";
$username  = "sdMulticare";
$password = "sdmulti@123";
$dbname = "sdmulticarehouse";


$conn= new mysqli($servername,$username,$password,$dbname);

if(isset($_GET['billid'])){

$billID = intval($_GET['billid']);

function  dowonloadPDF($billID , $conn){

 
    $sql = "SELECT
    accessoriesitem.ItemNo,
   stock.ItemName,
   stock.SellingPrice,
   accessoriesitem.itemQty,
   accessoriesitem.note,
   accessoriesitem.discounttype,
   accessoriesitem.discount,
   (stock.SellingPrice * accessoriesitem.itemQty) AS subTotal,
   SUM(stock.SellingPrice * accessoriesitem.itemQty) OVER () AS Total
FROM
   accessoriesitem
JOIN
   stock ON accessoriesitem.ItemNo = stock.itemNo
WHERE
   accessoriesitem.billNo =$billID
GROUP BY
   accessoriesitem.ItemNo, stock.ItemName, stock.SellingPrice, accessoriesitem.itemQty,
   accessoriesitem.discount;";


    $result = mysqli_query($conn,$sql);

return $result;


}
}

function getCustomerDetails($billID,$conn){

  $sql = "SELECT c.`customerid`, c.`name`, c.`phonenumber`, cb.billNo
  FROM `customer` AS c
  INNER JOIN `customerbill` AS cb ON c.`customerid` = cb.`customerid`
  WHERE cb.billNo=$billID
  ";

$result = mysqli_query($conn,$sql);

return $result;



}
function billdate($billID,$conn){

  $sql ="SELECT `billNo`, `date` FROM `accessoriesbill` WHERE billNo=$billID";

  $result = mysqli_query($conn,$sql);

return $result;
}

$billdate =  billdate($billID,$conn);

$itemList = dowonloadPDF($billID , $conn);
$customerDetails =getCustomerDetails($billID,$conn);

$itemCount =0;
$fullTotal=0;
$discountValue = 0;


        $mobileNumber = "0705161216,0763030377";
        $Address = "No. 49/2 Robertgunawardhana Mawatha, Thalangama South Battaramulla.";
        $Email = "sdmulticare@gmail.com";
        $companyName ="SD Multicare House";
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
<h3> '.$Address.'</h3>
<h3> '.$Email.' </h3>
<h3>'.$mobileNumber.' </h3></td>
<td>'.$companyImage.'</td>
</tr>
<tr >
<td><h3>Bill Number :- '.$billID .'</h3>';

if($billdate->num_rows > 0){

  while($row = mysqli_fetch_array($billdate,MYSQLI_ASSOC)){

    $html.='<td><h3>Bill Date:- '.$row['date'].'</h3>';

  }
}



$html.='</tr>
<tr >
<td>';

if($customerDetails->num_rows > 0){

  while($row = mysqli_fetch_array($customerDetails,MYSQLI_ASSOC)){

    $html.='<h3>Customer Name :- '.$row['name'].'</h3></td>
    <td><h3>Phone Number :-  '.$row['phonenumber'].'</h3></td>';

  }
}
 
 $html.='
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
  <th class="tb2">ITEM NO/th>
  <th class="tb2">ITEM NAME</th>
  <th class="tb2">SELING PRICE</th>
  <th class="tb2"> NOTE</th>
  <th class="tb2">QTY</th>
  <th class="tb2"> SUB TOTAL</th>
  </tr>
 
  <tbody>
';

        //Reload
        if($itemList->num_rows > 0){

            while($row = mysqli_fetch_array($itemList,MYSQLI_ASSOC)){
       

                $html .=  '<tr class="tb2">
                            <td class="tb2" >'.$row['ItemNo'].'</td>
                            <td class="tb2" >'. $row['ItemName'].'</td>
                            <td class="tb2" >Rs.'.$row['SellingPrice'].'</td>
                            <td class="tb2" >'.$row['note'].'</td>
                            <td class="tb2" >'.$row['itemQty'].'</td>
                            <td class="tb2" >Rs.'.$row['subTotal'].'</td> </tr>';
                            $discountValue = $row['discount'] ;
                            
                            if($row['discounttype']=="presentage"){
                              if( $discountValue<=0){
                                $disctype=" ";
                                $discvaluetype=" ";
                                  $fullTotal = $row['Total']; 
                              }else{
                                 $disctype = "(%)";
                                  $fullTotal = $row['Total']; 
                                  $discountPrice = ($fullTotal*$discountValue)/100;
                                  
                                  $fullTotal = $fullTotal-$discountPrice;
                              }
                            }elseif($row['discounttype']=="cash"){
                              if( $discountValue<=0){
                                $disctype=" ";
                                $fullTotal = $row['Total']; 
                            }else{
                        
                              $disctype = "(Cash)";
                                $fullTotal = $row['Total']; 
                                $discvaluetype="RS. ";
                                
                                $fullTotal = $fullTotal-$discountValue;
                            }
  
  
                            }
                
                        }
                    }
                    $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Discount'.$disctype.'</td>
                    <td class="tb2">'. $discvaluetype.' '.$discountValue.'</td> </tr>';
                    $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Total</td>
                    <td class="tb2">Rs.'.$fullTotal.'</td> </tr>';
                    
                    $html.= '
                    </tbody>';
              $html.= "</table>";

              $html .=  ' </style><h2 style="text-align: center;"> Thank you Come Again ! </h2>';




  

    

   
   
   

      
// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

   



?>