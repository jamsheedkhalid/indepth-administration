<?php

session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$total_amount = 0;

if (isset($_REQUEST['start_date'], $_REQUEST['end_date'])) {


    $sql = "select DISTINCT
                finance_transaction_ledgers.amount           transaction_amount,
                finance_transaction_ledgers.transaction_data           transaction_data,
                finance_transaction_ledgers.transaction_date transaction_date,
                finance_transaction_ledgers.payment_mode     transaction_mode,
                finance_transaction_ledgers.reference_no     reference_no,
                finance_transaction_ledgers.id               ledger_id
from finance_transaction_ledgers
    where (finance_transaction_ledgers.transaction_date BETWEEN '$_REQUEST[start_date]' AND '$_REQUEST[end_date]')
 and transaction_data IS NOT NULL  and status = 'ACTIVE'
    order by finance_transaction_ledgers.id desc;
 ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        echo ' <div class="table-responsive" >
                                    <table id="invoiceTable" style="overflow: scroll; font-size: 12px; "
                                    class="display table table-striped">

                                        <thead >
                                        <tr align="center">
                                            <th width="15">ID</th>
                                            <th width="25" style=" white-space: nowrap">Family ID</th>
                                            <th style=" white-space: nowrap">Parent</th>
                                            <th width="30">Amount</th>
                                            <th>Mode</th>
                                            <th  style=" white-space: nowrap">Transaction Date</th>
                                            <th  width="10" style=" white-space: nowrap">Ref.</th>
                                            <th width="15"> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
        while ($row = mysqli_fetch_array($result)) {
            $total_amount += $row['transaction_amount'];
            $transaction_data = $row['transaction_data'];
            $data = yaml_parse($transaction_data);

//            var_dump($data);

//            echo '<br>'. $data['table'][':payee']['table'][':guardian_name'];

            echo '<tr>';
            echo '<td  >' . $row['ledger_id'] . '</td>';
            echo '<td style=" white-space: nowrap"></td>';
            echo '<td>'.$data['table'][':payee']['table'][':guardian_name'].'</td>';
            echo '<td  align="right">' . number_format($row['transaction_amount'],2) .'</td>';
            echo '<td>' . $row['transaction_mode'] . '</td>';
            echo '<td align="center">' . $row['transaction_date'] . '</td>';
            echo '<td>' . $row['reference_no'] . '</td>';

            echo '<td>
        <form method="post" target="_blank"  action="/mysql/tax-invoice/print-invoice.php">
                                                <button type="submit" name="printTaxInvoice" value="' . $row['ledger_id'] . '" title="View Invoice"
                                            class="btn-shadow btn btn-outline-light">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-eye fa-w-20"></i>
                                        </span>

                                    </button>
        </form></td>';
            echo '</tr>';


        }
        echo '</tbody></table>';
        echo '<h2 style="font-weight: bolder; color: darkred"> Total Collection: AED '.number_format($total_amount,2).'</h2>';

    } else {
        echo "<div class='alert alert-danger fade show' role='alert'> <strong>No Invoice Available for ".date('d-M-Y',strtotime($_REQUEST['start_date'])). ' to ' .date('d-M-Y',strtotime($_REQUEST['end_date'])). '!</strong> Please try for different dates.</div>';
    }
} else {
    echo "<div class='alert alert-danger fade show' role='alert'><strong> No Invoice Available for ".date('d-M-Y',strtotime($_REQUEST['start_date'])). ' to ' .date('d-M-Y',strtotime($_REQUEST['end_date'])). '!</strong> Please try for different dates.</div>';
}

