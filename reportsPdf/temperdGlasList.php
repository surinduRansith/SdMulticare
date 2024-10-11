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
function getItemCountofsale($startDate,$endDate,$conn){

    $sql = "SELECT ab.billNo,ai.ItemNo,s.ItemName,ai.itemQty,ai.note,ab.date 
    FROM accessoriesitem ai 
    INNER JOIN accessoriesbill ab ON ai.billNo=ab.billNo
    INNER JOIN stock s ON ai.ItemNo = s.itemNo 
    where ab.date BETWEEN '".$startDate."' AND '".$endDate."' 
";
    
    $result = mysqli_query($conn,$sql);
    
    return $result;

 
    
}


    $companyName ="SD Multicare House Report";
    $reportType = "Note Include Item Report";
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
  <th >Bill No</th>
   <th >Item Name</th>
   <th >Item Qty</th>
   <th>Bill Note</th>
  </tr>
 
  <tbody>
';


        
      
        


        //Reload
        $itemCountresult = getItemCountofsale($startDate,$endDate,$conn);
   
        if($itemCountresult->num_rows > 0){
            
           
            
  
            while($row = mysqli_fetch_array($itemCountresult,MYSQLI_ASSOC)){
                if($row['note'] !="-"){
                $html .=  '<tr class="tb2">
                         
                            <td class="tb2" >'.$row['ItemNo'].'</td>
                            <td class="tb2" >'.$row['ItemName'].'</td>
                            <td class="tb2" >'.$row['itemQty'].'</td>
                             <td class="tb2" >'.$row['note'].'</td>
                         </tr> ';
                }
                         
    }
      }
      
      $html.= '
      </tbody>';
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>