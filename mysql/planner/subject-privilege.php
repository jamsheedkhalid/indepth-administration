<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];
$subject_name = '';
$subject_array = [];
$is_teaching = 0;
//echo $id;

$sql = " select name from subjects where id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $subject_name = $row['name'];
    }
}
//echo $sql;
//echo "--". $subject_name;

$sql = "select subjects.id id, subjects.name name from subjects
inner join batches on subjects.batch_id = batches.id
inner join courses on batches.course_id = courses.id
where courses.id = '$grade'
and batches.is_active = 1
and subjects.is_deleted = 0
and batches.is_deleted = 0
and courses.is_deleted = 0
  and batches.name LIKE '%2020%'
and subjects.name = '$subject_name';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $subject_array[] = $row['id'];
    }
}

//foreach ($subject_array as $key => $val) {
//    echo "$val" . "\n";
//}

foreach ($subject_array as $key => $val) {

    $sql = "select subjects.id, subjects.name
from employees_subjects
         inner join employees on employees_subjects.employee_id = employees.id
         inner join subjects on employees_subjects.subject_id = subjects.id
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where employees.user_id = '$_SESSION[user]' and course_id = '$grade'
  and subjects.id = $val
  and subjects.is_deleted = 0; ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
            $is_teaching = 1;
            break;
    } else {
        $is_teaching = 0;
    }

}
echo $is_teaching;


$conn->close();

