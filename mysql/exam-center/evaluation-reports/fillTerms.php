<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$student = $_REQUEST['student'];

$sql = "

select students.admission_no, exam_groups.name exam
from students
         inner join exam_groups on students.batch_id = exam_groups.batch_id
where students.last_name = \"$student\"
AND (exam_groups.name = 'Term 1 - Class Evaluation' OR exam_groups.name = 'Term 2 - Class Evaluation' OR exam_groups.name = 'Term 3 - Class Evaluation' );
";
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['exam']  . "\t";
}

$conn->close();