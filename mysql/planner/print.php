<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

$grade = $_REQUEST['grade'];

    class PDF extends FPDF
    {
// Page header
        public function Header()
        {
            $term = $_REQUEST['hidden_term_studentWise'];
            // Logo
            $this->Image('../../assets/images/sanawbar-logo.jpeg', 95, 10, 20, 20);
            $this->SetFont('times', 'B', 13);
            // Move to the right
            $this->Ln(25);
            // Title
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


            $this->Cell(0, 0, $term_name, 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(130, 80, 80, 80);

            // Line break
            $this->Ln(20);
        }

// Page footer
        public function Footer()
        {
            $date = date("d-m-Y");
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // times italic 8
            $this->SetFont('times', 'I', 8);
            // Page number
//            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            $this->Cell(0, 10, 'Date ' . $date, 0, 0, 'R');
        }
    }


    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();



    $pdf->Output('I', 'report-card.pdf', true);
    $pdf->Close();



