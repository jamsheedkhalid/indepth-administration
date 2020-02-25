<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];
;

$sql = " select last_name name, students.id id from students 
        inner join batches on students.batch_id = batches.id
        inner join courses on batches.course_id = courses.id
        where students.batch_id = '$section' and courses.id = '$grade'";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $section = ' <option class="select_all" value=""  > Select All </option>';
    while ($row = mysqli_fetch_array($result)) {
        $section .= ' <option  value=' . $row['id'] . '>' . $row['name'] . '</option>';
    }


    echo $section;
} else {
    echo '<option selected disabled> No Students </option>';
}

$conn->close();





