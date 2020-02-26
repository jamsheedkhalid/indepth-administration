<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$grade = array();

$sql = "select distinct courses.course_name course_name, courses.id course_id from employees_subjects
inner join employees on employees_subjects.employee_id = employees.id
inner join subjects on employees_subjects.subject_id = subjects.id
inner join batches on subjects.batch_id = batches.id
inner join courses on batches.course_id = courses.id
where employees.user_id = '$_SESSION[user]' and batches.is_active = 1 and batches.is_deleted = 0
and subjects.is_deleted = 0 and courses.is_deleted=0 and courses.id != 13 and courses.id != 14 order by course_name; ";

//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {

       $grade[] = $row;

    }

    echo json_encode($grade);
} else {
    echo 'No Grades;';
}

$conn->close();