<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


$id = $_REQUEST['id'];
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];

echo ' <div class="modal-body">';


$sql = "select id,employee_id, subject_id, student_list, title, content, attachment_file_name file, duedate from indepth_weekly_planner where id = '$id'; ";
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
        echo "</div>";


        echo '<div class="row"><div class="col"><label>Section: <b>' . $grade . ' ' . $section . '</b></label></div>';

        $sql_sub = "select name from subjects where id = '$row[subject_id]'; ";
        $result_sub = $conn->query($sql_sub);
        while ($row_sub = mysqli_fetch_array($result_sub)) {
            echo '<div align="right" class="col"><label> Subject:<b> ' . $row_sub['name'] . '</b></label></div></div>';
        }
        echo '<div><label>Title: <b> ' . $row['title'] . '</b></label></div>';

        echo '<label>Content:</label><div style="max-height: 200px; overflow-y: scroll">  <b> ' . $row['content'] . '</b></div>';
        if($row['file'] != '') {
            echo '<br><label for="download_file">Attachment: </label>';
            echo "<a id='download_file' href='/mysql/planner/view-planner/download.php?name=" . $row['file'] . "'> Download</a> ";

        }

        echo ' </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> ';

    }
}


$conn->close();





