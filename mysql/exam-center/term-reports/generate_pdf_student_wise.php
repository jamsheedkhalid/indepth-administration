<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

if (isset($_POST['studentSubmit'])) {

    $grade = $_POST['hidden_grade_studentWise'];
    $student = $_POST['hidden_student_studentWise'];
//    echo $grade;
    $section = $_REQUEST['hidden_section_studentWise'];
//    echo $section;
    $term = $_REQUEST['hidden_term_studentWise'];
//    echo $term;
    $ass_percent = $_REQUEST['studentAssessment'];
    $term_percent = $_REQUEST['studentTerm'];
    $total_percent = $ass_percent + $term_percent;
    $is_non_islamic = 0;
    $term_name ='';


    class PDF extends FPDF
    {
// Page header
        public function Header()
        {
            $term = $_REQUEST['hidden_term_studentWise'];
            // Logo
            $this->Image($_SERVER['DOCUMENT_ROOT'] . '/assets/images/sanawbar-logo.jpeg', 95, 10, 20, 20);
            $this->SetFont('times', 'B', 13);
            // Move to the right
            $this->Ln(25);
            // Title
            $this->Cell(0, 0, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
            $this->SetFont('times', 'B', 10);
            $this->Ln(7);
            $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
            $this->Ln(5);
            $this->Cell(0, 0, 'STUDENT TERM REPORT', 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(10, 52, 200, 52);
            $this->SetFont('times', '', 10);
            $this->Ln(15);
            $this->Cell(0, 0, 'ACADEMIC YEAR: 2019 - 2020', 0, 2, 'C');
            $this->Ln(10);
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
            $this->Line(130, 80, 80, 80);

            // Line break
            $this->Ln(20);
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

    $pdf->AliasNbPages();


    $sql_section = " select students.last_name name, students.admission_no admission, batches.name section, courses.course_name grade
from students
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where (courses.course_name = ' $grade' or courses.course_name = '$grade')
  AND courses.is_deleted = 0 AND batches.name LIKE '$section'
and  students.last_name = '$student'; ";
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
         inner join subjects on batches.id = subjects.batch_id
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
        $pdf->SetXY(40, 120);
        $pdf->Cell(30, 7, 'Subjects', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Max Mark', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Min Mark', 1, 0, 'C');
        $pdf->Cell(20, 7, 'C.E.', 1, 0, 'C');
        $pdf->Cell(20, 7, 'T.E.', 1, 0, 'C');
        $pdf->Cell(20, 7, 'T.R.', 1, 0, 'C');

        $pdf->SetFont('times', '', 10);


        $total_max = $total_min = $total_ASS = $total_TE = $total_TR = 0;
        $max_ASS = $max_TE = $max_TR = 0;
        $ratio_TR = $ratio_TE = $ratio_ASS = 0;
        while ($row = mysqli_fetch_array($result)) {
            if ($grade !== 'GR 9' && $grade !== 'GR10' && $grade !== 'GR11' && $grade !== 'GR12') {
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
                $pdf->SetX(40);
                $pdf->Cell(30, 7, $row['subject'], 1);
                $pdf->Cell(20, 7, $row['max'], 1, 0, 'C');
                $pdf->Cell(20, 7, $row['min'], 1, 0, 'C');


//                check non islamic
                                if(strpos($row['subject'], 'Islamic') !== false &&  is_null($row['ASS']) && is_null($row['TE'])     )
                                {
                                   $is_non_islamic = 1;
                                }

//                end check non islamic


                if (!is_null($row['ASS']))
                    $pdf->Cell(20, 7, $row['ASS'], 1, 0, 'C');
                ELSE
                    $pdf->Cell(20, 7, '-', 1, 0, 'C');

                if (!is_null($row['TE']))
                    $pdf->Cell(20, 7, $row['TE'], 1, 0, 'C');
                ELSE
                    $pdf->Cell(20, 7, '-', 1, 0, 'C');

                if (!is_null($row['TR']))
                    $pdf->Cell(20, 7, $row['TR'], 1, 0, 'C');
                ELSE
                    $pdf->Cell(20, 7, '-', 1, 0, 'C');

            } else if ($grade === 'GR 9'
                || $grade === 'GR10' || $grade === 'GR11' || $grade === 'GR12') {

                if ($row['subject'] === 'Moral Education') {
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
                    $pdf->SetX(40);
                    $pdf->Cell(30, 7, $row['subject'], 1);
                    $pdf->Cell(20, 7, $row['max'], 1, 0, 'C');
                    $pdf->Cell(20, 7, $row['min'], 1, 0, 'C');

                    //                check non islamic
                    if(strpos($row['subject'], 'Islamic') !== false &&  is_null($row['ASS']) && is_null($row['TE'])     )
                    {
                        $is_non_islamic = 1;
                    }

//                end check non islamic
                    if (!is_null($row['ASS']))
                        $pdf->Cell(20, 7, $row['ASS'], 1, 0, 'C');
                    ELSE
                        $pdf->Cell(20, 7, '-', 1, 0, 'C');

                    if (!is_null($row['TE']))
                        $pdf->Cell(20, 7, $row['TE'], 1, 0, 'C');
                    ELSE
                        $pdf->Cell(20, 7, '-', 1, 0, 'C');

                    if (!is_null($row['TR']))
                        $pdf->Cell(20, 7, $row['TR'], 1, 0, 'C');
                    ELSE
                        $pdf->Cell(20, 7, '-', 1, 0, 'C');
                }

            }
        }

        $pdf->ln();
        $pdf->SetX(40);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(30, 10, 'Total', 1, 0, 'C');
        $pdf->Cell(20, 10, $total_max, 1, 0, 'C');
        $pdf->Cell(20, 10, $total_min, 1, 0, 'C');

        if ($grade === 'GR11' || $grade === 'GR12' || $is_non_islamic === 1) {


            if($max_ASS !== 0 )
                $ratio_ASS = round(($total_max * $total_ASS)/$max_ASS);
            if($max_TE !== 0 )
                $ratio_TE = round(($total_max * $total_TE)/$max_TE);
            if($max_TR !== 0 )
                $ratio_TR = round(($total_max * $total_TR)/$max_TR);

            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(9, 10, $total_ASS , 'LTB', 0, 'C');
            $pdf->SetFont('times', 'I', 35);
            $pdf->Cell(2, 10,  ' / ' , 'TB', 0, 'C');
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(9, 10,  $ratio_ASS, 'RTB', 0, 'C');

            $pdf->Cell(9, 10, $total_TE , 'LTB', 0, 'C');
            $pdf->SetFont('times', 'I', 35);
            $pdf->Cell(2, 10,  ' / ' , 'TB', 0, 'C');
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(9, 10,  $ratio_TE, 'RTB', 0, 'C');

            $pdf->Cell(9, 10, $total_TR , 'LTB', 0, 'C');
            $pdf->SetFont('times', 'I', 35);
            $pdf->Cell(2, 10,  ' / ' , 'TB', 0, 'C');
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(9, 10,  $ratio_TR, 'RTB', 0, 'C');


        } else {
            $pdf->Cell(20, 10, $total_ASS , 1, 0, 'C');
            $pdf->Cell(20, 10, $total_TE , 1, 0, 'C');
            $pdf->Cell(20, 10, $total_TR , 1, 0, 'C');
        }
        if ($grade === 'GR 9'
            || $grade === 'GR10' || $grade === 'GR11' || $grade === 'GR12') {
            $pdf->SetFont('times', '', 10);
            $pdf->ln();
            $pdf->SetX(40);
            $pdf->Cell(30, 1, '', 'LTB');
            $pdf->Cell(20, 1, '', 'TB', 0, 'C');
            $pdf->Cell(20, 1, '', 'BT', 0, 'C');
            $pdf->Cell(20, 1, '', 'BT', 0, 'C');
            $pdf->Cell(20, 1, '', 'BT', 0, 'C');
            $pdf->Cell(20, 1, '', 'BTR', 0, 'C');
            $pdf->ln();
            $pdf->SetX(40);
            $pdf->Cell(30, 7, $ME['subject'], 1);
            $pdf->Cell(20, 7, $ME['max'], 1, 0, 'C');
            $pdf->Cell(20, 7, $ME['min'], 1, 0, 'C');
            $pdf->Cell(20, 7, $ME['ASS'], 1, 0, 'C');
            $pdf->Cell(20, 7, $ME['TE'], 1, 0, 'C');
            $pdf->Cell(20, 7, $ME['TR'], 1, 0, 'C');
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


        $pdf->ln(15);
        $pdf->SetFont('times', '', 10);
        $pdf->SetX(40);
        $pdf->Cell(20, 7, 'C.E. ', 'LTB', 0, 'L');
        $pdf->Cell(70, 7, 'Class Evaluation for ' . $term_name, 'TB', 0, 'L');
        $pdf->Cell(10, 7, $ass_percent . ' %', 'TBR', 0, 'R');
        $pdf->ln();
        $pdf->SetX(40);
        $pdf->Cell(20, 7, 'T.E. ', 'LTB', 0, 'L');
        $pdf->Cell(70, 7, $term_name . ' Exam', 'TB', 0, 'L');
        $pdf->Cell(10, 7, $term_percent . ' %', 'TBR', 0, 'R');
        $pdf->ln();
        $pdf->SetX(40);
        $pdf->Cell(20, 7, 'T.R. ', 'LTB', 0, 'L');
        $pdf->Cell(70, 7, $term_name . ' Result', 'TB', 0, 'L');
        $pdf->Cell(10, 7, $term_percent + $ass_percent . ' %', 'TBR', 0, 'R');


    }
    $pdf->Output('I', $student .'-' .$term_name.' '. 'report-card.pdf', true);
    $pdf->Close();

}

