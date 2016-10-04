<?php
  
  ob_start();
  session_start();
  include 'functions.php';
 ?>
 <?php
  define('FPDF_FONTPATH','FPDF/font/');
  require('FPDF/fpdf.php');
  include("connection.php");
  class XFPDF extends FPDF
  {
    function FancyTable($header,$data)
    {
      $this->SetFillColor(255,0,0);       
      $this->SetTextColor(255,255,255);   
      $this->SetDrawColor(128,0,0);      
      $this->SetLineWidth(.3);        
      $this->SetFont('','B');
      $w=array(5,15,15,15,15,30,5,15,15,15,10,25);

      for($i=0;$i<sizeof($header);$i++)
      {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
      }
      $this->Ln();
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0,0,0);     
      $this->SetFont('');         
      $fill=0;
      
      foreach($data as $row)
      {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
        $this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
		$this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
		$this->Cell($w[6],6,$row[6],'LR',0,'L',$fill);
		$this->Cell($w[7],6,$row[7],'LR',0,'L',$fill);
		$this->Cell($w[8],6,$row[8],'LR',0,'L',$fill);
		$this->Cell($w[9],6,$row[9],'LR',0,'L',$fill);
		$this->Cell($w[10],6,$row[10],'LR',0,'L',$fill);
		$this->Cell($w[11],6,$row[11],'LR',0,'L',$fill);
        $this->Ln();
        $fill=!$fill;
      } 
      $this->Cell(array_sum($w),0,'','T'); 
    }
  }
  $conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
  $query = "SELECT * FROM CUSTOMER ORDER BY CUST_ID";
  $stmt = oci_parse($conn,$query);
  oci_execute($stmt); 
  
  $nrows = oci_fetch_all($stmt,$results);

  if ($nrows> 0)
  {
    $data = array();
    $header= array();
    while(list($column_name) = each($results))
    {
      $header[]=$column_name;
    }
    for ($i = 0; $i<$nrows; $i++)
    {
      reset($results);
      $j=0;
      while (list(,$column_value) = each($results))
      {
        $data[$i][$j] = $column_value[$i];
        $j++;
      }
    }
  }
  else
  {
    echo "No Records found";
  }
  oci_free_statement($stmt);
  
  $pdf = new XFPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','',5); 
  $pdf->FancyTable($header,$data);
  $pdf->Output("I");
 ?>