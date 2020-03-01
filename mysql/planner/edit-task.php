<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];
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
//$is_teaching = true;
if ($is_teaching) {
    echo ' <div class="modal-body">';
    $sql = "select id,employee_id, subject_id, student_list, title, content, duedate from indepth_weekly_planner where id = '$id'; ";
//    echo $sql;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo '<div class="row"> <div class="col"><label> Date: <b> ' . date('l, d-m-Y', strtotime($row['duedate'])) . '</b></label></div>';
            $sql_emp = "select first_name from employees where id = '$row[employee_id]'; ";
            $result_emp = $conn->query($sql_emp);

            if ($result_emp->num_rows > 0) {
                while ($row_emp = mysqli_fetch_array($result_emp)) {
                    echo '<div class="col" align="right"><label> <i><small>Created by: ' . $row_emp['first_name'] . '</small></i></label></div>';
                }
            }
            echo '</div>';


            echo '<div class="row"><div class="col"><label>Grade: <b>' . $grade . '</b></label></div>';

            $sql_sub = "select name from subjects where id = '$row[subject_id]'; ";
            $result_sub = $conn->query($sql_sub);
            while ($row_sub = mysqli_fetch_array($result_sub)) {

                echo '<div align="right" class="col"><label> Subject:<b> ' . $row_sub['name'] . '</b></label></div></div>';
            }
            echo '<div><label for="edit_title">Title:</label> <input id="edit_title" class="form-control-sm form-control" value=" ' . $row['title'] . '"/></div>';
            echo '<br><label for="edit_content">Content:</label><textarea id="edit_content" type="text"  style="height: 100px" placeholder="Description" class="form-control-sm form-control" >' . nl2br(str_replace('<br />', ' ', $row['content'])) . '</textarea><br>';
            echo '<label for="task-select-edit" hidden>Select Student</label><select  hidden id="task-select-edit" name="task-select-edit" multiple="multiple"  size = "5" class="form-control-sm form-control select_all "><option>Select Students</option></select>';
            echo '<form method="post" enctype="multipart/form-data">';
            echo '<label for="file_upload_edit" class="">Upload File</label>'.
            '<input name="file_upload_edit" id="file_upload_edit" type="file" onchange="fileValidation(\'file_upload_edit\',\'upload_warning-edit\',\'btnUpdate\')" class="form-control-file"><br>'.
            '<small id="upload_warning-edit" style="display: none"><div class="alert alert-sm alert-danger fade show" role="alert">File too large to upload! (Max 30MB)</div></small></form> ';

            echo ' </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="btnUpdate" onclick="updateTask(' . $row['id'] . ')">Save</button> ';

        }
    }

} else {
    echo $is_teaching;
}


//echo 'flag:' . $is_teaching;


$conn->close();





