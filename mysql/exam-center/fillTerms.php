<?php
include($_SERVER['DOCUMENT_ROOT'].'/config/database.php');
$student = $_REQUEST['student'];

$sql = "select 
       (CASE WHEN exam_groups.name LIKE '%Term 1%' THEN 'Term 1' 
           WHEN exam_groups.name LIKE '%Term 2%' THEN 'Term 2' 
            WHEN exam_groups.name LIKE '%Term 3%' THEN 'Term 3' END) as  exam
from exam_groups
         inner join students on exam_groups.batch_id = students.batch_id 
         where students.last_name = '$student' group by 
               exam
";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['exam']  . "\t";
}

$conn->close();