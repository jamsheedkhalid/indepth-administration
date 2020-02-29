<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$student = $_REQUEST['selected_students'];
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];
$subject = $_REQUEST['subject'];
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$emp_id = $_SESSION['user'];
$date = $_REQUEST['date'];

$name       = $_FILES['file']['name'];
$temp_name  = $_FILES['file']['tmp_name'];
if(isset($name) and !empty($name)){
    $location =$_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    if(move_uploaded_file($temp_name, $location.$name)){
//        echo 'File uploaded successfully';
    }}

//$student_list = '';
//
//$students = explode(',', $student);
//$ln =  count($students);
//$ln--;
//while($ln >= 0){
//    $student_list .=  (string)$students[$ln]. ', ';
//    $ln--;
//}

$sql = "select id from employees where user_id = '$emp_id'; ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $emp_id = $row['id'];
    }
}

$save_task = "INSERT INTO indepth_weekly_planner (id, employee_id, subject_id, student_list, title, content, duedate, attachment_file_name, 
                                    attachment_content_type, attachment_file_size, attachment_updated_at, created_at, updated_at, school_id) 
VALUES (NULL, '$emp_id', '$subject', '$student', '$title', '$content', '$date', NULL, NULL, NULL, NULL, CURRENT_DATE(), CURRENT_DATE(), '1')";

//echo $save_task;
if ($conn->query($save_task) === TRUE) {
    echo 'New record created successfully';
} else {
    echo 'Error: ' . $save_task . '<br>' . $conn->error;
}

$conn->close();





