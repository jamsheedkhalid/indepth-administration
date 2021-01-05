<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

if (isset($_POST['salarySubmit'])) {

    $to_address = $_POST['salary_toAddress'];
    $employee_name = $_POST['salary_name'];
    $employee_passport = $_POST['salary_passport'];
    $employee_joinDate = $_POST['salary_joinDate'];
    $employee_jobTitle = $_POST['salary_jobTitle'];
    $employee_nationality = $_POST['salary_nationality'];
    $employee_ms = $_POST['salary_ms'];
    $employee_salary = $_POST['salary_amount'];
    list($whole, $decimal) = explode('.', $employee_salary);
    $whole = convertNum($whole) . " dirhams";

    if($decimal != 00){
        $decimal = convertNum($decimal) . " fils";
    }
    else {
        $decimal = "";

    }


    if ($employee_ms === 'm') {
        $ms = 'Mr.';
        $hs = 'his';
    } else {
        $ms = 'Ms.';
        $hs = 'her';
    }

    switch ($_POST['salary_authorizer']) {
        case 1:
            $authoriser = 'Omar Sarriedine';
            $role = 'Administrative Director';
            break;
        case 2:
            $authoriser = 'Reema Sarriedine';
            $role = 'Principal';
            break;
        case 3:
            $authoriser = 'Talaat Sarriedine';
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
            $this->Cell(0, 20, "SALARY CERTIFICATE", 0, '1', 'C');

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
    $pdf->Cell(0, 30, "To " . $to_address, 0, '1', 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->Ln(8);
    $pdf->MultiCell(0, 5, "This is to confirm  that $ms $employee_name, $employee_nationality nationality, holder of passsport number $employee_passport is currently employed full time at Al Sanawbar School as $employee_jobTitle since $employee_joinDate with a total monthly salary of $whole $decimal (AED $employee_salary).", 0, 'L', false);
    $pdf->Ln(6);
    $pdf->MultiCell(0, 5, "This letter has been given upon $hs request without any responsiblity from our part.", 0, 'L', false);
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

