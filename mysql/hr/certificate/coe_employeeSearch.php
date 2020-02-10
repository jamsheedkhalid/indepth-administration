<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$Name = $_REQUEST['emp'];

$Query = "SELECT CONCAT(employees.first_name,' ',employees.middle_name,' ',employees.last_name) Name FROM employees "
    . "WHERE employee_number LIKE '%$Name%' OR first_name LIKE '%$Name%' OR last_name LIKE '%$Name%' OR  middle_name  LIKE '%$Name%' ORDER BY '$Name' DESC; ";
//echo $Query;
$name = '';
//Query execution
$result = $conn->query($Query);
if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_array($result)) {
        $name.= "". $row['Name'].",";
//        $name[] = $row[$name];
    }

    echo  $name;
}

else{
    echo 'NIL';
}