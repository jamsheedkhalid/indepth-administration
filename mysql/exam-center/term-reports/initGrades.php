<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$sql = 'SELECT  course_name FROM courses where is_deleted = 0 and course_name LIKE "%GR%" ORDER BY course_name';
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['course_name'] . "\t";
}

$conn->close();