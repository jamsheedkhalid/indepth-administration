<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
session_start();

$ledger_id = $_POST['printTaxInvoice'];

class TAX_PDF extends TCPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->SetTitle('Tax Invoice');
        $this->Rect(10, 10, 190, 257, 'D'); //For A4
        $this->Image('../../assets/images/sanawbar-logo.jpeg', 15, 18, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
        $this->Cell(38, 10, 'Tel: 03 767 98889', 0, '2', 'L');
        $this->Cell(38, 0, 'www.alsanawbarschool.com', 0, '', 'L');
        $this->SetLineWidth(0.1);
        $this->Line(10, 45, 200, 45);
        $this->Ln(10);
    }

// Page footer
    public function Footer()
    {
        $date = date('d-m-Y');
        $this->SetFont('times', 'I', 10);
        $this->SetY(-60);
        $this->Cell(0, 10, '__________________   ', 0, 0, 'R');
        $this->SetY(-55);
        $this->Cell(0, 10, 'Accountant Signature   ', 0, 0, 'R');
        $this->SetY(-15);
        // times italic 8
        $this->SetFont('times', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Printed by ' . $_SESSION['name'], 0, 0, 'L');
        $this->Cell(0, 10, 'Printed on ' . $date, 0, 0, 'R');
    }
}

$pdf = new TAX_PDF('L');
$pdf->SetTitle('Weekly Planner');
$pdf->SetMargins(10, 50, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->AddPage('P', 'A4');
$fontFamily = 'Times'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
$fontStyle = ''; // 'B', 'I', 'U', 'BI', 'BU', 'IU', 'BIU'
$fontSize = 10; // float, in point
$pdf->SetFont($fontFamily, $fontStyle, $fontSize);

$particular_total = 0;
$vat_total = 0;
$vat = 0;


$sql = "
select guardians.familyid                           family_id,
       guardians.first_name                         parent_name,
       guardians.mobile_phone                       parent_contact,
       students.last_name                           student_name,
       students.admission_no                        admission_no,
       batches.name                                 section,
       courses.course_name                          grade,
       Round(finance_transaction_ledgers.amount,2)           transaction_amount,
       finance_fee_collections.name                 collection_name,
       finance_fee_particulars.name                particular_name,
       Round(finance_fees.particular_total,2)                particular_total,
       Round(finance_fees.balance,2)                         particular_balance,
       Round(finance_transactions.amount,2)                  particular_paid,
       fee_invoices.invoice_number                  invoice_number,
       transaction_receipts.receipt_number          receipt_number,
       finance_transaction_ledgers.transaction_date transaction_date,
       finance_transaction_ledgers.payment_mode     transaction_mode,
       finance_transaction_ledgers.payment_note     transaction_note,
       finance_transaction_ledgers.reference_no     reference_no,
       finance_transaction_ledgers.id               ledger_id
from finance_transaction_ledgers
         inner join finance_transactions on finance_transaction_ledgers.id = finance_transactions.transaction_ledger_id
         inner join finance_fees on finance_transactions.finance_id = finance_fees.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join fee_invoices on finance_fees.id = fee_invoices.fee_id
         inner join finance_transaction_receipt_records
                    on finance_transactions.id = finance_transaction_receipt_records.finance_transaction_id
         inner join transaction_receipts
                    on finance_transaction_receipt_records.transaction_receipt_id = transaction_receipts.id
         inner join students on finance_fees.student_id = students.id
         inner join guardians on students.immediate_contact_id = guardians.id
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join collection_particulars
                    on finance_fee_collections.id = collection_particulars.finance_fee_collection_id
         inner join finance_fee_particulars
                    on collection_particulars.finance_fee_particular_id = finance_fee_particulars.id and
                       batches.id = finance_fee_particulars.batch_id
where finance_transaction_ledgers.id = '$ledger_id';
";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $transaction_date = date('d-M-Y', strtotime($row['transaction_date']));
        $html = <<<EOD
<table style="padding: 10px;">
<tr>
<td>Bill To:</td>
<td></td>
</tr>
<tr>
<td><strong>Name</strong>      : $row[parent_name] 
<br><strong>Parent ID</strong> : $row[family_id]
<br><strong>Tel</strong> : $row[parent_contact]</td>
<td><strong>Invoice No</strong>: $row[ledger_id] 
<br><strong>Invoice Date</strong> : $transaction_date </td>
</tr>
</table>
<br>
<h2 align="center"><u>FEES INVOICE</u></h2>
<table style="padding: 10px;">
<tr>
<td><strong>Student Name:</strong> $row[student_name]</td>
<td><strong>Section:</strong> $row[grade] -  $row[section] </td>
</tr>
</table>

<table style="padding: 10px; margin-left: 10px; width: 100%"  cellspacing="0" cellpadding="1" border="1" class="table table-bordered table-dark">
<thead>
<tr  align="center" valign="center"  style="font-weight: bold" >
<th height="30"  width="50" >Inv #</th>
<th width="50">Receipt #  </th>
<th width="150">Fee</th>
<th>Amount</th>
<th width="60">VAT(%)</th>
<th width="65">VAT Amount</th>
<th>Total</th>
<th>Paid</th>
<th>Balance</th>
</tr>
</thead>
<tbody>
EOD;

        $result_fees = $conn->query($sql);
        if ($result_fees->num_rows > 0) {
            while ($row_fees = mysqli_fetch_array($result_fees)) {
                $html .= <<<EOD
   <tr >
<td height="30" width="50" > $row_fees[invoice_number]</td>
<td width="50" > $row_fees[receipt_number]</td>
<td width="150" > $row_fees[particular_name]</td>
EOD;

                if (stripos($row_fees['particular_name'], 'uniform') !== false) {
                    $particular_total = number_format((double)$row_fees['particular_total'] - ((5 * (double)$row_fees['particular_total']) / 100), 2);
                    $vat = (5 * (double)$row_fees['particular_total'] / 100);
                    $vat_total = $vat_total + $vat;
                    $vat_total = number_format($vat_total, 2);
                    $html .= <<<EOD
<td align="right" >$particular_total </td>                   
<td align="right" width="60">5</td>
<td align="right" width="65">$vat</td>

EOD;
                } else
                    if (stripos($row_fees['particular_name'], 'bus') !== false) {

                        $html .= <<<EOD
<td align="right" >$row_fees[particular_total] </td>                   
<td align="right" width="60">EXE</td>
<td align="right" width="65">-</td>

EOD;
                    } else {
                        $html .= <<<EOD
<td align="right" > $row_fees[particular_total]</td>
<td align="right" width="60">0</td>
<td align="right" width="65">0</td>

EOD;
                    }


                $html .= <<<EOD
<td align="right"> $row_fees[particular_total]</td>
<td align="right"> $row_fees[particular_paid]</td>
<td align="right"> $row_fees[particular_balance]</td>
</tr>
EOD;
            }
        }

        list($feeWhole, $feeDecimal) = explode('.', $row['transaction_amount']);
        $feeWhole = ucwords(convertNum($feeWhole)) . ' Dirhams';
        $feeDecimal = ucwords(convertNum($feeDecimal)) . ' Fils';

        $html .= <<<EOD
        <tr >
        <td height="30" colspan="7"></td>
        <td  align="right">Total VAT</td>
        <td align="right" >$vat_total</td>
</tr>       
 <tr >
        <td  style="font-weight: bold" height="30"  colspan="7"><label align="left"> Amount in words:  $feeWhole and $feeDecimal</label> </td><td border="0"  align="right"> Total Paid</td>
        <td  style="font-weight: bold" align="right" >$row[transaction_amount]</td>
</tr> <tr >
        <td height="20"  colspan="9"> Payment Mode : $row[transaction_mode] <br> Reference: $row[reference_no]  </td>
</tr>
</tbody>
</table>

<table style="padding: 20px;">
            <tr>
                    <td width="325">
                        <table  cellspacing="0" cellpadding="1" border="1">
                                <tr>
                                <td>
                                    <table>
                                    <tr><td  colspan="2">Bank Details:</td></tr>
                                    <tr><td width="100">Bank</td><td>: Bank of Sharjah</td></tr>
                                    <tr><td>Branch</td><td>: Al Ain</td></tr>
                                    <tr><td width="100">Account Name</td><td>: AL Sanawbar School</td></tr>
                                    <tr><td>Account No</td><td>: 01106-357005</td></tr>
                                    <tr><td>IBAN</td><td  width="200">: AED900120000001106357005</td></tr>
                                    <tr><td>Currency</td><td>: AED</td></tr>
                                    <tr><td>Swift Code</td><td>: SHARAEAS</td></tr>
                                    </table>
                                                                     
                                    </td>
                                    
                                </tr>
                        </table>
                    </td>
                    <td>
                     <table>
                                <tr>
                                    <td>Payment can be done in CASH/VISA/CHEQUE drawn in favour of Al Sanawbar School or through direct bank transfer</td>
                                </tr>
                                <tr>
                                
                                Notes:<br>
                                1. Please ensure that the above invoice amount is credited to our account after deduction of all bank charges.<br>
                                2. Kindly email student name, grade, family ID and bank transfer receipt.
</tr>
                        </table>
                    </td>
            </tr>
</table>


EOD;

        break;

    }
}

$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('Invoice', 'I');
$pdf->Close();



