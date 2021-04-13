<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];
$type = $_REQUEST['type'];

//    echo "exam \t";
if($type === 'sectionWise'){
$sql = "select  
     (CASE WHEN exam_groups.name LIKE '%Term 1%' THEN 'Term 1' 
           WHEN exam_groups.name LIKE '%Term 2%' THEN 'Term 2' 
            WHEN exam_groups.name LIKE '%Term 3%' THEN 'Term 3' END) as  exam
from exam_groups inner join batches on exam_groups.batch_id = batches.id
inner join courses on batches.course_id = courses.id
         where batches.name = '$section' and ( course_name = '$grade' or course_name = ' $grade') 
  and courses.is_deleted = 0
  and batches.is_deleted = 0
  and batches.is_active = 1 group by exam ";
}
else if($type === 'gradeWise'){
   $sql = " select distinct
      (CASE WHEN exam_groups.name LIKE '%Term 1%' THEN 'Term 1' 
           WHEN exam_groups.name LIKE '%Term 2%' THEN 'Term 2' 
            WHEN exam_groups.name LIKE '%Term 3%' THEN 'Term 3' END) as  exam
from exam_groups
         inner join batches on exam_groups.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where ( course_name = '$grade' or course_name = ' $grade') 
  and courses.is_deleted = 0
  and batches.is_deleted = 0
  and batches.name LIKE '%2021%'
  and batches.is_active = 1 and exam_groups.inactive = 0 group by exam";

}
//echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['exam']  . "\t";
}

$conn->close();