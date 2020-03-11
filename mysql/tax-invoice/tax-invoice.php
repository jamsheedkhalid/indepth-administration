<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


if (isset($_REQUEST['start_date'], $_REQUEST['end_date'])) {


    $sql = "select distinct guardians.familyid                           family_id,
                guardians.first_name                         parent_name,
                finance_transaction_ledgers.amount           transaction_amount,
                finance_transaction_ledgers.transaction_date transaction_date,
                finance_transaction_ledgers.payment_mode     transaction_mode,
                finance_transaction_ledgers.payment_note     transaction_note,
                finance_transaction_ledgers.id               ledger_id
from finance_transaction_ledgers
         inner join finance_transactions on finance_transaction_ledgers.id = finance_transactions.transaction_ledger_id
         inner join finance_fees on finance_transactions.finance_id = finance_fees.id
         inner join fee_invoices on finance_fees.id = fee_invoices.fee_id
         inner join finance_transaction_receipt_records
                    on finance_transactions.id = finance_transaction_receipt_records.finance_transaction_id
         inner join students on finance_fees.student_id = students.id
         inner join guardians on students.immediate_contact_id = guardians.id
    where (finance_transaction_ledgers.transaction_date BETWEEN '$_REQUEST[start_date]' AND '$_REQUEST[end_date]')
    order by finance_transaction_ledgers.id desc;
 ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo ' <div class="table-responsive" >       
                                    <table id="invoiceTable" style="overflow: scroll; font-size: 12px; "
                                    class="display">                                    

                                        <thead >
                                        <tr align="center">
                                            <th width="15">ID</th>
                                            <th width="15">Family ID</th>
                                            <th style=" white-space: nowrap">Parent</th>
                                            <th width="30">Amount</th>
                                            <th>Mode</th>
                                            <th  style=" white-space: nowrap">Transaction Date</th>
                                            <th  style=" white-space: nowrap">Transaction Note</th>
                                            <th width="15"> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td  >' . $row['ledger_id'] . '</td>';
//        echo '<td>'.$row['guardian'].'</td>';
            echo '<td style=" white-space: nowrap">' . $row['family_id'] . '</td>';
            echo '<td>' . $row['parent_name'] . '</td>';
            echo '<td  align="right">' . number_format($row['transaction_amount'],2) .'</td>';
            echo '<td>' . $row['transaction_mode'] . '</td>';
            echo '<td>' . $row['transaction_date'] . '</td>';
            echo '<td>' . $row['transaction_note'] . '</td>';

            echo '<td>
        <form method="post" target="_blank"  action="/mysql/tax-invoice/invoice.php">
                                                <button type="submit" name="printTaxInvoice" value="' . $row['ledger_id'] . '" title="View Invoice"
                                            class="btn-shadow btn btn-outline-light">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-eye fa-w-20"></i>
                                        </span>

                                    </button>
        </form></td>';
            echo '</tr>';


        }

    } else {
        echo "<div class='alert alert-danger fade show' role='alert'> <strong>No Invoice Available for ".date('d-M-Y',strtotime($_REQUEST['start_date'])). ' to ' .date('d-M-Y',strtotime($_REQUEST['end_date'])). '!</strong> Please try for different dates.</div>';
    }
} else {
    echo "<div class='alert alert-danger fade show' role='alert'><strong> No Invoice Available for ".date('d-M-Y',strtotime($_REQUEST['start_date'])). ' to ' .date('d-M-Y',strtotime($_REQUEST['end_date'])). '!</strong> Please try for different dates.</div>';
}

