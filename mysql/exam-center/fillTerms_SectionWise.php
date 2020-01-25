<?php
include($_SERVER['DOCUMENT_ROOT'].'/indepth-administration/config/database.php');
$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];
$type = $_REQUEST['type'];

//    echo "exam \t";
if($type === 'sectionWise'){
$sql = "select  exam_groups.name exam
from exam_groups inner join batches on exam_groups.batch_id = batches.id
inner join courses on batches.course_id = courses.id
         where batches.name = '$section' and ( course_name = '$grade' or course_name = ' $grade')order by exam_groups.id desc ";
}
else if($type === 'gradeWise'){
   $sql = " select distinct  exam_groups.name exam
from exam_groups inner join batches on exam_groups.batch_id = batches.id
inner join courses on batches.course_id = courses.id
where ( course_name = '$grade' or course_name = ' $grade') order by exam_groups.id desc ";

}
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['exam']  . "\t";
}

$conn->close();