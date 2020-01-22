<?php
include($_SERVER['DOCUMENT_ROOT'] . '/indepth-administration/config/database.php');
$student = $_REQUEST['student'];
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];
$term = $_REQUEST['term'];

$sql = "
select p.last_name                                                                                           name,
       courses.course_name                                                                                   grade,
       batches.name                                                                                          section,
       subjects.name                                                                                         subject,
       round(exams.maximum_marks, 0)                                                                         max,
       round(exams.minimum_marks, 0)                                                                         min,
       round(MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, NULL)), 0)            ASS1,
       round(MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, NULL)), 0)                               TE1,
       round(MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, NULL)) * 57 / 100 +
             MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, NULL)) * 43 / 100, 0) TR

from students p
         inner join exam_scores on p.id = exam_scores.student_id
         inner join exams on exam_scores.exam_id = exams.id
         inner join exam_groups on exams.exam_group_id = exam_groups.id
         inner join batches on exam_groups.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join subjects on exams.subject_id = subjects.id


where (exam_groups.name = 'Term 1' or exam_groups.name = 'Term 1 - Class Evaluation')
  and p.last_name = '$student'
group by subjects.id
";
//echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo '
    <table class="table">
    <thead>
    <tr>
    <th>Subjects</th>
    <th>Max Mark</th>
    <th>Min Mark</th>
    <th>ASS. 3</th>
    <th>T.E.2</th>
    <th>T.R.2</th>
</tr>
</thead>
<tbody>';
    while ($row = mysqli_fetch_array($result)) {
        echo ' <tr>
 <td>'.$row[] .'</td>
</tr>

';
    }

    echo ' </tbody>
</table>';
}

$conn->close();