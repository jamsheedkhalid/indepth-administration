<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$student = $_REQUEST['selected_students'];
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$id = $_REQUEST['id'];


include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];
//echo $id;

$sql = "select subjects.id, subjects.name
from employees_subjects
         inner join employees on employees_subjects.employee_id = employees.id
         inner join subjects on employees_subjects.subject_id = subjects.id
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where employees.user_id = '$_SESSION[user]' and course_id = '$grade'
  and subjects.is_deleted = 0; ";
//echo $sql;
$is_teaching = 0;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        if ($row['id'] === $id) {
            $is_teaching = 1;
            break;
        }
        $is_teaching = 0;
    }


} else {
    $is_teaching = 0;
}
echo $is_teaching;













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





