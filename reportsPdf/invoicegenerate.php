<?php 
require('../assets/fpdf186/fpdf.php');
require('../dbconnect/dbconnect.php');
require('../dbconnect/queris/select.php');

if(isset($_GET['billid'])){

$billID = intval($_GET['billid']-1);








function  dowonloadPDF($billID , $conn){

 
    $sql = "SELECT
    accessoriesitem.ItemNo,
   accessoriesitem.itemName,
   stock.SellingPrice,
   accessoriesitem.itemQty,
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

$itemList = dowonloadPDF($billID , $conn);

$itemCount =0;
$fullTotal=0;
$discountValue = 0;






class PDF extends FPDF
    {
        // Page header
        function Header()
    {
        $mobileNumber = "0705161216,0763030377";
        $Address = "No. 49/2 Robertgunawardhana Mawatha, Thalangama South Battaramulla.";
        $Email = "sdmulticare@gmail.com";
        // Logo
        $Title = "SD Multicare House";
        $this->Image('../assets/fpdf186/images/sdlogo.jpeg',170,6,30,);
        // Arial bold 15
        $this->SetFont('Arial','B',25);
        // Move to the right
        
        // Title
        
        $this->Cell(30,10,$Title,0,0,'L');
    
      
        $this->SetFont('Arial','B',12);
        // Line break
        $this->Ln(7);
        $this->Cell(0,10,$Address,0,0,'L');
        $this->Ln(5);
        $this->Cell(0,10,'Email:- '.$Email,0,0,'L');
        $this->Ln(5);
        $this->Cell(0,10,'Mobile and Whatsapp:- '.$mobileNumber,0,0,'L');
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

    $pdf->Cell(50,10,'S&D invoice',0,0,'C');

    $pdf->SetFont('Arial','B',11);
    
    $pdf->SetXY(1,50);
    $pdf->Cell(50,10,'Bill NO - ',0,0,'C');

    $pdf->SetXY(15,50);
    $pdf->Cell(50,10,$billID,0,0,'C');




    $pdf->SetFont('Arial','B',11);
     
    $pdf->Ln(15);
    $width_cell=array(10,20,50,50,20,45);
    $pdf->SetFillColor(193,229,252); 
    
    // Header starts /// 
    $pdf->Cell($width_cell[0],10,'',1,0,'C',true); 
    $pdf->Cell($width_cell[1],10,'ITEM NO',1,0,'C',true); 
    $pdf->Cell($width_cell[2],10,'ITEM NAME',1,0,'C',true); 
    $pdf->Cell($width_cell[3],10,'SELLING PRICE(RS.)',1,0,'C',true);
    $pdf->Cell($width_cell[4],10,'QTY',1,0,'C',true);
    $pdf->Cell($width_cell[5],10,'SUB TOTAL(RS.)',1,1,'C',true); 
    //// header is over ///////
    


    $pdf->SetFont('Arial','',10);

 

    if($itemList->num_rows > 0){

        while($row = mysqli_fetch_array($itemList,MYSQLI_ASSOC)){
$itemCount++;
    
    $pdf->Cell($width_cell[0],10,$itemCount,1,0,'C',false); // First column of row 1 
    $pdf->Cell($width_cell[1],10,$row['ItemNo'],1,0,'C',false); // Second column of row 1 
    $pdf->Cell($width_cell[2],10,$row['itemName'],1,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[3],10,$row['SellingPrice'],1,0,'C',false); // Fourth column of row 1 
    $pdf->Cell($width_cell[4],10,$row['itemQty'],1,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[5],10,$row['subTotal'],1,1,'C',false); // Fourth column of row 1 
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
      
    $pdf->Cell($width_cell[0],10,'',0,0,'C',false); // First column of row 1 
    $pdf->Cell($width_cell[1],10,'',0,0,'C',false); // Second column of row 1 
    $pdf->Cell($width_cell[2],10,'',0,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[3],10,'',0,0,'C',false); // Fourth column of row 1 
    $pdf->Cell($width_cell[4],10,'Discount',1,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[5],10,$discountValue,1,1,'C',false); // Fourth column of row 1 

   
    $pdf->Cell($width_cell[0],10,'',0,0,'C',false); // First column of row 1 
    $pdf->Cell($width_cell[1],10,'',0,0,'C',false); // Second column of row 1 
    $pdf->Cell($width_cell[2],10,'',0,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[3],10,'',0,0,'C',false); // Fourth column of row 1 
    $pdf->Cell($width_cell[4],10,'Total',1,0,'C',false); // Third column of row 1 
    $pdf->Cell($width_cell[5],10,$fullTotal,1,1,'C',false); // Fourth column of row 1

    $pdf->SetFont('Arial','B',20);
    $pdf->Ln(30);
    
  
  
    $pdf->SetXY(50,180);
    $pdf->Cell(150,10,'Thank you come again !',0,0,'L',false);
   
   

    $pdf->Output($billID.'.pdf', 'I' );

   
}


?>