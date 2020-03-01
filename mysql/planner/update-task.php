<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$student = $_REQUEST['selected_students'];
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$id = $_REQUEST['id'];
$is_teaching = false;
$subject_name = '';
$subject_array = [];
$subject_id = '';

$sql = " select subject_id from indepth_weekly_planner where id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $subject_id = $row['subject_id'];
    }
}
//echo $subject_id;

$sql = " select name from subjects where id = '$subject_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $subject_name = $row['name'];
    }
}
//echo $sql;
//echo $id."-".$subject_name;


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
//    echo $val."   ";
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
        $is_teaching = true;
        break;
    } else {
        $is_teaching = false;
    }
}
$sql = "UPDATE indepth_weekly_planner  
                SET 
                    student_list = '$student', 
                    title = '$title', 
                    content = '$content',
                    attachment_file_name = NULL, 
                    attachment_content_type = NULL, 
                    attachment_file_size = NULL, 
                    attachment_updated_at = NULL,  
                    updated_at = CURRENT_DATE()  
             WHERE id = '$id'";

echo $sql;
if ($conn->query($sql) === TRUE) {
    echo 'Updated successfully';
} else {
    echo 'Error: ' . $sql . '<br>' . $conn->error;
}

$conn->close();





