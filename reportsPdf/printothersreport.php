<?php
require_once '../assets/vendor/autoload.php';
$servername = "localhost";
$username  = "sdMulticare";
$password = "sdmulti@123";
$dbname = "sdmulticarehouse";


$conn= new mysqli($servername,$username,$password,$dbname);

if (isset($_GET['startdate']) && isset($_GET['enddate'])) {

    $startDate = $_GET['startdate'];
    $endDate = $_GET['enddate'];
   
}


        function getprintData($startDate,$endDate,$itemType,$conn){

            $sql = "SELECT  po.billNo, po.itemName, po.Amount,  po.note,ab.date,ab.billtype FROM print_others po
            INNER JOIN  accessoriesbill ab 
            ON ab.billNo=po.billNo
            WHERE ab.date BETWEEN '".$startDate."' AND '".$endDate."' AND ab.billtype=$itemType;";
            
            $result = mysqli_query($conn,$sql);
            
            return $result;
        
        
        
        
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
  <th class="tb2">Bill Item Note</th>
  <th class="tb2"> Bill Total</th>
  </tr>
 
  <tbody>
';

$rangeTotal=0;


$getprintresult = getprintData($startDate,$endDate,2,$conn);
  if($getprintresult->num_rows>0){
    while($row = mysqli_fetch_array($getprintresult,MYSQLI_ASSOC)){
  $html.= '<tr class="tb2" >
        <td class="tb2">'.$row['billNo'].'</td>
        <td class="tb2">'.$row['date'].'</td>
        <td class="tb2">'.$row['itemName'].'</td>
        <td class="tb2">'.$row['note'].'</td>
        <td class="tb2">Rs.'.$row['Amount'].'</td>';

        $rangeTotal= $rangeTotal+$row['Amount'];
    }
  }
      $html .=  '
      <tr class="tb2">
        <td class="tb2" colspan ="4">Total</td>
      <td class="tb2">Rs.'.$rangeTotal.'</td> </tr>';
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>