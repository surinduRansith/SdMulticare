
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
function getItemCountofsale($startDate,$endDate,$conn){

    $sql = "SELECT ai.ItemNo,ai.itemName,ab.date, sum(ai.itemQty) AS item_count
FROM accessoriesbill ab
INNER JOIN accessoriesitem ai ON ab.billNo = ai.billNo
where ab.date BETWEEN '".$startDate."' AND '".$endDate."'
GROUP BY ai.ItemNo 
";
    
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
  <th class="tb2">Item No</th>
  <th class="tb2">Item Name</th>
  <th class="tb2">Item Count</th>
  </tr>
 
  <tbody>
';

$rangeTotal=0;

        
      
        


        //Reload
        $itemCountresult = getItemCountofsale($startDate,$endDate,$conn);
   
        if($itemCountresult->num_rows > 0){
  
            while($row = mysqli_fetch_array($itemCountresult,MYSQLI_ASSOC)){

                $html .=  '<tr class="tb2">
                            <td class="tb2" >'.$row['ItemNo'].'</td>
                            <td class="tb2" >'.$row['itemName'].'</td>
                            <td class="tb2" >'.$row['item_count'].'</td>
                         </tr>';
                            $rangeTotal=$rangeTotal+$row['item_count'];
    }
      }
      $html .=  '
      <tr class="tb2">
        <td class="tb2" colspan ="2">Total</td>
      <td class="tb2">'.$rangeTotal.'</td> </tr>';
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>
