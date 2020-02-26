<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];
;

$sql = "select subjects.id id,subjects.name name
from employees_subjects
         inner join employees on employees_subjects.employee_id = employees.id
         inner join subjects on employees_subjects.subject_id = subjects.id
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where employees.user_id = '5047' and batches.id = '$section' and courses.id = '$grade'
  and courses.id = '1'
  and batches.is_active = 1
  and batches.is_deleted = 0
  and subjects.is_deleted = 0
  and courses.is_deleted = 0; ";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $section .= ' <option  value=' . $row['id'] . '>' . $row['name'] . '</option>';
    }


    echo $section;
} else {
    echo '<option selected disabled> No Students </option>';
}

$conn->close();





