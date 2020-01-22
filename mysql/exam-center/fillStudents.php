<?php
include($_SERVER['DOCUMENT_ROOT'].'/indepth-administration/config/database.php');
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];


$sql = "select students.last_name, students.admission_no 
from students
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where (courses.course_name = ' $grade' or courses.course_name = '$grade') 
  AND courses.is_deleted = 0 AND batches.name LIKE '%$section'
order by students.last_name";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['last_name']  . "\t";
}

$conn->close();