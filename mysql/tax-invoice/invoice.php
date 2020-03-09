<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
setlocale(LC_MONETARY, 'en_US');
session_start();
$ledger_id = $_POST['printTaxInvoice'];


echo '<h4> Ledger ID :' .$ledger_id . '</h4>';
$sql = " select transaction_data from finance_transaction_ledgers where id = '$ledger_id'  ";
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = mysqli_fetch_array($result)){
        echo  json_encode($row['transaction_data']);
       echo  json_last_error();
    }
}

