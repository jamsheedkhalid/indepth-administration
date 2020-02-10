<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$name = $_REQUEST['emp'];
$details = array();
$Query = ' SELECT first_name name, employee_number, id, nationality_id, gender, job_title, joining_date, first_name from employees 
       WHERE'
    . " first_name =  '$name'  ";

$result = $conn->query($Query);
if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_array($result)) {
         $details['Name'] = $row['name'];
        $details['Nationality'] = $row['nationality_id'];
        $details['Title'] = $row['job_title'];
        $details['JoinDate'] = $row['joining_date'];
        $details['Gender'] = $row['gender'];
    }

    echo json_encode($details);
}

else{
    echo '';
}