
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

        function reloadBill($startDate,$endDate,$reportType,$conn){


            $sql = "SELECT accessoriesbill.billNo, reload.ItemName, reload.itemAmount,accessoriesbill.billtype,accessoriesbill.date 
            FROM reload,accessoriesbill WHERE  accessoriesbill.billNo = reload.billNo AND accessoriesbill.date BETWEEN '".$startDate."' AND '".$endDate."' AND accessoriesbill.billtype = ".$reportType."";
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
  <th class="tb2"> Bill Total</th>
  </tr>
 
  <tbody>
';

$rangeTotal=0;

        
      
        


        //Reload
        $reloadbillresult = reloadBill($startDate,$endDate,0,$conn);
   
        if($reloadbillresult->num_rows > 0){
  
            while($row = mysqli_fetch_array($reloadbillresult,MYSQLI_ASSOC)){

                $html .=  '<tr class="tb2">
                            <td class="tb2" >'.$row['billNo'].'</td>
                            <td class="tb2" >'.$row['date'].'</td>
                            <td class="tb2" >'.$row['ItemName'].'</td>
                            <td class="tb2" >'.$row['itemAmount'].'</td> </tr>';
                            $rangeTotal=$rangeTotal+$row['itemAmount'];
    }
      }
      $html .=  '
      <tr class="tb2">
        <td class="tb2" colspan ="3">Total</td>
      <td class="tb2">Rs.'.$rangeTotal.'</td> </tr>';
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>
