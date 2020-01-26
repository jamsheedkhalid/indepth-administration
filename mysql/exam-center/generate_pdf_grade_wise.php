<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/fpdf/fpdf.php');

if (isset($_POST['submitGradeWise'])) {

    $grade = $_POST['hidden_grade_gradeWise'];
//    echo $grade;
    $term = $_REQUEST['hidden_term_gradeWise'];
//    echo $term;
    $ass_percent = $_REQUEST['gradeAssessment'];
    $term_percent =  $_REQUEST['gradeTerm'];


    class PDF extends FPDF
    {
// Page header
        public function Header()
        {   $term = $_REQUEST['hidden_term_gradeWise'];
            // Logo
            $this->Image('../../assets/images/sanawbar-logo.jpeg', 95, 10, 20, 20);
            $this->SetFont('times', 'B', 13);
            // Move to the right
            $this->Ln(25);
            // Title
            $this->Cell(0, 0, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
            $this->SetFont('times', 'B', 10);
            $this->Ln(7);
            $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
            $this->Ln(5);
            $this->Cell(0, 0, 'STUDENT EVALUATION REPORT', 0, 2, 'C');
            $this->SetLineWidth(0.2);
            $this->Line(10, 52, 200, 52);
            $this->SetFont('times', '', 10);
            $this->Ln(15);
            $this->Cell(0, 0, 'ACADEMIC YEAR: 2019 - 2020', 0, 2, 'C');
            $this->Ln(10);
            $this->Cell(0, 0, $term, 0, 2, 'C');
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


    $sql_grade = "select batches.name section 
                    from batches inner join courses on batches.course_id = courses.id 
                    where batches.is_active =1 and courses.is_deleted = 0 and  batches.is_deleted = 0 and 
                     (courses.course_name = ' $grade' or courses.course_name = '$grade') ;  ";
    $result_grade = $conn->query($sql_grade);

    while ($row_grade = mysqli_fetch_array($result_grade)) {
        $section = $row_grade['section'];
//        echo $section;

        $sql_section = "select students.last_name name, students.admission_no admission, batches.name section, courses.course_name grade
from students
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where (courses.course_name = ' $grade' or courses.course_name = '$grade')
  AND courses.is_deleted = 0 AND batches.name LIKE '$section' and  batches.is_active =1 and   batches.is_deleted = 0  
order by students.last_name; ";
//    echo $sql_section . "\n";
        $result_section = $conn->query($sql_section);

        while ($row_section = mysqli_fetch_array($result_section)) {

            $sql = " select     
       subjects.name                                                                                         subject,
       round(exams.maximum_marks, 0)                                                                         max,
       round(exams.minimum_marks, 0)                                                                         min,
       round(MAX(IF(exam_groups.name = '$term - Class Evaluation', exam_scores.marks, 0)), 1)            ASS,
       round(MAX(IF(exam_groups.name = '$term', exam_scores.marks, 0)), 1)                               TE,
       round(MAX(IF(exam_groups.name = '$term', exam_scores.marks, 0)) * $term_percent / 100 +
             MAX(IF(exam_groups.name = '$term - Class Evaluation', exam_scores.marks, 0)) * $ass_percent / 100, 1) TR
from students p
         inner join exam_scores on p.id = exam_scores.student_id
         inner join exams on exam_scores.exam_id = exams.id
         inner join exam_groups on exams.exam_group_id = exam_groups.id
         inner join batches on exam_groups.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join subjects on exams.subject_id = subjects.id
where (exam_groups.name = '$term' or exam_groups.name = '$term - Class Evaluation')
  and p.last_name = \"$row_section[name]\"
group by subjects.id; ";

//        echo "-------------".$sql;
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
            $pdf->Cell(20, 7, 'ASS. ', 1, 0, 'C');
            $pdf->Cell(20, 7, 'T.E.', 1, 0, 'C');
            $pdf->Cell(20, 7, 'T.R.', 1, 0, 'C');

            $pdf->SetFont('times', '', 10);


            $total_max = $total_min = $total_ASS = $total_TE = $total_TR = 0;
            while ($row = mysqli_fetch_array($result)) {


                $total_max += $row['max'];
                $total_min += $row['min'];
                $total_ASS += $row['ASS'];
                $total_TE += $row['TE'];
                $total_TR += $row['TR'];

                $pdf->ln();
                $pdf->SetX(40);
                $pdf->Cell(30, 7, $row['subject'], 1);
                $pdf->Cell(20, 7, $row['max'], 1, 0, 'C');
                $pdf->Cell(20, 7, $row['min'], 1, 0, 'C');
                $pdf->Cell(20, 7, $row['ASS'], 1, 0, 'C');
                $pdf->Cell(20, 7, $row['TE'], 1, 0, 'C');
                $pdf->Cell(20, 7, $row['TR'], 1, 0, 'C');
            }
            $pdf->ln();
            $pdf->SetX(40);
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(30, 7, 'Total', 1, 0, 'C');
            $pdf->Cell(20, 7, $total_max, 1, 0, 'C');
            $pdf->Cell(20, 7, $total_min, 1, 0, 'C');
            $pdf->Cell(20, 7, $total_ASS, 1, 0, 'C');
            $pdf->Cell(20, 7, $total_TE, 1, 0, 'C');
            $pdf->Cell(20, 7, $total_TR, 1, 0, 'C');

            $pdf->ln();
            $pdf->SetFont('times', '', 10);
            $pdf->SetXY(40, 240);
            $pdf->Cell(20, 7, 'ASS. ', 'LTB', 0, 'L');
            $pdf->Cell(70, 7, '1st Assessment '. $term, 'TB', 0, 'L');
            $pdf->Cell(10, 7, $ass_percent . ' %', 'TBR', 0, 'R');
            $pdf->ln();
            $pdf->SetX(40);
            $pdf->Cell(20, 7, 'T.E. ', 'LTB', 0, 'L');
            $pdf->Cell(70, 7, $term, 'TB', 0, 'L');
            $pdf->Cell(10, 7, $term_percent . ' %', 'TBR', 0, 'R');
            $pdf->ln();
            $pdf->SetX(40);
            $pdf->Cell(20, 7, 'T.R. ', 'LTB', 0, 'L');
            $pdf->Cell(70, 7, $term.' Result', 'TB', 0, 'L');
            $pdf->Cell(10, 7, $term_percent + $ass_percent . ' %', 'TBR', 0, 'R');


        }
    }
    $pdf->Output('I', $grade . '-' . $section . 'report-card.pdf', true);
    $pdf->Close();


}

