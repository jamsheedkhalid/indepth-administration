<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];

echo ' <div class="modal-body">';


$sql = "select id,employee_id, subject_id, student_list, title, content,attachment_file_name file, duedate, updated_at from indepth_weekly_planner where id = '$id'; ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<div class="row"> <div class="col"><label> Date: <b> ' . date('l, d-m-Y', strtotime($row['duedate'])) . '</b></label></div>';
        $sql_emp = "select first_name from employees where id = '$row[employee_id]'; ";
        $result_emp = $conn->query($sql_emp);

        if ($result_emp->num_rows > 0) {
            while ($row_emp = mysqli_fetch_array($result_emp)) {
                echo '<div class="col" align="right"><label> <i><small>Created by: ' . $row_emp['first_name'] . '</small></i></label>
                           <label> <small><i> Last updated on: ' . date('d-F-Y h:i a', strtotime($row['updated_at'])) . '</i></small> </label> </div>';
            }
        }
        echo '</div>';


        echo '<div class="row"><div class="col"><label>Grade: <b>' . $grade . '</b></label></div>';

        $sql_sub = "select name from subjects where id = '$row[subject_id]'; ";
        $result_sub = $conn->query($sql_sub);
        while ($row_sub = mysqli_fetch_array($result_sub)) {
            echo '<div align="right" class="col"><label> Subject:<b> ' . $row_sub['name'] . '</b></label>
</div></div>';
        }
        echo '<div><label>Title: <b> ' . $row['title'] . '</b></label></div>';
        echo '<label>Content:</label><div style="max-height: 200px; overflow-y: scroll">  <b> ' . $row['content'] . '</b></div>';
        if ($row['file'] != '') {
            echo '<br><label for="download_file">Attachment: </label>';
            echo "<a id='download_file' href='/mysql/planner/view-planner/download.php?name=" . $row['file'] . "'> Download</a> ";

        }
        else  {
            echo '<br><label for="download_file">Attachment: Not available </label>';

        }

        echo ' </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-danger" onclick="delTask(' . $row['id'] . ')">Delete</button>
                        <button class="btn btn-success" onclick="editTask(' . $row['id'] . ')">Edit</button> ';

    }
}


$conn->close();





