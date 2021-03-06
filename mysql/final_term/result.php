<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$grade = $_REQUEST['grade'];
 if ($grade != 0 ) {
    if ($grade == 13)
        $grades = "and (courses.course_name in ('GR 1', 'GR 2', 'GR 3'))";
    elseif ($grade == 46)
        $grades = " and (courses.course_name in ('GR 4', 'GR 5', 'GR 6'))";
    elseif ($grade == 79)
        $grades = "and (courses.course_name in ('GR 7', 'GR 8', 'GR 9'))";
    else
        $grades = " and (courses.course_name in ('GR10', 'GR11', 'GR12'))";


    $filter = $_REQUEST['filter'];
    $show_ar_name = $_REQUEST['show_ar_name'];
    $show_parent_name = $_REQUEST['show_parent_name'];
    $show_family_id = $_REQUEST['show_family_id'];
    $show_contact = $_REQUEST['show_contact'];

    if ($show_ar_name == 'true') {
        $show_ar = 'students.first_name';
    } else {
        $show_ar = 'students.last_name';
    }


    $sql = "
select p.admission_no,
       p.last_name                                                                                           en_name,
       p.first_name                                                                                           ar_name,   
       g.first_name parent_name, p.familyid familyid,g.mobile_phone mobile_number,
       course_name 'grade', batches.name 'section',
       subjects.name                                                                                         subject,
       round(exams.maximum_marks, 0)                                                                         max,
       round(exams.minimum_marks, 0)                                                                         min,
           (MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, null)) * 30 / 100 +
        MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, null)) * 70 / 100)              T1,
       (MAX(IF(exam_groups.name = 'Term 2', exam_scores.marks, null)) * IF(courses.id = 12, 100, 30) / 100 +
        IF(courses.id = 12, 0,
           MAX(IF(exam_groups.name = 'Term 2 - Class Evaluation', exam_scores.marks, null)) * 70 / 100))          T2,
       (MAX(IF(exam_groups.name = 'Term 3', exam_scores.marks, null)) * IF(courses.id = 12, 100, 30) / 100 +
        IF(courses.id = 12, 0,
           MAX(IF(exam_groups.name = 'Term 3 - Class Evaluation', exam_scores.marks, null)) * 70 / 100))          T3,
       IFNULL((MAX(IF(exam_groups.name = 'Term 1', exam_scores.marks, null)) * 30 / 100 +
               MAX(IF(exam_groups.name = 'Term 1 - Class Evaluation', exam_scores.marks, null)) * 70 / 100), 'Y') exempt
from students p
         inner join guardians g ON p.immediate_contact_id = g.id 
         inner join batches on p.batch_id = batches.id
         left join students_subjects on p.id = students_subjects.student_id
         inner join courses on batches.course_id = courses.id
         inner join subjects on batches.id = subjects.batch_id
         left join exams on subjects.id = exams.subject_id
         left join exam_groups on exams.exam_group_id = exam_groups.id
         left join exam_scores on exams.id = exam_scores.exam_id and p.id = exam_scores.student_id
where subjects.is_deleted = 0 
$grades
group by p.id, exams.subject_id";

//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {


        echo "
    <table id='marks_table' class='table table-hover table-responsive-lg table-bordered'>
    <thead>
    <tr align='center'>";
        if ($show_parent_name === 'true') echo '<th>Parent</th>';
        if ($show_family_id == 'true') echo '<th>Family ID </th>';
        if ($show_contact == 'true') echo '<th > Contact #</th>';

        echo "<th>Adm. #</th>
    <th>Student</th>
    <th>Grade</th>
    <th>Section</th>
    <th>Subject</th>
    <th>MAX</th>
    <th>MIN</th>
    <th>T1</th>
    <th>T2</th>
    <th>T3</th>
    <th>FT</th>
    <th>Exempt</th>
    ";

        echo '</tr>
</thead>
<tbody>
    ';
        while ($row = mysqli_fetch_array($result)) {
            echo '
        <tr>';
            if ($show_parent_name == 'true') {
                echo ' <td>' . $row['parent_name'] . '</td>';
            }
            if ($show_family_id == 'true') {
                echo '<td>' . $row['familyid'] . '</td>';
            }
            if ($show_contact == 'true') {
                echo '<td>' . $row['mobile_number'] . '</td>';
            }

            echo "<td>" . $row['admission_no'] . '</td>';

            if ($show_ar_name == 'true') {
                echo ' <td align="right"><b>' . $row['ar_name'] . '</b></td>';
            } else {
                echo ' <td align="left"><b>' . $row['en_name'] . '</b></td>';
            }

            echo '   
        <td>' . $row['grade'] . '</td>
        <td>' . $row['section'] . '</td>
        <td>' . $row['subject'] . '</td>
        <td align="right"><b>' . $row['max'] . '</b></td>
        <td align="right"><b>' . $row['min'] . '</b></td>
        <td align="right"><b>' .  round($row['T1'],0) . '</b></td>
        <td align="right"><b>' .  round($row['T2'],0) . '</b></td>
        <td align="right"><b>' .  round($row['T3'],0) . '</b></td>';
        $final_term = ($row['T1'] * 33 / 100) + ($row['T2'] * 33 / 100) + ($row['T3'] * 34 / 100);
         echo '   <td align="right"><b>' . round($final_term,0) . '</b></td>';
            echo "<td align='center'><b>";

            if ($row['exempt'] == 'Y')
                echo $row['exempt'];
            else
                echo "";

            echo "</b></td>";
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-primary fade show" role="alert"><strong>No Marks Found!</strong> Please use different selection to view marks</div>';
    }
} elseif ($grade == 0) {
    echo '<div class="alert alert-primary fade show" role="alert"><strong>Select a grade from Grade dropdown!</strong></div>';
}

