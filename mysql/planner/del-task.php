<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$id = $_REQUEST['id'];
$gradeID = $_REQUEST['grade'];
//echo $id;

$is_teaching = 0;

$sql_sub = "select subject_id from indepth_weekly_planner where id = '$id'";
$result_sub = $conn->query($sql_sub);
if ($result_sub->num_rows > 0) {
    while ($row_sub = mysqli_fetch_array($result_sub)) {

        $sql = "select subjects.id, subjects.name
from employees_subjects
         inner join employees on employees_subjects.employee_id = employees.id
         inner join subjects on employees_subjects.subject_id = subjects.id
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where employees.user_id = '$_SESSION[user]' and course_id = '$gradeID'
  and subjects.is_deleted = 0; ";
//echo $sql;
        $is_teaching = 0;

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($row['id'] === $row_sub['subject_id']) {

                    $del_task = " delete from indepth_weekly_planner where id = '$id'";

                    echo $del_task;
                    if ($conn->query($del_task) === TRUE) {
                        echo 'Task deleted successfully';
                    } else {
                        echo 'Error: ' . $del_task . '<br>' . $conn->error;
                    }

                    $is_teaching = 1;
                    break;
                }
                $is_teaching = 0;
            }
        } else {
            $is_teaching = 0;
        }


    }
} else {
    $is_teaching = 0;
}

if ($is_teaching === 0) {
    echo $is_teaching;
}


$conn->close();


