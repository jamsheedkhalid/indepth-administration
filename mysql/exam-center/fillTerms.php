<?php
include($_SERVER['DOCUMENT_ROOT'].'/indepth-administration/config/database.php');
$student = $_REQUEST['student'];

$sql = "select exam_groups.name exam
from exam_groups
         inner join students on exam_groups.batch_id = students.batch_id 
         where students.last_name = '$student' ";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['exam']  . "\t";
}

$conn->close();