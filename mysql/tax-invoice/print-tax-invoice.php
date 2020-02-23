<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
include($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');


    $invoice_no = $_POST['printTaxInvoice'];


    class PDF1 extends FPDF
    {

// Page header
        public function Header()
        {
            // Logo
            $this->Image('../../assets/images/sanawbar-logo.jpeg', 95, 10, 20, 20);
            $this->SetFont('times', 'B', 13);
            $this->Ln(25);
            $this->Cell(0, 0, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
            $this->SetFont('times', 'B', 10);
            $this->Ln(7);
            $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
            $this->Ln(5);
            $this->Cell(0, 0, 'STUDENT EVALUATION REPORT', 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(10, 52, 200, 52);
            $this->SetFont('times', '', 10);
            $this->Ln(15);
            $this->Cell(0, 0, 'ACADEMIC YEAR: 2019 - 2020', 0, 2, 'C');
            $this->Ln(10);
            $this->SetLineWidth(0.2);
            $this->Line(130, 80, 80, 80);
            $this->Ln(20);
        }
// Page footer
        public function Footer()
        {
            $date = date('d-m-Y');
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // times italic 8
            $this->SetFont('times', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Printed By ' . $_SESSION['name'], 0, 0, 'L');
            $this->Cell(0, 10, 'Date ' . $date, 0, 0, 'R');
        }
    }

    $pdf = new PDF1();
    $pdf->AddPage();
    $pdf->Cell(0,0, 'hi');



