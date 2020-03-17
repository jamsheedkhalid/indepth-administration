<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/include/digitToWord.php');
session_start();

class TAX_PDF2 extends TCPDF
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
        $this->Cell(120, 10, 'FEE COLLECTION REPORT', 0, 2, 'R');
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

        $this->SetY(-15);
        // times italic 8
        $this->SetFont('times', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Printed by ' . $_SESSION['name'], 0, 0, 'L');
        $this->Cell(0, 10, 'Printed on ' . $date, 0, 0, 'R');
    }
}

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

$pdf = new TAX_PDF2('P');
$pdf->SetTitle('Tax Report');
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
$pdf->Cell(0,0,'Report: '.date('D, d-M-Y',strtotime($start_date)) . ' To ' . date('D, d-M-Y',strtotime($end_date)),'','2','C' );


 $table =<<<EOD
 <br><br>
<table  style="padding: 10px; width: 100%"  cellspacing="0" cellpadding="1" border="1" class="table table-bordered table-dark">
    <thead style="padding: 10px; width: 100%">
    <tr style="background-color: lightgray">
    <th> DATE</th>
    <th> CASH</th>
    <th> CHEQUE</th>
    <th> VISA</th>
    <th style="white-space: nowrap"> BANK</th>
    <th> OTHERS</th>
    <th> TOTAL</th>
    </tr>
    </thead>
    <tbody>
EOD;

$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    new DateTime($end_date)
);
$total_cash = $total_cheque = $total_visa = $total_bank = $total_others = $total_total = 0;
foreach ($period as $key => $value) {
    $date = $value->format('Y-m-d')  ;
    $sql = " SELECT id, amount, transaction_data, transaction_data  from finance_transaction_ledgers where transaction_date = '$date' and transaction_data is not null and status = 'ACTIVE'";
   $result = $conn->query($sql);
   if($result->num_rows > 0){
       $cash = $cheque = $visa = $bank = $others = $total = 0;
       while($row = mysqli_fetch_array($result)){
           $transaction_data = $row['transaction_data'];
           $data = yaml_parse($transaction_data);
           $payment_mode =  $data['table'][':transactions'][''][0]['table'][':payment_mode'];

           if ($payment_mode == 'Cash'){
                    $cash += (double)$row['amount'];
                    $total_cash +=(double)$row['amount'];

           } else  if ($payment_mode == 'Cheque'){
                    $cheque += (double)$row['amount'];
                    $total_cheque += (double)$row['amount'];
           }else  if ($payment_mode == 'Card Payment'){
                    $visa += (double)$row['amount'];
                    $total_visa += (double)$row['amount'];
           }else  if ($payment_mode == 'Online Payment'){
                    $bank += (double)$row['amount'];
                    $total_bank += (double)$row['amount'];
           }else   {
               $others += (double)$row['amount'];
               $total_others += (double)$row['amount'];
           }

           $total += (double)$row['amount'];
           $total_total += (double)$row['amount'];

       }
   }

   $dateFormatted = date('d-m-Y', strtotime($date));
    $table .=<<<EOD
        <tr  >
            <td > $dateFormatted </td>
            <td align="right"> $cash </td>
            <td align="right"> $cheque </td>
            <td align="right"> $visa </td>
            <td align="right"> $bank </td>
            <td align="right"> $others </td>
            <td align="right"> $total </td>
        </tr>

EOD;
}
$total_cash = number_format($total_cash,2);
$total_cheque = number_format($total_cheque,2);
$total_visa = number_format($total_visa,2);
$total_bank = number_format($total_bank,2);
$total_others = number_format($total_others,2);
$total_total = number_format($total_total,2);

$table .=<<<EOD
    </tbody>
    <tfoot>
    <tr style="font-weight: bolder ;background-color: lightpink">
    <td> Grand Total</td>
    <td align="right"> $total_cash </td>
    <td align="right"> $total_cheque </td>
    <td align="right"> $total_visa </td>
    <td align="right"> $total_bank </td>
    <td align="right"> $total_others </td>
    <td align="right"> $total_total </td>
</tr>
</tfoot>
</table>

EOD;





$pdf->writeHTML($table, true, false, true, false, '');
ob_end_clean();
$pdf->Output('Invoice Report.pdf', 'I');
$pdf->Close();

