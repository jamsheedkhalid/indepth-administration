<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


if (isset($_REQUEST['start_date'], $_REQUEST['end_date'])) {


    $sql = " SELECT id, payment_mode, transaction_date, amount, reference_no, payee_type, payee_id
    from finance_transaction_ledgers
    where (transaction_date BETWEEN '$_REQUEST[start_date]' AND '$_REQUEST[end_date]')
    order by transaction_date desc;
 ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo ' <div class="table-responsive" >       
                                    <table id="invoiceTable" style="overflow: scroll; font-size: 12px; "
                                    class="display">                                    

                                        <thead >
                                        <tr align="center">
                                            <th  >ID</th>
                                            <th> Payment Mode</th>
                                            <th> Payee Type</th>
                                            <th > Payee ID</th>
                                            <th> Reference #</th>
                                            <th> Amount</th>
                                            <th  style=" white-space: nowrap"> Transaction Date</th>
                                            <th> Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td  >' . $row['id'] . '</td>';
//        echo '<td>'.$row['guardian'].'</td>';
            echo '<td>' . $row['payment_mode'] . '</td>';
            echo '<td>' . $row['payee_type'] . '</td>';
            echo '<td>' . $row['payee_id'] .'</td>';
            echo '<td>' . $row['reference_no'] . '</td>';
            echo '<td>' . $row['amount'] . '</td>';
            echo '<td>' . $row['transaction_date'] . '</td>';

            echo '<td>
        <form method="post" target="_blank"  action="/mysql/tax-invoice/invoice.php">
                                                <button type="submit" name="printTaxInvoice" value="' . $row['id'] . '" title="View Invoice"
                                            class="btn-shadow btn btn-outline-light">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-eye fa-w-20"></i>
                                        </span>

                                    </button>
        </form></td>';
            echo '</tr>';


        }
        echo '</tbody><tfoot><tr> <th></th> <th></th> <th></th> <th></th> <th></th> <th></th><th></th><th></th> </tr></tfoot></table>

';
    } else {
        echo "<div class='alert alert-danger fade show' role='alert'> <strong>No Invoice Available!</strong> Please try for different dates.</div>";
    }
} else {
    echo "<div class='alert alert-danger fade show' role='alert'><strong> No Invoice Available!</strong> Please try for different dates.</div>";
}

