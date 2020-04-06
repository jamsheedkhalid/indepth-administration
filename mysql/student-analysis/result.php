<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = $_REQUEST['grade'];
$section = $_REQUEST['section'];
$subject = $_REQUEST['subject'];
$term = $_REQUEST['term'];
$filter = $_REQUEST['filter'];
$custom_filter = $_REQUEST['custom_filter'];
$custom_filter_less = $_REQUEST['custom_filter_less'];
$show_ar_name = $_REQUEST['show_ar_name'];


//echo $grade. '-' .$section. '-' . $subject . '-' . $term. '-'. $filter;

switch ($filter) {

    case 'all':
        $filter_sql = '  ';
        break;
    case 'failed':
        $filter_sql = '  and exam_scores.marks < exams.minimum_marks ';
        break;
    case 'passed':
        $filter_sql = '  and exam_scores.marks >= exams.minimum_marks ';
        break;

    case 'custom':
        if ($custom_filter != '') {
            $filter_sql = '   and exam_scores.marks > ' . $custom_filter;
        } else {
            $filter_sql = '   and exam_scores.marks >= 0 ';

        }
        break;
    case 'custom_less':
        if($custom_filter_less !=''){
        $filter_sql = '   and exam_scores.marks < ' . $custom_filter_less;}
        else{
            $filter_sql = '   and exam_scores.marks >= 0';
        }
        break;

    default:
        $filter_sql = '';
}

if ($show_ar_name == 'true') {
    $show_ar = 'students.first_name';
} else {
    $show_ar = 'students.last_name';
}


$sql = "
select exam_groups.name            exam_name,
       $show_ar student_name,
       students.admission_no admission_no,
       courses.course_name grade,
       batches.name section,
       subjects.name               subject,
       exam_groups.name   term,
       exam_scores.marks           mark,
       exam_scores.remarks  remark,
       exam_scores.is_failed is_failed,
       exams.minimum_marks minimum_marks,
       exams.maximum_marks maximum_marks
from exam_scores
         inner join exams on exam_scores.exam_id = exams.id
         inner join exam_groups on exams.exam_group_id = exam_groups.id
         inner join subjects on exams.subject_id = subjects.id
         inner join students on exam_scores.student_id = students.id
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where 
 ( courses.course_name = ' $grade' or courses.course_name = '$grade') AND 
 (batches.name = '$section' or batches.name = ' $section') AND 
      (subjects.name = '$subject' or subjects.name = ' $subject') AND 
      (exam_groups.name = '$term' or exam_groups.name = ' $term') 
    $filter_sql
order by student_name;
     ";
//echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "
    <table id='marks_table' class='table table-hover table-responsive-lg table-bordered  '  >
    <thead>
    <tr align='center' >
    <th>Adm. #</th>
    <th>Student</th>
    <th>Grade</th>
    <th>Subject</th>
    <th>Term</th>
    <th>Mark</th>
    <th>Remark</th>
</tr>
</thead>
<tbody>
    ";
    while ($row = mysqli_fetch_array($result)) {
        if ($row['remark'] == '') {
            if ($row['mark'] < $row['minimum_marks']) {
                $remark = "<label style='color:darkred;'> Failed </label>";
            } else if ($row['mark'] >= $row['minimum_marks']) {
                $remark = "<label style='color:darkgreen;'> Passed </label>";
            }
        } else {
            $remark = $row['remark'];
        }

        echo '
        <tr>
        <td>' . $row['admission_no'] . '</td>';

        if ($show_ar_name == 'true') {
            echo   ' <td align="right"><b>' . $row['student_name'] . '</b></td>';
        } else {
            echo   ' <td align="left"><b>' . $row['student_name'] . '</b></td>';
        }

    echo '   
        <td>' . $row['grade'] . '-' . $row['section'] . '</td>
        <td>' . $row['subject'] . '</td>
        <td>' . $row['term'] . '</td>
        <td align="right"><b>' . $row['mark'] . '</b></td>
        <td>' . $remark . '</td>
</tr>
        ';

    }

    echo '
        </tbody>
    </table>';
}


