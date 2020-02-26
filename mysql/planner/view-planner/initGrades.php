<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = array();

$sql = 'select distinct courses.course_name course_name, courses.id course_id from
       courses where is_deleted = 0 and courses.id != 13 and courses.id != 14 order by course_name; ';

//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {

       $grade[] = $row;

    }

    echo json_encode($grade);
} else {
    echo 'No Grades;';
}

$conn->close();