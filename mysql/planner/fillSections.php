<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$grade = $_REQUEST['grade'];
$section ='';

$sql = "select distinct batches.name name , batches.id id
from employees_subjects
         inner join employees on employees_subjects.employee_id = employees.id
         inner join subjects on employees_subjects.subject_id = subjects.id
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where employees.user_id = '$_SESSION[user]' and courses.id = '$grade'
  and batches.is_active = 1
  and batches.is_deleted = 0
  and subjects.is_deleted = 0
  and courses.is_deleted = 0; ";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $section .= ' <option value='.$row['id'].'>'. $row['name'] .'</option>';
    }


    echo $section;
} else {
    echo '<option selected disabled> No Sections </option>';
}

$conn->close();

