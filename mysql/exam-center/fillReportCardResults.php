<?php
include($_SERVER['DOCUMENT_ROOT'] . '/indepth-administration/config/database.php');
$student = $_REQUEST['student'];
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];
$term = $_REQUEST['term'];
$result_params1 = 43;
$result_params2 = 57;

$sql = "
select p.last_name                                                                                           name,
       courses.course_name                                                                                   grade,
       batches.name                                                                                          section,
       subjects.name                                                                                         subject,
       round(exams.maximum_marks, 0)                                                                         max,
       round(exams.minimum_marks, 0)                                                                         min,
       round(MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, NULL)), 0)            ASS,
       round(MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, NULL)), 0)                               TE,
       round(MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, NULL)) * $result_params2 / 100 +
             MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, NULL)) * $result_params1 / 100, 0) TR

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
    $total_max = $total_min = $total_ASS = $total_TE = $total_TR = 0;
    echo '
    <table class="table table-hover table-sm mb-0 table-bordered student-report-card-result">
    <thead>
    <tr align="center">
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
 <td >'.$row['subject'] .'</td>
 <td align="center">'.$row['max'] .'</td>
 <td align="center">'.$row['min'] .'</td>
 <td align="center">'.$row['ASS'] .'</td>
 <td align="center">'.$row['TE'] .'</td>
 <td align="center">'.$row['TR'] .'</td>
</tr>
';
        $total_max += $row['max'];
        $total_min += $row['min'];
        $total_ASS += $row['ASS'];
        $total_TE += $row['TE'];
        $total_TR += $row['TR'];
    }
    echo '<tfoot style="font-weight: bold!important;"><td>Total</td><td align="center">'.$total_max.'</td><td align="center">'.$total_min.'</td><td align="center">'.$total_ASS.'</td><td align="center">'.$total_TE.'</td><td align="center">'.$total_TR.'</td></th>';
    echo ' </tbody>
</table>';

    echo "
<div class='col-sm-7' ><table class='table table-sm mb-0  mt-5 params-table' >
<tr><td>ASS. 3</td><td>1st Assessment 2nd Term</td><td align='right'>$result_params1 %</td></tr>
<tr><td>T.E.2</td><td>2nd Term</td><td align='right'>$result_params2 %</td></tr>
<tr><td>T.R.2</td><td>2nd Term Result</td><td align='right'>100 %</td></tr>
</table></div>";



}

$conn->close();