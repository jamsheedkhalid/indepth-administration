<?php
include($_SERVER['DOCUMENT_ROOT'].'/indepth-administration/config/database.php');
$grade = $_REQUEST['grade'];


$sql = "select students.last_name
from students
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where course_name = '$grade'
order by students.last_name";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['last_name'] . "\t";
}

$conn->close();