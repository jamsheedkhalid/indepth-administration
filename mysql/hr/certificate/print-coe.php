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
            $this->Ln(70);
            $this->SetFont('times', '', 12);
            $this->Cell(0, 10, "Date: ".date("d-m-Y"), 0, '1', 'R');

            $this->SetFont('times', 'BU', 18);
            $this->SetX($this->lMargin);
            $this->Cell(0, 20, "EMPLOYEMENT CERTIFICATE", 0, '1', 'C');

        }

// Page footer
        public function Footer()
        {

        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 15);
    $pdf->Cell(0, 30, "To " .$to_address, 0, '1', 'L');
    $pdf->SetFont('times', '', 14);
    $pdf->Ln(8);
    $pdf->MultiCell(0, 10, "This is to certify that $employee_name, $employee_nationality nationality, holder of passsport/EID number $employee_passport is currently employed full time at Al Sanawbar School as $employee_jobTitle since $employee_joinDate till date.", 0, 'L',false );
    $pdf->Ln(6);
    $pdf->MultiCell(0, 10, "This letter has been given upon his/he request without any responsiblity from our part.", 0, 'L',false );
    $pdf->Ln(15);
    $pdf->MultiCell(0, 20, "Sincerely yours,", 0, 'L',false );
    $pdf->Ln(2);
    $pdf->MultiCell(0, 6, "Omar Sarieddine", 0, 'L',false );
    $pdf->MultiCell(0, 6, "Administrative Officer", 0, 'L',false );
    $pdf->MultiCell(0, 6, "Al Sanawbar School", 0, 'L',false );
    $pdf->Output('I', 'report-card.pdf', true);
    $pdf->Close();

}

