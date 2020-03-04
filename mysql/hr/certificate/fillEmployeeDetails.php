<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$id = $_REQUEST['id'];
$details = array();
$Query = "SELECT nationality_id,
       CONCAT(employees.first_name,' ',employees.middle_name,' ',employees.last_name) Name,
       employee_positions.name job_title,
       joining_date,
       employee_number,
       first_name,
       gender,
       employee_additional_details.additional_info passport,
       countries.name                              nationality,
       employee_salary_structures.net_pay salary
from employees
         left join employee_additional_details on employees.id = employee_additional_details.employee_id and additional_field_id = 13
         left join countries on employees.nationality_id = countries.id
         inner join employee_positions on employees.employee_position_id = employee_positions.id 
left join employee_salary_structures on employees.id = employee_salary_structures.employee_id
where  employees.employee_number = '$id' ; ";

//Query execution
$result = $conn->query($Query);
if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_array($result)) {
        $details['joinDate'] = date("d-M-Y", strtotime($row['joining_date']));
        $details['passport'] = $row['passport'];
        $details['nationality'] = $row['nationality'];
        $details['job_title'] = $row['job_title'];
        $details['employee_number'] = $row['employee_number'];
        $details['name'] = $row['Name'];
        $details['gender'] = $row['gender'];
        $details['salary'] = $row['salary'];
    }

    echo json_encode($details);
}