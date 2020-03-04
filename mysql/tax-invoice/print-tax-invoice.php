<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
include($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');
session_start();
$invoice_no = $_POST['printTaxInvoice'];

class PDF1 extends FPDF
{
    private $invoice;

    public function setInvoiceNo($invoice)
    {
        $this->invoice = $invoice;
    }

// Page header
    function Header()
    {
        // Logo
        $this->SetTitle('Tax Invoice-' . $this->invoice);
        $this->Rect(10, 10, 190, 257, 'D'); //For A4
        $this->Image('../../assets/images/sanawbar-logo.jpeg', 15, 13, 20, 20);
        $this->SetFont('times', 'B', 13);
        $this->SetXY(38, 15);
        $this->Cell(10, 0, 'Al SANAWBAR SCHOOL', 0, 2, 'L');
        $this->SetFont('times', '', 10);
        $this->Cell(38, 10, 'Manaseer School Road, P.0 Box 1781', 0, '', 'L');
        $this->SetFont('times', 'U', 18);
        $this->Cell(120, 10, 'TAX INVOICE', 0, 2, 'R');
        $this->SetFont('times', 'B', 10);
        $this->Cell(120, 5, 'TRN 100270764200003', 0, 2, 'R');
        $this->SetXY(38, 25);
        $this->SetFont('times', '', 10);
        $this->Cell(38, 0, 'Tel: 03 767 98889', 0, '2', 'L');
        $this->Cell(38, 8, 'www.alsanawbarschool.com', 0, '2', 'L');
        $this->SetLineWidth(0.2);
        $this->Line(10, 40, 200, 40);
        $this->Ln(10);
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


$sql = "select fee_invoices.invoice_number              invoice_no,
       students.last_name                       student,
       students.admission_no                    adm_no,
       students.familyid                        family_id,
       guardians.first_name                     guardian,
       guardians.mobile_phone                   contact_no,
       finance_fees.particular_total            due_amount,
       finance_fees.balance                     balance,
       finance_fee_collections.name             fee_name,
       finance_fee_collections.start_date       invoice_date,
       finance_fee_collections.due_date         due_date,
       finance_fees.is_paid                     is_paid,
       batches.name                             section,
       courses.course_name                      grade,
       finance_transactions.transaction_date    pay_date,
       finance_transactions.amount              pay_amount,
       finance_fee_particulars.name             particular,
       round(finance_fee_particulars.amount, 2) particular_amount,
       finance_fee_discounts.discount_amount    discount_amount,
       fee_discounts.name                       discount_name
from fee_invoices
         inner join finance_fees on fee_invoices.fee_id = finance_fees.id
         inner join batches on finance_fees.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join finance_fee_discounts on finance_fees.id = finance_fee_discounts.finance_fee_id
         inner join alsanawbar.finance_fee_particulars
                    on (finance_fee_collections.fee_category_id = finance_fee_particulars.finance_fee_category_id and
                        batches.id = finance_fee_particulars.batch_id)
         inner join collection_discounts on finance_fee_collections.id = collection_discounts.finance_fee_collection_id
         inner join fee_discounts on collection_discounts.fee_discount_id = fee_discounts.id
         inner join students on finance_fees.student_id = students.id
         inner join guardians on students.familyid = guardians.familyid
         left join finance_transactions on finance_fees.id = finance_transactions.finance_id

where fee_invoices.is_active = 1 and fee_invoices.id = '$invoice_no'  ;";

echo $sql;
$result = $conn->query($sql);


$pdf = new PDF1();
$pdf->AddPage();
$pdf->setInvoiceNo($invoice_no);
$pdf->SetFont('times', '', 10);
$pdf->ln();
$pdf->SetFillColor(192,192,192);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $pdf->SetLeftMargin(15);
        $pdf->SetRightMargin(15);
        $pdf->Cell(10, 0, 'Bill To', '', '1', 'L');
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(20, 10, 'Name', '', '', 'L');
        $pdf->Cell(40, 10, ':  ' . $row['guardian'], '', 1, 'L');
        $pdf->Cell(20, 0, 'Parent ID', '', '', 'L');
        $pdf->Cell(40, 0, ':  ' . $row['family_id'], '', 1, 'L');
        $pdf->Cell(20, 10, 'Tel', '', '', 'L');
        $pdf->Cell(40, 10, ':  ' . $row['contact_no'], '', 1, 'L');
        $pdf->SetXY(130, 55);
        $pdf->Cell(20, 0, 'Invoice No', '', '', 'L');
        $pdf->Cell(20, 0, ':  ' . $invoice_no, '', '', 'L');
        $pdf->SetXY(130, 55);
        $pdf->Cell(20, 10, 'Invoice Date', '', '', 'L');
        $pdf->Cell(20, 10, ':  ' . date('d-m-Y', strtotime($row['invoice_date'])), '', '2', 'L');
        $pdf->SetX(0);
        $pdf->ln(12);
        $pdf->SetFont('times', 'BU', 13);
        $pdf->Cell(0, 0, $row['fee_name'], '', '', 'C');
        $pdf->ln(12);
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(0, 0, 'Student  : ' . $row['adm_no'] . ' - ' . $row['student'], '', 1, 'L');
        $pdf->Cell(0, 0, 'Grade  : ' . $row['grade'] . ' - ' . $row['section'], '', '', 'C');
        $pdf->Cell(0, 0, 'AY  : ' . '2019-2020', '', '', 'R');
        $pdf->SetFont('times', '', 10);
        $pdf->SetXY(0, 85);
        $pdf->ln(10);
        $pdf->MultiCell(60, 8, 'Particular', 'LTBR', 'C', 1);
        $pdf->SetXY(75, 95);
        $pdf->MultiCell(30, 8, 'Amount', 'LTBR', 'C', 1);
        $pdf->SetXY(105, 95);
        $pdf->MultiCell(30, 8, 'VAT (%)', 'LTBR', 'C', 1);
        $pdf->SetXY(135, 95);
        $pdf->MultiCell(30, 8, 'VAT Amount', 'LTBR', 'C', 1);
        $pdf->SetXY(165, 95);
        $pdf->MultiCell(30, 8, 'Total', 'LTBR', 'C', 1);
        $pdf->Cell(60, 8, $row['particular'], 'LTBR', '', 'L');
        $pdf->Cell(30, 8, $row['particular_amount']+0, 'LTBR', '', 'R');
        $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
        $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
        $pdf->Cell(30, 8, $row['particular_amount']+0, 'LTBR', '1', 'R');
        $pdf->MultiCell(0, 8, 'Discount', 'LTBR', 'L', 1);
        $pdf->Cell(60, 8, $row['particular'], 'LTBR', '', 'L');
        $pdf->Cell(30, 8, $row['discount_amount'] + 0, 'LTBR', '', 'R');
        $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
        $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
        $pdf->Cell(30, 8, $row['discount_amount'] + 0, 'LTBR', '1', 'R');
        $pdf->Cell(120, 8, '', '', '', 'R');
        $pdf->Cell(30, 8, 'Total VAT', 'LTBR', '', 'L');
        $pdf->Cell(30, 8, 0, 'LTBR', '1', 'R');
        $pdf->Cell(120, 8, '', '', '', 'R');
        $pdf->Cell(30, 8, 'Total ', 'LTBR', '', 'L');
        $pdf->Cell(30, 8, $row['discount_amount'] + $row['particular_amount'], 'LTBR', '1', 'R');
        if($row['is_paid'] == 1) {
            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Paid ', 'LTBR', '', 'L');
            $pdf->Cell(30, 8, $row['pay_amount'] , 'LTBR', '', 'R');
        } else {
            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Balance ', 'LTBR', '', 'L');
            $pdf->Cell(30, 8, $row['balance'] , 'LTBR', '', 'R');
        }
        break;

    }
}


$pdf->Output('I', 'cvs', true);
$pdf->Close();

