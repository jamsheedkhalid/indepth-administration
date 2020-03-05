<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


if (isset($_REQUEST['start_date'], $_REQUEST['end_date'])) {


    $sql = "select fee_invoices.invoice_number invoice_no,
       students.last_name student,
       students.admission_no admission_no,
       guardians.first_name guardian,
       round(finance_fees.particular_total,2) total_amount,
       round(finance_fees.balance,2) due_balance,
       finance_fee_collections.name fee_name,
       finance_fee_collections.due_date due_date,
       finance_fee_collections.start_date start_date,
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

where fee_invoices.is_active = 1 and (finance_fee_collections.start_date BETWEEN '$_REQUEST[start_date]' AND '$_REQUEST[end_date]')
order by fee_invoices.created_at desc;
 ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo ' <div class="table-responsive" >       
                                    <table id="invoiceTable" style="overflow: scroll; font-size: 12px; "
                                    class="display">                                    

                                        <thead >
                                        <tr align="center">
                                            <th  >Inv. #</th>
                                            <th> Std. ID</th>
                                            <th> Student</th>
                                            <th style=" white-space: nowrap"> Grade</th>
                                            <th> Fees Name</th>
                                            <th> Amount</th>
                                            <th  style=" white-space: nowrap"> Date</th>
                                            <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td  >' . $row['invoice_no'] . '</td>';
//        echo '<td>'.$row['guardian'].'</td>';
            echo '<td>' . $row['admission_no'] . '</td>';
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
                                                <button type="submit" name="printTaxInvoice" value="' . $row['invoice_no'] . '" title="View Invoice"
                                            class="btn-shadow btn btn-outline-light">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-eye fa-w-20"></i>
                                        </span>

                                    </button>
        </form></td>';
            echo '</tr>';


        }
        echo '</tbody><tfoot><tr> <th>Invoice</th> <th>ID</th> <th>Student</th> <th>Grade</th> <th>Fees</th> <th>Amount</th><th>Date</th> </tr></tfoot></table>

';
    } else {
        echo "<div class='alert alert-danger fade show' role='alert'> <strong>No Invoice Available!</strong> Please try for different dates.</div>";
    }
} else {
    echo "<div class='alert alert-danger fade show' role='alert'><strong> No Invoice Available!</strong> Please try for different dates.</div>";
}

