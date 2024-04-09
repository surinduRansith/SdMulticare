<?php 
require_once '../assets/vendor/autoload.php';
$servername = "localhost";
$username  = "root";
$password = "";
$dbname = "sdmulticarehouse";


$conn= new mysqli($servername,$username,$password,$dbname);

if(isset($_GET['billid'])){

$billID = intval($_GET['billid']);

function  dowonloadPDF($billID , $conn){

 
    $sql = "SELECT
    accessoriesitem.ItemNo,
   accessoriesitem.itemName,
   stock.SellingPrice,
   accessoriesitem.itemQty,
   accessoriesitem.note,
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
   accessoriesitem.ItemNo, accessoriesitem.itemName, stock.SellingPrice, accessoriesitem.itemQty,
   accessoriesitem.discount;";


    $result = mysqli_query($conn,$sql);

return $result;


}
}

$itemList = dowonloadPDF($billID , $conn);

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
<td><h3>Bill Number :- '.$billID .'</h3>
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
                            <td class="tb2" >'. $row['itemName'].'</td>
                            <td class="tb2" >Rs.'.$row['SellingPrice'].'</td>
                            <td class="tb2" >'.$row['note'].'</td>
                            <td class="tb2" >'.$row['itemQty'].'</td>
                            <td class="tb2" >Rs.'.$row['subTotal'].'</td> </tr>';
                            $discountValue = $row['discount'] ;
                            
                            if( $discountValue<=0){
                        
                                $fullTotal = $row['Total']; 
                            }else{
                        
                                $fullTotal = $row['Total']; 
                                $discountPrice = ($fullTotal*$discountValue)/100;
                                
                                $fullTotal = $fullTotal-$discountPrice;
                            }

                
                        }
                    }
                    $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Discount(%)</td>
                    <td class="tb2">'.$discountValue.'</td> </tr>';
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