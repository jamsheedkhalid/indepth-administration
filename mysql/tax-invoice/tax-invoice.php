<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$sql = 'select fee_invoices.invoice_number invoice_no,
       students.last_name student,
       students.admission_no admission_no,
       guardians.first_name guardian,
       round(finance_fees.particular_total,2) total_amount,
       round(finance_fees.balance,2) due_balance,
       finance_fee_collections.name fee_name,
       finance_fee_collections.due_date due_date,
       finance_fees.is_paid is_paid,
        batches.name section,
       courses.course_name grade,
       finance_transactions.transaction_date pay_date,
       round(finance_transactions.amount,2) pay_amount
from fee_invoices
         inner join finance_fees on fee_invoices.fee_id = finance_fees.id
        inner join batches on finance_fees.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join students on finance_fees.student_id = students.id
         left join guardians on students.familyid = guardians.familyid
        left join finance_transactions on finance_fees.id = finance_transactions.finance_id

where fee_invoices.is_active = 1
order by fee_invoices.created_at desc;
 ';

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo ' <div style=" height:100vh;">       
                                    <table style="white-space: nowrap; font-size: 11px; " id=""  
                                    class="mb-0 table  table-hover  table-sm table-bordered mb-0 table">                                    

                                        <thead style="position: relative">
                                        <tr align="center">
                                            <th style="width: 130px !important;" >Inv. #</th>
                                            <th> Student</th>
                                            <th> Grade</th>
                                            <th> Fees Name</th>
                                            <th> Amount</th>
                                            <th> Date</th>
                                            <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td  >' . $row['invoice_no'] . '</td>';
//        echo '<td>'.$row['guardian'].'</td>';
        echo '<td>' . $row['student'] . '</td>';
        echo '<td>' . $row['grade'] . ' ' . $row['section'] . '</td>';
        echo '<td>' . $row['fee_name'] . '</td>';
        if ((int)$row['is_paid'] === 1) {
            echo '<td align="right">' . $row['pay_amount'] . '</td>';
            echo '<td> Paid on ' . date('d-m-Y', strtotime($row['pay_date'])) . '</td>';
        } else {
            echo '<td align="right">' . $row['due_balance'] . '/' . $row['total_amount'] . '</td>';
            echo '<td> Due on ' . date('d-m-Y', strtotime($row['due_date'])) . '</td>';
        }
        echo '<td>
        <form method="post" target="_blank"  action="/mysql/tax-invoice/print-tax-invoice.php">
            <button type="submit" name="printTaxInvoice" value="'.$row['invoice_no'].'">View Invoice</button>
        </form></td>';
        echo '</tr>';


    }
    echo '</tbody></table>';
}

