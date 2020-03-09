<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = $_REQUEST['grade'];


$sql = "select batches.name name
from batches
         inner join courses on batches.course_id = courses.id
where ( courses.course_name = ' $grade' or courses.course_name = '$grade')
  and batches.is_deleted = 0
  and batches.is_active = 1
 and batches.name LIKE '%2020%'
  order by batches.name ";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['name']  . "\t";
}

$conn->close();