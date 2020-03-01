<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$student = $_REQUEST['selected_students'];
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];

$size = null;
$the_content_type = '';


$temp = explode('.', $_FILES['file']['name']);
$name = round(microtime(true)) . '.' . end($temp);
$temp_name = $_FILES['file']['tmp_name'];
$size = $_FILES['file']['size'];
//echo $name;

if (isset( $_FILES['file']['name']) and !empty( $_FILES['file']['name'])) {
    $location = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    if (move_uploaded_file($temp_name, $location . $name)) {
        if(is_file($location.$name)) {
            $the_content_type = mime_content_type($location.$name);

        }
    }
} else {
    $name = '';
}

$sql = "UPDATE indepth_weekly_planner  
                SET 
                    student_list = '$student', 
                    title = '$title', 
                    content = '$content',
                    attachment_file_name = '$name', 
                    attachment_content_type = '$the_content_type', 
                    attachment_file_size = '$size', 
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





