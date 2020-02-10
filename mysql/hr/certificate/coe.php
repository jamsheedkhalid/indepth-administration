<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

if (isset($_POST['coeSubmit'])) {

    $to_address = $_POST['coe_toAddress'];
    $employee_name = $_POST['coe_name'];
    $employee_passport = $_POST['coe_passport'];
    $employee_joinDate = $_POST['coe_joinDate'];
    $employee_jobTitle = $_POST['coe_jobTitle'];
    $employee_nationality = $_POST['coe_nationality'];


    class PDF extends FPDF
    {
// Page header
        public function Header()
        {

            $this->Ln(50);
            $pdf->Cell(50, 5, "Admission No. :", 0, 0, 'R');


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
            $this->Cell(0, 10, 'Date ' . $date, 0, 0, 'R');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(50, 5, "Admission No. :", 0, 0, 'R');
    $pdf->Output('I',  'report-card.pdf', true);
    $pdf->Close();

}

