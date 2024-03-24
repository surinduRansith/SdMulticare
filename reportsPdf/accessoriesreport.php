
<?php
require('../assets/fpdf186/fpdf.php');
require('../dbconnect/dbconnect.php');
require('../dbconnect/queris/select.php');


if (isset($_GET['startdate']) && isset($_GET['enddate'])) {

    $startDate = $_GET['startdate'];
        $endDate = $_GET['enddate'];
$result = accessoriesbill($startDate,$endDate,1,$conn);





    
}
$itemCount=0;
$total = 0;
class PDF extends FPDF
{
// Page header
function Header()
    {
        // $startDate = "2024-03-23";
        // $endDate = "2024-03-24";
        
        $startDate = $_GET['startdate'];
        $endDate = $_GET['enddate'];
       
        // Logo
        $Title = "SD Multicare House Report";
        $this->Image('../assets/fpdf186/images/sdlogo.jpeg',170,6,30,);
        // Arial bold 15
        $this->SetFont('Arial','B',25);
        // Move to the right
        
        // Title
        
        $this->Cell(30,10,$Title,0,0,'L');
        $this->Ln(12);
        $this->SetFont('Arial','B',20);
        $this->Cell(30,10,'Accessories Report',0,0,'L');
    
      
        $this->SetFont('Arial','B',12);
        // Line break
        $this->Ln(7);
        
        $this->Ln(5);
        $this->Cell(0,10,'Start Date  :-  '.$startDate,0,0,'L');
        $this->Ln(5);
        $this->Cell(0,10,'End Date   :- '.$endDate,0,0,'L');
        $this->Ln(30);
    }
    
    // Page footer
    function Footer()
    {
       
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        
        
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    


    

}

$pdf = new PDF();
    
    $pdf->AddPage();
    $pdf->Ln(20);
    $pdf->SetFont('Arial','B',20);

    $pdf->SetXY(80,50);

 

    $pdf->SetFont('Arial','B',11);
    
    $pdf->SetXY(1,50);


    

    
    
    $pdf->SetFont('Arial','B',11);
    
    $pdf->Ln(15);
    $width_cell=array(15,50,50,50);
    $pdf->SetFillColor(193,229,252); 
    
    // Header starts /// 
    $pdf->Cell($width_cell[0],10,'',1,0,'C',true); 
    $pdf->Cell($width_cell[1],10,'BILL NO',1,0,'C',true); 
    $pdf->Cell($width_cell[2],10,'BILL DATE',1,0,'C',true); 
    $pdf->Cell($width_cell[3],10,'BILL TOTAL',1,1,'C',true);

    //// header is over ///////
    
    
    
    $pdf->SetFont('Arial','',10);

 
    if($result->num_rows > 0){

        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

            $billTotalresult =  billTotal($row['billNo'], $conn);
           


            $itemCount++;
    $pdf->Cell($width_cell[0],10,$itemCount,1,0,'C',false); // First column of row 1 
    $pdf->Cell($width_cell[1],10,$row['billNo'],1,0,'C',false); // Second column of row 1 
    $pdf->Cell($width_cell[2],10,$row['date'],1,0,'C',false); // Third column of row 1 

    if($billTotalresult->num_rows > 0){
        
        while($row = mysqli_fetch_array($billTotalresult,MYSQLI_ASSOC)){
          $discountValue = $row['discount']; 
          //echo $row['Total'];
  
          if( $discountValue<=0){
  
            $fullTotal = $row['Total']; 
           // echo $fullTotal;
  
        }else{
    
            $fullTotal = $row['Total']; 
            $discountPrice = ($fullTotal*$discountValue)/100;
    
            $fullTotal = $fullTotal-$discountPrice;
  
           // echo $fullTotal;         
    }

            
            $total =$total+$fullTotal;

        }
      }


    $pdf->Cell($width_cell[3],10,$fullTotal,1,1,'C',false); // Fourth column of row 1 
    
   

        
        }

    
    }

    
      
    
    
    $pdf->Cell($width_cell[0],10,'',0,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[1],10,'',0,0,'C',false); // Fourth column of row 1 
    $pdf->Cell($width_cell[2],10,'Total',1,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[3],10,$total,1,1,'C',false); // Fourth column of row 1

    $pdf->SetFont('Arial','B',20);
    $pdf->Ln(30);
    

   
    

    $pdf->Output('ff'.'.pdf', 'I' );

?>