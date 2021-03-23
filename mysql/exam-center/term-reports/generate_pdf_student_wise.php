<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');

if (isset($_POST['studentSubmit'])) {

    $grade = $_POST['hidden_grade_studentWise'];
    $student = $_POST['hidden_student_studentWise'];
    $section = $_REQUEST['hidden_section_studentWise'];
    $term = $_REQUEST['hidden_term_studentWise'];
    $ass_percent = $_REQUEST['studentAssessment'];
    $term_percent = $_REQUEST['studentTerm'];
    $start_date = $_REQUEST['start_date'];
    $end_date = $_REQUEST['end_date'];
    $absents2days = $_REQUEST['absents2days'];
    $holidays = $_REQUEST['holidays'];

    $total_percent = $ass_percent + $term_percent;
    $is_non_islamic = 0;
    $term_name = '';


    class PDF extends TCPDF
    {
// Page header
        public function Header()
        {
            $term = $_REQUEST['hidden_term_studentWise'];
            // Logo
            $this->Image($_SERVER['DOCUMENT_ROOT'] . '/assets/images/sanawbar-logo.jpeg', 95, 10, 20, 20);
            $this->SetFont('times', 'B', 13);
            // Move to the right
            $this->Ln(30);
            // Title
            $this->Cell(0, 5, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
            $this->SetFont('times', 'B', 10);
            $this->Ln(7);
            $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
            $this->Ln(5);
            $this->Cell(0, 0, 'STUDENT TERM REPORT', 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(10, 52, 200, 52);
            $this->SetFont('times', '', 10);
            $this->Ln(5);
            $this->Cell(0, 0, 'ACADEMIC YEAR: 2020 - 2021', 0, 2, 'C');
            $this->Ln(5);
            switch ($term) {
                case 'Term 1':
                    $term_name = 'First Term';
                    break;
                case 'Term 2':
                    $term_name = 'Second Term';
                    break;
                case 'Term 3';
                    $term_name = 'Third Term';
                    break;
                default:
                    $term_name = 'Term Unknown';
            }

            $this->Cell(0, 0, $term_name, 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(130, 70, 80, 70);

            // Line break
            $this->Ln(5);
        }

// Page footer
        public function Footer()
        {
            $date = date("d-m-Y");
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // times italic 8
            $this->SetFont('times', 'I', 8);
            // Page number
//            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            $this->Cell(0, 10, 'Date ' . $date, 0, 0, 'R');
        }
    }


    $pdf = new PDF();

//    $pdf->AliasNbPages();


    $sql_section = " select students.religion religion, students.last_name name, students.admission_no admission, batches.name section, courses.course_name grade
from students
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where (courses.course_name = ' $grade' or courses.course_name = '$grade')
  AND courses.is_deleted = 0 AND batches.name LIKE '$section'
and  students.id = \"$student\" ";
    $result_section = $conn->query($sql_section);

    while ($row_section = mysqli_fetch_array($result_section)) {

        $sql = " select     
       subjects.name                                                                                         subject,
       round(exams.maximum_marks, 0)                                                                         max,
       round(exams.minimum_marks, 0)                                                                         min,
       round(MAX(IF(exam_groups.name = '$term - Class Evaluation', exam_scores.marks, null)), 0)            ASS,
       round(MAX(IF(exam_groups.name = '$term', exam_scores.marks, null)), 0)                               TE,
       round(MAX(IF(exam_groups.name = '$term', exam_scores.marks, null)) * $term_percent / 100 +
             MAX(IF(exam_groups.name = '$term - Class Evaluation', exam_scores.marks, null)) * $ass_percent / 100, 0) TR
from students p
         inner join batches on p.batch_id = batches.id
         left join students_subjects on p.id = students_subjects.student_id
         inner join subjects on batches.id = subjects.batch_id and (subjects.elective_group_id is null or subjects.id = students_subjects.subject_id) 
         inner join courses on batches.course_id = courses.id
         inner join exams on subjects.id = exams.subject_id
         inner join exam_groups on exams.exam_group_id = exam_groups.id
         left join exam_scores on exams.id = exam_scores.exam_id and p.id = exam_scores.student_id
where (exam_groups.name = '$term' or exam_groups.name = '$term - Class Evaluation')
  and p.last_name = \"$row_section[name]\"
group by subjects.id; ";

//echo $sql;
        $result = $conn->query($sql);


        $pdf->AddPage();
        $pdf->SetFont('times', '', 10);
        $pdf->ln(65);
        $pdf->Cell(50, 5, "Admission No. :", 0, 0, 'R');
        $pdf->Cell(100, 5, $row_section['admission'], 0, 0, 'L');
        $pdf->ln();
        $pdf->Cell(50, 5, "Student's Name :", 0, 0, 'R');
        $pdf->Cell(100, 5, $row_section['name'], 0, 0, 'L');
        $pdf->ln();
        $pdf->Cell(50, 5, "Grade :", 0, 0, 'R');
        $pdf->Cell(100, 5, $row_section['grade'], 0, 0, 'L');
        $pdf->ln();
        $pdf->Cell(50, 5, "Section :", 0, 0, 'R');
        $pdf->Cell(100, 5, $row_section['section'], 0, 0, 'L');

        $pdf->SetFont('times', 'B', 10);
        $pdf->ln(10);
        $pdf->SetX(25);
//        $pdf->SetXY(25, 120);
        $pdf->Cell(60, 7, 'Subjects', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Max Mark', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Min Mark', 1, 0, 'C');
        if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {
            $pdf->Cell(20, 7, 'C.E.', 1, 0, 'C');
            $pdf->Cell(20, 7, 'T.E.', 1, 0, 'C');
        }
        $pdf->Cell(20, 7, 'Term Result', 1, 0, 'C');

        $pdf->SetFont('dejavusans', '', 8);


        $total_max = $total_min = $total_ASS = $total_TE = $total_TR = 0;
        $max_ASS = $max_TE = $max_TR = 0;
        $ratio_TR = $ratio_TE = $ratio_ASS = 0;
        while ($row = mysqli_fetch_array($result)) {
            $var = preg_split("#-#", $row['subject']);
            $row['subject'] = $var[0];

            if ($grade !== 'GR 9' && $grade !== 'GR10' && $grade !== 'GR11' && $grade !== 'GR12') {

                if ((strpos($row_section['religion'], 'Christian') !== false && strpos($row['subject'], 'Islamic') === false)
                    || strpos($row_section['religion'], 'Christian') === false) {

                    $total_max += $row['max'];
                    $total_min += $row['min'];

                    if ($row['ASS'] !== null) {
                        $total_ASS += $row['ASS'];
                        $max_ASS += $row['max'];
                    }
                    if ($row['TE'] !== null) {
                        $total_TE += $row['TE'];
                        $max_TE += $row['max'];
                    }
                    if ($row['TR'] !== null) {
                        $total_TR += $row['TR'];
                        $max_TR += $row['max'];
                    }

                    $pdf->ln();
                    $pdf->SetX(25);
                    $pdf->Cell(60, 7, $row['subject'], 1);
                    $pdf->Cell(20, 7, $row['max'], 1, 0, 'C');
                    $pdf->Cell(20, 7, $row['min'], 1, 0, 'C');


//                check non islamic
                    if (strpos($row['subject'], 'Islamic') !== false && is_null($row['ASS']) && is_null($row['TE'])) {
                        $is_non_islamic = 1;
                    }

//                end check non islamic

                    if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {
                        if (!is_null($row['ASS']))
                            $pdf->Cell(20, 7, $row['ASS'], 1, 0, 'C');
                        else
                            $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    }
                    if (!is_null($row['TE']))
                        $pdf->Cell(20, 7, $row['TE'], 1, 0, 'C');
                    else
                        $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                        if (!is_null($row['TR']))
                            $pdf->Cell(20, 7, $row['TR'], 1, 0, 'C');
                        else
                            $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    }

                }
            } else if ($grade === 'GR 9'
                || $grade === 'GR10' || $grade === 'GR11' || $grade === 'GR12') {

                if (strpos($row['subject'], 'Moral Education') !== false) {
                    $ME['subject'] = $row['subject'];
                    $ME['max'] = $row['max'];
                    $ME['min'] = $row['min'];
                    $ME['ASS'] = $row['ASS'];
                    $ME['TE'] = $row['TE'];
                    $ME['TR'] = $row['TR'];
                } else {
                    $total_max += $row['max'];
                    $total_min += $row['min'];
                    if ($row['ASS'] !== null) {
                        $total_ASS += $row['ASS'];
                        $max_ASS += $row['max'];
                    }
                    if ($row['TE'] !== null) {
                        $total_TE += $row['TE'];
                        $max_TE += $row['max'];
                    }
                    if ($row['TR'] !== null) {
                        $total_TR += $row['TR'];
                        $max_TR += $row['max'];
                    }


                    $pdf->ln();
                    $pdf->SetX(25);
                    $pdf->Cell(60, 7, $row['subject'], 1);
                    $pdf->Cell(20, 7, $row['max'], 1, 0, 'C');
                    $pdf->Cell(20, 7, $row['min'], 1, 0, 'C');

                    //                check non islamic
                    if (strpos($row['subject'], 'Islamic') !== false && is_null($row['ASS']) && is_null($row['TE'])) {
                        $is_non_islamic = 1;
                    }

//                end check non islamic
                    if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                        if (!is_null($row['ASS']))
                            $pdf->Cell(20, 7, $row['ASS'], 1, 0, 'C');
                        else
                            $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    }
                    if (!is_null($row['TE']))
                        $pdf->Cell(20, 7, $row['TE'], 1, 0, 'C');
                    else
                        $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                        if (!is_null($row['TR']))
                            $pdf->Cell(20, 7, $row['TR'], 1, 0, 'C');
                        else
                            $pdf->Cell(20, 7, '-', 1, 0, 'C');
                    }
                }

            }
        }

        $pdf->ln();
        $pdf->SetX(25);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(60, 10, 'Total', 1, 0, 'C');
        $pdf->Cell(20, 10, $total_max, 1, 0, 'C');
        $pdf->Cell(20, 10, $total_min, 1, 0, 'C');

        {
            if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                $pdf->Cell(20, 10, $total_ASS, 1, 0, 'C');
            }
            $pdf->Cell(20, 10, $total_TE, 1, 0, 'C');
            if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                $pdf->Cell(20, 10, $total_TR, 1, 0, 'C');
            }
        }
        if (($grade === 'GR 9'
                || $grade === 'GR10' || $grade === 'GR11' || $grade === 'GR12') && ($ME['subject'] != null)) {
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->ln();
            $pdf->SetX(25);
            $pdf->Cell(60, 1, '', 'LTB');
            $pdf->Cell(20, 1, '', 'TB', 0, 'C');
            $pdf->Cell(20, 1, '', 'BT', 0, 'C');
            if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                $pdf->Cell(20, 1, '', 'BT', 0, 'C');
                $pdf->Cell(20, 1, '', 'BT', 0, 'C');
            }
            $pdf->Cell(20, 1, '', 'BTR', 0, 'C');
            $pdf->ln();
            $pdf->SetX(25);
            $pdf->Cell(60, 7, $ME['subject'], 1);
            $pdf->Cell(20, 7, $ME['max'], 1, 0, 'C');
            $pdf->Cell(20, 7, $ME['min'], 1, 0, 'C');
            if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                $pdf->Cell(20, 7, $ME['ASS'], 1, 0, 'C');
            }
            $pdf->Cell(20, 7, $ME['TE'], 1, 0, 'C');
            if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {

                $pdf->Cell(20, 7, $ME['TR'], 1, 0, 'C');
            }
        }
        switch ($term) {
            case 'Term 1':
                $term_name = '1st Term';
                break;
            case 'Term 2':
                $term_name = '2nd Term';
                break;
            case 'Term 3';
                $term_name = '3rd Term';
                break;
            default:
                $term_name = 'Term Unknown';
        }
//        if ($term == 'Term 1' || ($term == 'Term 2' && $grade != 'GR12')) {
//            $pdf->ln(15);
//            $pdf->SetFont('times', '', 10);
//            $pdf->SetX(25);
//            $pdf->Cell(20, 7, 'C.E. ', 'LTB', 0, 'L');
//            $pdf->Cell(70, 7, 'Class Evaluation for ' . $term_name, 'TB', 0, 'L');
//            $pdf->Cell(10, 7, $ass_percent . ' %', 'TBR', 0, 'R');
//            $pdf->ln();
//            $pdf->SetX(25);
//            $pdf->Cell(20, 7, 'T.E. ', 'LTB', 0, 'L');
//            $pdf->Cell(70, 7, $term_name . ' Exam', 'TB', 0, 'L');
//            $pdf->Cell(10, 7, $term_percent . ' %', 'TBR', 0, 'R');
//            $pdf->ln();
//            $pdf->SetX(25);
//            $pdf->Cell(20, 7, 'T.R. ', 'LTB', 0, 'L');
//            $pdf->Cell(70, 7, $term_name . ' Result', 'TB', 0, 'L');
//            $pdf->Cell(10, 7, $term_percent + $ass_percent . ' %', 'TBR', 0, 'R');
//        }

        // Attendance

        //HEADER
        $pdf->ln(15);
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetX(25);

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);


        $pdf->Cell(160, 7, 'ATTENDANCE [' . $start->format('d-m-Y') . ' to ' . $end->format('d-m-Y') . ']', 'LTBR', 0, 'C');
        $pdf->ln();
        $pdf->SetX(25);
        $pdf->Cell(40, 7, 'ATTENDANCE', 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, 'PERIODS', 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, 'DAYS', 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, 'PERCENTAGE', 'LTBR', 0, 'C');

        //TOTAL
        //periods per day
        $days_periods_sql = "
        SELECT concat(courses.course_name, ' - ', batches.name) grade,count(class_timing_sets.name) periods, class_timings.name,timetable_entries.weekday_id,
               class_timings.start_time,class_timings.end_time
        FROM timetables
         INNER JOIN timetable_entries on timetables.id = timetable_entries.timetable_id
         INNER JOIN class_timings on timetable_entries.class_timing_id = class_timings.id
         INNER JOIN class_timing_sets on class_timings.class_timing_set_id = class_timing_sets.id
         INNER JOIN batches on timetable_entries.batch_id = batches.id
         INNER JOIN courses on batches.course_id = courses.id
         INNER JOIN students on batches.id = students.batch_id
        WHERE students.id = $student
        GROUP BY weekday_id
        ORDER BY weekday_id;
        ";

        $day_periods_query = $conn->query($days_periods_sql);

        $interval = new DateInterval('P1D');
        $end->modify('+1 day');
        $days = new DatePeriod($start, $interval, $end);
        $periods = $working_days = 0;
        if ($day_periods_query->num_rows > 0) {
            while ($day_periods_row = mysqli_fetch_array($day_periods_query)) {
//                echo '<br><br><hr><h5>' . $day_periods_row['weekday_id'] . ' - ' . $day_periods_row['periods'] . '</h5>';
                foreach ($days as $d) {
                    $day = Date('w', strtotime($d->format("d-m-Y")));
                    if ($day == $day_periods_row['weekday_id']) {
//                        echo 'Matched with ' . $day_periods_row['weekday_id'] . ' = ' . $day_periods_row['periods'] . '<br>';
                        $periods += $day_periods_row['periods'];
                        $working_days++;
                    }
                }
            }
        }

//        echo 'Working days = ' . $working_days;
//        echo 'Periods = ' . $periods;


        $pdf->ln();
        $pdf->SetX(25);
        $pdf->Cell(40, 7, 'TOTAL', 'LTBR', 0, 'C');
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(40, 7, $periods, 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, $working_days, 'LTBR', 0, 'C');
        $pdf->Cell(20, 7, 'Periods', 'LTBR', 0, 'C');
        $pdf->Cell(20, 7, 'Days', 'LTBR', 0, 'C');

        //ABSENT
        $absent_periods_sql = "SELECT count(month_date) absent, last_name, name, reason
                       FROM subject_leaves
                       INNER JOIN students on subject_leaves.student_id = students.id
                       INNER JOIN subjects on subject_leaves.subject_id = subjects.id
                       WHERE student_id = $student AND (month_date BETWEEN '$start_date' AND '$end_date')
                       GROUP BY month_date;";
        echo $absent_periods_sql;

        $total_absents = $conn->query($absent_periods_sql);
        if ($total_absents->num_rows > 0) {
            $total_periods_absent = $total_days_absent = 0;
            while ($absent_row = mysqli_fetch_array($total_absents)) {
                $total_periods_absent += $absent_row['absent'];
                if ($absent_row['absent'] >= $absents2days) $total_days_absent++;
            }
        }
        echo $total_periods_absent . 'periods and ' . $total_days_absent . 'days<br>';


        $pdf->ln();
        $pdf->SetX(25);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(40, 7, 'ABSENT', 'LTBR', 0, 'C');
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(40, 7, $total_periods_absent, 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, $total_days_absent, 'LTBR', 0, 'C');

        if ($total_periods_absent > 0 AND $periods > 0) // Avoid dividing on Zero
            $pdf->Cell(20, 7, round(($total_periods_absent/$periods) * 100,2) . '%', 'LTBR', 0, 'C');
        else
            $pdf->Cell(20, 7, '100%', 'LTBR', 0, 'C');


        if ($total_days_absent > 0 AND $working_days > 0) // Avoid dividing on Zero
            $pdf->Cell(20, 7, round(($total_days_absent/$working_days) * 100,2) . '%', 'LTBR', 0, 'C');
        else
            $pdf->Cell(20, 7, '100%', 'LTBR', 0, 'C');


        $total_periods_present = $periods - $total_periods_absent;
        $total_days_present = $working_days - $total_days_absent;

        $pdf->ln();
        $pdf->SetX(25);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(40, 7, 'PRESENT', 'LTBR', 0, 'C');
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(40, 7, $total_periods_present, 'LTBR', 0, 'C');
        $pdf->Cell(40, 7, $total_days_present, 'LTBR', 0, 'C');

        if ($total_periods_present > 0 AND $periods > 0) // Avoid dividing on Zero
            $pdf->Cell(20, 7, round(($total_periods_present/$periods) * 100,2) . '%', 'LTBR', 0, 'C');
        else
            $pdf->Cell(20, 7, '100%', 'LTBR', 0, 'C');


        if ($total_days_present > 0 AND $working_days > 0) // Avoid dividing on Zero
            $pdf->Cell(20, 7, round(($total_days_present/$working_days) * 100,2) . '%', 'LTBR', 0, 'C');
        else
            $pdf->Cell(20, 7, '100%', 'LTBR', 0, 'C');

        $pdf->ln();
        $pdf->SetX(25);
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(160, 7, 'Each ' . $absents2days . ' or more absent periods on the same day mark 1 day as absent.', 'LTBR', 0, 'L');



    }

    ob_end_clean();
    $pdf->Output('report-card.pdf', 'I');
    $pdf->Close();

}

