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


$sql = "select id from employees where user_id = '$emp_id'; ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $emp_id = $row['id'];
    }
}
$timestamp = date('Y-m-d H:i:s');

$save_task = "INSERT INTO indepth_weekly_planner (id, employee_id, subject_id, student_list, title, content, duedate, attachment_file_name,
                                    attachment_content_type, attachment_file_size, attachment_updated_at, created_at, updated_at, school_id)
VALUES (NULL, '$emp_id', '$subject', '$student', '$title', '$content', '$date', '$name', '$the_content_type', '$size', NULL, '$timestamp', '$timestamp', '1')";

//echo $save_task;
if ($conn->query($save_task) === TRUE) {
    echo 'New task created successfully';
} else {
    echo 'Error: ' . $save_task . '<br>' . $conn->error;
}

$conn->close();




function get_mime_type($file) {
    $mtype = false;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file);
        finfo_close($finfo);
    } elseif (function_exists('mime_content_type')) {
        $mtype = mime_content_type($file);
    }
    return $mtype;
}
