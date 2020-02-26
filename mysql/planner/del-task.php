<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();
$id = $_REQUEST['id'];

echo $id;

$del_task = " delete from indepth_weekly_planner where id = '$id'";

echo $del_task;
if ($conn->query($del_task) === TRUE) {
    echo 'Task deleted successfully';
} else {
    echo 'Error: ' . $del_task . '<br>' . $conn->error;
}
$conn->close();