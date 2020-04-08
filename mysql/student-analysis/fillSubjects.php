<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];


$sql = "select distinct subjects.name name
from subjects
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where ( courses.course_name = ' $grade' or courses.course_name = '$grade')
and (batches.name in ($section) )
and subjects.is_deleted = 0 
  order by subjects.name ";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['name']  . "\t";
}

$conn->close();