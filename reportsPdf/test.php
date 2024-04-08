<?php 
require_once '../assets/vendor/autoload.php';
$servername = "localhost";
$username  = "root";
$password = "";
$dbname = "sdmulticarehouse";


$conn= new mysqli($servername,$username,$password,$dbname);

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

$startDate = "2024-03-21";
$endDate = "2024-04-21";

$billresult =accessoriesbill($startDate,$endDate,1,$conn);

  if($billresult->num_rows > 0){
    
    while($row = mysqli_fetch_array($billresult,MYSQLI_ASSOC)){
  
      $billNOArray[]  = array(
        'billNo' => $row['billNo'],
        'date' => $row['date']
    );
  
    }
  }

// Import Mpdf class
use Mpdf\Mpdf;

// Instantiate Mpdf object
$mpdf = new Mpdf();

// HTML content for PDF

$html= '<P> Accessories Bill <P>';
$html.= '<P> '.$startDate.' To '.$endDate.' <P>';

  $html.= '<head>
  <style>
    table {
      border-collapse: collapse;
    }
    table td,
    table th {
      border: 1px solid #000;
    }
  </style><table >
  <tr>
  <th >Bill No</th>
  <th >Bill Date</th>
  <th>Bill Item</th>
  <th>Item Qty</th>
  <th>Item Note</th>
  <th> Bill Total</th>
  <th></th>
  </tr>
 
  <tbody>
';


foreach($billNOArray as $index =>$value){
  
  $html.= '<tr >
        <td >'.$value['billNo'].'</td>
        <td>'.$value['date'].'</td>';
        

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
    $html .= '<td>'.$itemNames.'<br>'.'</td>';
      
    if($resultQty->num_rows > 0){
    
        while($row = mysqli_fetch_array($resultQty,MYSQLI_ASSOC)){
          
         $itemQty .= $row['itemQty'] . '<br>';
         
        }
      }
      
        $html.= '<td>'.$itemQty.'</td>';
        if($resultNote->num_rows > 0){
    
            while($row = mysqli_fetch_array($resultNote,MYSQLI_ASSOC)){
              
                $itemNote.= $row['note']. '<br>';
             
            }
          } 

          $html.= '<td>'. $itemNote.'</td>';

       

      
        $html.= "<td >fsdfsfs</td>";
       $html .=  " </tr>";
     
      }
      $html .=  "
      <tr>
      <th >Total</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      <td>Rs. gigiiyi</td> 
      <td></td> </tr>";
      $html.= "
      </tbody>";
$html.= "</table>";
     

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF to browser
$mpdf->Output();

?>