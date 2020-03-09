<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
include($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');
setlocale(LC_MONETARY, 'en_US');

session_start();

class PDF_INVOICE extends FPDF
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
        $this->SetTitle('Tax Invoice' );
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


if (isset($_POST['start_date'], $_POST['end_date'])) {


    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "
select fee_invoices.invoice_number             invoice_no,
       students.last_name                      student_name,
       students.admission_no                   admission_no,
       students.familyid                       family_id,
       batches.name                            section,
       courses.course_name                     grade,
       guardians.first_name                    parent,
       guardians.mobile_phone                  contact_no,
       round(finance_fees.particular_total, 2) particular_amount,
       round(finance_fees.discount_amount, 2)  discount,
       finance_fees.is_paid                    is_paid,
       round(finance_fees.balance, 2)          balance,
       finance_fee_collections.due_date        due_date,
       finance_fee_collections.name            collection_name,
       finance_fee_collections.start_date      invoice_date,
       finance_fee_particulars.name            particular,
       fee_discounts.name                       discount_name,
       finance_transactions.amount              paid

from fee_invoices
         inner join finance_fees on fee_invoices.fee_id = finance_fees.id
         inner join students on finance_fees.student_id = students.id
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join guardians on students.immediate_contact_id = guardians.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
        inner join finance_fee_categories on finance_fee_collections.fee_category_id = finance_fee_categories.id
        inner join collection_particulars on finance_fee_collections.id = collection_particulars.finance_fee_collection_id
         inner join finance_fee_particulars
                    on collection_particulars.finance_fee_particular_id = finance_fee_particulars.id and batches.id = finance_fee_particulars.batch_id
        left join fee_discounts on finance_fee_categories.id = fee_discounts.finance_fee_category_id and  students.id = fee_discounts.receiver_id
        left join finance_transactions on finance_fees.id = finance_transactions.finance_fees_id
where (finance_fee_collections.start_date BETWEEN '$start_date' AND '$end_date');
";

//echo $sql;
    $result = $conn->query($sql);


    $pdf = new PDF_INVOICE();

    $pdf->SetFont('times', '', 10);
    $pdf->ln();
    $pdf->SetFillColor(192, 192, 192);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $pdf->AddPage('P','A4');
            $pdf->setInvoiceNo($row['invoice_no']);
            $discount = (double)$row['discount'];
            $particular = (double)$row['particular_amount'];

            $pdf->SetLeftMargin(15);
            $pdf->SetRightMargin(15);
            $pdf->Cell(10, 0, 'Bill To', '', '1', 'L');
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(20, 10, 'Name', '', '', 'L');
            $pdf->Cell(40, 10, ':  ' . $row['parent'], '', 1, 'L');
            $pdf->Cell(20, 0, 'Parent ID', '', '', 'L');
            $pdf->Cell(40, 0, ':  ' . $row['family_id'], '', 1, 'L');
            $pdf->Cell(20, 10, 'Tel', '', '', 'L');
            $pdf->Cell(40, 10, ':  ' . $row['contact_no'], '', 1, 'L');
            $pdf->SetXY(130, 55);
            $pdf->Cell(20, 0, 'Invoice No', '', '', 'L');
            $pdf->Cell(20, 0, ':  ' . $row['invoice_no'], '', '', 'L');
            $pdf->SetXY(130, 55);
            $pdf->Cell(20, 10, 'Invoice Date', '', '', 'L');
            $pdf->Cell(20, 10, ':  ' . date('d-m-Y', strtotime($row['invoice_date'])), '', '2', 'L');
            $pdf->SetX(0);
            $pdf->ln(12);
            $pdf->SetFont('times', 'BU', 13);
            $pdf->Cell(0, 0, $row['collection_name'], '', '', 'C');
            $pdf->ln(12);
            $pdf->SetFont('times', '', 10);
            $pdf->Cell(0, 0, 'Student: ' . $row['admission_no'] . ' - ' . $row['student_name'], '', 1, 'L');
//        $pdf->Cell(0, 0, , '', '', 'C');
            $pdf->Cell(0, 0, 'Grade: ' . $row['grade'] . ' - ' . $row['section'] . '    AY: ' . '2019-2020', '', '', 'R');
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
            $pdf->Cell(30, 8, $row['particular_amount'], 'LTBR', '', 'R');
            $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
            $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
            $pdf->Cell(30, 8, $row['particular_amount'], 'LTBR', '1', 'R');

            if ($row['discount'] != 0) {
                $pdf->MultiCell(0, 8, 'Discount', 'LTBR', 'L', 1);
                $pdf->Cell(60, 8, $row['discount_name'] . '(' . $row['particular'] . ')', 'LTBR', '', 'L');
                $pdf->Cell(30, 8, $row['discount'], 'LTBR', '', 'R');
                $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
                $pdf->Cell(30, 8, '', 'LTBR', '', 'R');
                $pdf->Cell(30, 8, $row['discount'], 'LTBR', '1', 'R');
            }
            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Total VAT', 'LTBR', '', 'L');
            $pdf->Cell(30, 8, 0, 'LTBR', '1', 'R');
            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Total to pay', 'LTBR', '', 'L');
            $pdf->Cell(30, 8, number_format($particular - $discount, 2), 'LTBR', '1', 'R');

            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Paid ', 'LTBR', '', 'L');
//        if ($row['paid']!= null){
//            $pdf->Cell(30, 8, number_format($row['paid'], 2), 'LTBR', '1', 'R');
//        }
//            else {
            $pdf->Cell(30, 8, number_format(($particular - $discount - $row['balance']), 2), 'LTBR', '1', 'R');
//
//        }            $pdf->Cell(120, 8, '', '', '', 'R');
            $pdf->Cell(30, 8, 'Balance ', 'LTBR', '', 'L');
            $pdf->Cell(30, 8, number_format($row['balance'], 2), 'LTBR', '', 'R');


        }
    }


    $pdf->Output('I', 'Tax-invoice', true);
    $pdf->Close();

}

