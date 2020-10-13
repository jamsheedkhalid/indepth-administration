<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

if (isset($_POST['nolSubmit'])) {

    $to_address = $_POST['nol_toAddress'];
    $employee_name = $_POST['nol_name'];
    $employee_passport = $_POST['nol_passport'];
    $employee_joinDate = $_POST['nol_joinDate'];
    $employee_jobTitle = $_POST['nol_jobTitle'];
    $employee_nationality = $_POST['nol_nationality'];
    $reason = $_POST['nol_reason'];
    $decl = $_POST['nol_decl'];
    $employee_ms = $_POST['nol_ms'];
    if ($employee_ms === 'm') {
        $ms = 'Mr.';
        $hs = 'his';
    } else {
        $ms = 'Ms.';
        $hs = 'her';
    }

    switch ($_POST['nol_authorizer']) {
        case 1:
            $authoriser = 'Mr. Omar Sarriedine';
            $role = 'Administrative Director';
            break;
        case 2:
            $authoriser = 'Ms. Reema Sarriedine';
            $role = 'Principal';
            break;
        case 3:
            $authoriser = 'Mr. Talaat Sarriedine';
            $role = 'Administrative Manager';
            break;

    }


    class PDF extends FPDF
    {
// Page header
        public function Header()
        {
            $this->Ln(70);
            $this->SetFont('times', '', 12);
            $this->Cell(0, 10, "Date: " . date("d-m-Y"), 0, '1', 'R');

            $this->SetFont('times', 'BU', 18);
            $this->SetX($this->lMargin);
            $this->Cell(0, 20, "NO OBJECTION LETTER", 0, '1', 'C');

        }

// Page footer
        public function Footer()
        {

        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 13);
    $pdf->Cell(0, 30, "To: " . $to_address, 0, '1', 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(0, 10, "Dear Sir/Madam,", 0, '1', 'L');
    $pdf->Cell(0, 5, "Greetings,", 0, '1', 'L');
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, "This is to confirm that $ms $employee_name, holder of nationality $employee_nationality, passsport number $employee_passport is currently employed full time at Al Sanawbar School as $employee_jobTitle since $employee_joinDate till date.", 0, 'L', false);
    $pdf->MultiCell(0, 5, "$employee_name has expressed interest in $reason. Our organization have no objection regarding $hs $decl.", 0, 'L', false);

    $pdf->Ln(6);
    $pdf->MultiCell(0, 5, "Please feel free to contact us if you require any further information.", 0, 'L', false);
    $pdf->Ln(15);
    $pdf->MultiCell(0, 20, "Sincerely yours,", 0, 'L', false);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 6, $authoriser, 0, 'L', false);
    $pdf->MultiCell(0, 6, $role, 0, 'L', false);
    $pdf->MultiCell(0, 6, "Al Sanawbar School", 0, 'L', false);
    $pdf->Output('I', 'report-card.pdf', true);
    $pdf->Close();

}

