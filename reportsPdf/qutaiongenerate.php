<?php
require_once '../assets/vendor/autoload.php';



if (isset($_GET['itemNO'])) {
  $itemNoJson = urldecode($_GET['itemNO']);
  $itemNoArray = json_decode($itemNoJson, true);
  
  if (is_array($itemNoArray)) {
      // Process the array
      foreach ($itemNoArray as $item) {
        $cusotmerName = htmlspecialchars($item['customerName']);
        $customerNO=htmlspecialchars($item['customerNumber']);
        $quoteNumber =htmlspecialchars($item['quoteNumber']);
      }
  } else {
      echo "Failed to decode itemNO.";
  }
} else {
  echo "No item numbers found.";
}



      
        $mobileNumber = "0705161216,0763030377";
        $Address = "No. 49/2 Robertgunawardhana Mawatha, Thalangama South Battaramulla.";
        $Email = "sdmulticare@gmail.com";
        $companyName ="SD Multicare House";
        $companyImage = "<img src='../assets/Images/sdlogo.jpeg'  style='width:150px; height: 150px;'>";

// Import Mpdf class
use Mpdf\Mpdf;

// Instantiate Mpdf object
$mpdf = new Mpdf([
  'format' => 'A4',
  'margin_bottom' => 15, // Adjust as needed
]);

$mpdf->SetFooter('
<footer>
<div style="text-align: center; padding: 10px; background-color: #f1f1f1;">
    <p style="color:red">This quotation is valid for 7 days from the date of issue</p>
    <p>Thank you for considering our services!</p>
    <p>For any inquiries, please contact us.</p>
</div>
</footer>
');

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
<td><h3>Bill Number :- '.$quoteNumber .'</h3>';

 $html.='<td><h3>Date:- '.date('Y-m-d').'</h3>';

$html.='</tr>
<tr >
<td>';



    $html.='<h3>Customer Name :- '.$cusotmerName.'</h3></td>
    <td><h3>Phone Number :-  '.$customerNO.'</h3></td>';


 
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
  <th class="tb2">ITEM NO</th>
  <th class="tb2">ITEM NAME</th>
  <th class="tb2">SELING PRICE</th>
  <th class="tb2"> NOTE</th>
  <th class="tb2">QTY</th>
  <th class="tb2"> SUB TOTAL</th>
  </tr>
 
  <tbody>
';

if (is_array($itemNoArray)) {

  foreach ($itemNoArray as $item) {
                $html .=  '<tr class="tb2">
                            <td class="tb2" >'.htmlspecialchars($item['itemNo']).'</td>
                            <td class="tb2" >'.htmlspecialchars($item['itemName']).'</td>
                            <td class="tb2" >Rs.'.htmlspecialchars($item['sellingprice']).'</td>
                            <td class="tb2" >'.htmlspecialchars($item['note']).'</td>
                            <td class="tb2" >'.htmlspecialchars($item['itemqty']).'</td>
                            <td class="tb2" >Rs.'.htmlspecialchars($item['total']).'</td> </tr>';
  }} 

  $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Sub Total</td>
                    <td class="tb2">'. htmlspecialchars($item['subtotal']).'</td> </tr>';
                    $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Discount</td>
                    <td class="tb2">'. htmlspecialchars($item['discount']).'</td> </tr>';
                    $html .=  '
                    <tr class="tb2">
                      <td class="tb2" colspan ="5">Total</td>
                    <td class="tb2">Rs.'.htmlspecialchars($item['fulltotal']).'</td> </tr>';
                    
                    $html.= '
                    </tbody>';
              $html.= "</table>";

              $html .=  ' </style>';

     


  

    

   
   
   

      
// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

   



?>