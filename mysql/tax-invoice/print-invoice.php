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

$pdf = new TAX_PDF('P');
$pdf->SetTitle('Tax Invoice');
$pdf->SetMargins(10, 50, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->AddPage('P', 'A4');
$fontFamily = 'Times'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
$fontStyle = ''; // 'B', 'I', 'U', 'BI', 'BU', 'IU', 'BIU'
$fontSize = 10; // float, in point

$pdf->SetFont($fontFamily, $fontStyle, $fontSize);

$sql = "select 
                round(finance_transaction_ledgers.amount,2)           transaction_amount,
                finance_transaction_ledgers.transaction_data           transaction_data,
                finance_transaction_ledgers.transaction_date transaction_date,
                finance_transaction_ledgers.payment_mode     transaction_mode,
                finance_transaction_ledgers.reference_no     reference_no,
                finance_transaction_ledgers.payment_note     payment_note,
                finance_transaction_ledgers.id               ledger_id
from finance_transaction_ledgers
 where id = '$ledger_id'
 ";
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = mysqli_fetch_array($result)){

        $transaction_data = $row['transaction_data'];
        $data = yaml_parse($transaction_data);
//        var_dump($data);
        $admission_no =  $data['table'][':payee']['table'][':admission_no'];
        $parent_name =  $data['table'][':payee']['table'][':guardian_name'];
        $grade =   $data['table'][':payee']['table'][':course_full_name'];
        $section_id =  $data['table'][':batch_id'];
        $transaction_date =  $data['table'][':transaction_date'];
        $date = date('d-M-Y',strtotime($transaction_date));


        $student = " select last_name from students where admission_no = '$admission_no'";
        $result = $conn->query($student);
        if ($result->num_rows > 0) {
            $student = mysqli_fetch_row($result);
        }
        else {
            $student = " select last_name from archived_students where admission_no = '$admission_no'";
            $result = $conn->query($student);
            if ($result->num_rows > 0) {
                $student = mysqli_fetch_row($result);
            } else {
                $student = '-';
            }
        }

        $section = " select name from batches where id = '$section_id'";
        $result = $conn->query($section);
        if ($result->num_rows > 0) {
            $section = mysqli_fetch_row($result)[0];
        }




//        fetch family id
        $p_id = " select familyid from students where admission_no = '$admission_no'";
        $result = $conn->query($p_id);
        if ($result->num_rows > 0) {
            $family_id = mysqli_fetch_row($result);
        }
        else {
            $p_id = " select familyid from archived_students where admission_no = '$admission_no'";
            $result = $conn->query($p_id);
            if ($result->num_rows > 0) {
                $family_id = mysqli_fetch_row($result);
            } else {
                $family_id = '-';
            }
        }

        //        fetch parent contact number
        $p_id = " select mobile_phone from guardians where familyid = '$family_id[0]'";
        $result = $conn->query($p_id);
        if ($result->num_rows > 0) {
            $contact_no = mysqli_fetch_row($result);
        }
        else {
            $p_id = " select mobile_phone from archived_guardians where familyid = '$family_id[0]'";
            $result = $conn->query($p_id);
            if ($result->num_rows > 0) {
                $contact_no = mysqli_fetch_row($result);
            } else {
                $contact_no = '-';
            }
        }


        $html = <<<EOD
        <table style="padding: 10px;">
<tr>
<td>Bill To:</td>
<td></td>
</tr>
<tr>
<td width="500"><strong>Name</strong>      : $parent_name
        <br><strong>Parent ID</strong> : $family_id[0]
        <br><strong>Tel</strong> : $contact_no[0] </td>
<td><strong>Invoice No</strong>: $ledger_id
        <br><strong>Invoice Date</strong> : $date </td>
</tr>
</table>
<br>
<h2 align="center"><u>FEES INVOICE</u></h2>
<table style="padding: 10px;">
<tr>
<td width="510"><strong>Student :</strong> $admission_no - $student[0]</td>
<td><strong>Section:</strong>  $grade -  $section </td>
</tr>
</table>
<br><br>
<table style="padding: 10px; width: 100%"  cellspacing="0" cellpadding="1" border="1" class="table table-bordered table-dark">
<thead >
<tr  align="center" valign="center"  style="font-weight: bold; background-color: lightgrey" >
<th height="30"  width="50" >Inv #</th>
<th width="50">Receipt #  </th>
<th width="150">Fee</th>
<th>Amount</th>
<th width="60">VAT(%)</th>
<th width="65">VAT Amount</th>
<th>Total</th>
<th>Paid</th>
<th>Due Amount</th>
</tr>
</thead>
<tbody> 
EOD;
        $transactions = count($data['table'][':transactions']['']);
        for ($i=0; $i<$transactions; $i++){

            $amount =  $data['table'][':transactions'][''][$i]['table'][':actual_amount'];
            $tr_id =  $data['table'][':transactions'][''][$i]['table'][':ft_id'];
            $fee_collection_name =  $data['table'][':transactions'][''][$i]['table'][':fee_collection_name'];
            $cname =  $data['table'][':transactions'][''][$i]['table'][':cname'];
            $transaction_amount =  $data['table'][':transactions'][''][$i]['table'][':transaction_amount'];
            $balance =  number_format(round((double)$data['table'][':transactions'][''][$i]['table'][':balance'],2),2);

            [$feeWhole, $feeDecimal] = explode('.', $row['transaction_amount']);
            $feeWhole = ucwords(convertNum($feeWhole)) . ' Dirhams';
            $feeDecimal = ucwords(convertNum($feeDecimal)) . ' Fils';

            $t_data = "
            select receipt_data from finance_transactions      
            inner join finance_transaction_receipt_records on finance_transactions.id = finance_transaction_receipt_records.finance_transaction_id
            where finance_transactions.id = '$tr_id'
            ";
            $result_tr = $conn->query($t_data);
            $receipt_data = mysqli_fetch_row($result_tr)[0];
            $receipt_data = yaml_parse($receipt_data);
//            var_dump($receipt_data);
            $fee_particular_name =  $receipt_data['table'][':fee_particulars'][0]['table'][':name'];
            $invoice_no =  $receipt_data['table'][':invoice_no'];
            $receipt_no =  $receipt_data['table'][':receipt_no'];
            $html .=<<<EOD
<tr>
    <td width="50" height="20" >$invoice_no</td>
    <td width="50">$receipt_no</td>
    <td width="150"> $fee_particular_name</td>
    <td align="right"> $amount</td>
    <td width="60"></td>
    <td width="65"></td>
    <td align="right"> $amount</td>
    <td align="right"> $transaction_amount</td>
    <td align="right"> $balance</td>
    </tr>
EOD;
        }
        $html .=<<<EOD
        <tr>
        <td colspan="7" height="30"></td>
        <td align="left"> Total VAT</td>
        <td align="right"> </td>
        </tr>   
        <tr style="background-color: lightsalmon; font-weight: bold">
        <td colspan="7" height="30"> $feeWhole And $feeDecimal </td>
        <td align="left"> Total Paid</td>
        <td align="right"> $row[transaction_amount]</td>
        </tr>
        <tr >
        <td height="20"  colspan="9"> Payment Mode : $row[transaction_mode] <br> Reference: $row[reference_no]
          <br> Cashier : $cname </td>
    </tr>
</tbody></table>

<table style="padding: 20px;">
<tbody >
            <tr>
                    <td width="325">
                        <table  cellspacing="0" cellpadding="1" border="1">
                                <tr>
                                <td>
                                    <table>
                                    <tr><td  colspan="2">Bank Details:</td></tr>
                                    <tr><td width="100">Bank</td><td>: First Abu Dhabi Bank</td></tr>
                                    <tr><td>Branch</td><td>: Salam Branch, Al Ain</td></tr>
                                    <tr><td width="100">Account Name</td><td>: Al Sanawbar School</td></tr>
                                    <tr><td>Account No</td><td>: 101 100 2036658 016</td></tr>
                                    <tr><td>IBAN</td><td  width="200">: AE420351011002036658016</td></tr>
                                    <tr><td>Currency</td><td>: AED</td></tr>
                                    <tr><td>Swift Code</td><td>: FGBMAEAA</td></tr>
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
                                <tr><td>
                                Notes:<br>
                                1. Please ensure that the above invoice amount is credited to our account after deduction of all bank charges.<br>
                                2. Kindly email student name, grade, family ID and bank transfer receipt.
                               </td> </tr>
                        </table>
                    </td>
                   
            </tr>
</tbody>
</table>
EOD;

    }
}




$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('Invoice-'.$ledger_id.'.pdf', 'I');
$pdf->Close();



