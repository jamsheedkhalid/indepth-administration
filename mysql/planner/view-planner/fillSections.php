<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = $_REQUEST['grade'];
$section ='';

$sql = "select distinct batches.name name , batches.id id
from  batches 
         inner join courses on batches.course_id = courses.id
where courses.id = '$grade'
  and batches.is_active = 1
  and batches.is_deleted = 0
  and courses.is_deleted = 0; ";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $section .= ' <option value='.$row['id'].'>'. $row['name'] .'</option>';
    }


    echo $section;
} else {
    echo '<option selected disabled> No Sections </option>';
}

$conn->close();

