<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$user_id = $_SESSION['user'];
//echo $user_id;

$sql = "select distinct exam_groups.name term from students
inner join users on students.user_id = users.id
inner join exam_groups on students.batch_id = exam_groups.batch_id
inner join  guardians on students.immediate_contact_id = guardians.id
where guardians.user_id = '$user_id'";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['term']  . "\t";
}

$conn->close();
//echo 'Term 1'  . "\t";