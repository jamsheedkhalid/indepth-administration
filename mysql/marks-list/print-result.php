<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');
date_default_timezone_set('Asia/Dubai');

//echo $_POST['gradeSelect'];


if($_POST['gradeSelect']){
    $grade = "'" . implode("', '", $_POST['gradeSelect']) . "'";
}

if($_POST['sectionSelect']){
    $section = "'" . implode("', '", $_POST['sectionSelect']) . "'";
}
if($_POST['subject']){
    $subject = "'" . implode("', '", $_POST['subject']) . "'";
}
if($_POST['term']){
    $term = "'" . implode("', '", $_POST['term']) . "'";
}

$filter = $_POST['filter'];
$custom_filter = $_POST['custom_filter'];
$custom_filter_less = $_POST['custom_filter_less'];

echo $grade . '-' . $section . '-' . $subject . '-' . $term . '-' . $filter . '-' . $custom_filter . '-' . $custom_filter_less;


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
        if ($custom_filter_less != '') {
            $filter_sql = '   and exam_scores.marks < ' . $custom_filter_less;
        } else {
            $filter_sql = '   and exam_scores.marks >= 0';
        }
        break;

    default:
        $filter_sql = '';
}

if (isset($_POST['show_ar_names'])) {
    $show_ar = 'students.first_name';
} else {
    $show_ar = 'students.last_name';
}

if (isset($_POST['show_parent_name'])) {
    $parent_th = '<td> Parent </td>';
} else {
    $parent_th = ' ';
}

if (isset($_POST['show_family_id'])) {
    $family_id_th = '<td> Family ID </td>';
} else {
    $family_id_th = ' ';
}
if (isset($_POST['show_contact'])) {
    $contact_th = '<td> Contact # </td>';
} else {
    $contact_th = ' ';
}





class PDF_MARKS_LIST extends TCPDF
{

// Page header
    public function Header()
    {
        // Logo
        $this->Image('../../assets/images/sanawbar-logo.jpeg', 135, 5, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('times', 'B', 13);
        // Move to the right
        $this->Ln(25);
        // Title
        $this->Cell(0, 0, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
        $this->SetFont('times', 'B', 10);
        $this->Ln(7);
        $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
        $this->Ln(5);
        $this->Cell(0, 0, 'STUDENTS MARK LIST', 0, 2, 'C');
        $this->SetLineWidth(0.2);
        $this->Line(10, 52, 285, 52);
        $this->SetFont('times', '', 10);
        $this->Ln(20);
    }

// Page footer
    public function Footer()
    {
        $date = date('d-m-Y h:i a');
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // times italic 8
        $this->SetFont('times', 'I', 8);
        // Page number
        $this->Cell(135, 10, 'Printed by ' . $_SESSION['name'], 0, 0, 'L');
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'L');
        $this->Cell(0, 10, 'As on ' . $date, 0, 0, 'R');
    }
}


$pdf = new PDF_MARKS_LIST('L');
$pdf->SetTitle('Marks List');
$pdf->SetMargins(10, 60, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);

$pdf->AddPage('L', 'A4');
$fontFamily = 'dejavusans'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
$fontStyle = ''; // 'B', 'I', 'U', 'BI', 'BU', 'IU', 'BIU'
$fontSize = 8.5; // float, in point
$pdf->SetFont($fontFamily, $fontStyle, $fontSize);


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
       exams.maximum_marks maximum_marks,
       guardians.first_name parent_name,
       students.familyid family_id,
       guardians.mobile_phone parent_mobile
       
from exam_scores
         inner join exams on exam_scores.exam_id = exams.id
         inner join exam_groups on exams.exam_group_id = exam_groups.id
         inner join subjects on exams.subject_id = subjects.id
         inner join students on exam_scores.student_id = students.id
         inner join batches on students.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join guardians on students.immediate_contact_id = guardians.id
where 
 ( courses.course_name in ($grade)) AND 
 (batches.name in ($section) ) AND 
      (subjects.name in ($subject) ) AND 
      (exam_groups.name in ($term) ) 
    $filter_sql
order by courses.course_name, batches.name, student_name;
     ";
echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {

    $table = <<<EOD

    <table style="padding: 10px;" class='table table-striped table-responsive-lg table-bordered  ' cellpadding="2" cellspacing="0"  border="1"  nobr="true" width="100%" >
    <thead>
    <tr nobr="true" style="text-align: center; font-weight: bolder; background-color: silver ">
    <th height="20"  >Adm. #</th>
    <th>Student</th>
    <th >Grade</th>
    <th>Subject</th>
    <th >Term</th>
    <th >Mark</th>
    <th>Remark</th>
    $parent_th
    $family_id_th
    $contact_th
</tr>
</thead>
<tbody>

EOD;
    while ($row = mysqli_fetch_array($result)) {
        if ($row['remark'] == '') {
            if ($row['mark'] < $row['minimum_marks']) {
                $remark = "<label style='color:darkred;'> Failed </label>";
                $table .= <<<EOD
        <tr nobr="true" style="white-space: nowrap; background-color: lightcoral">
EOD;
            } else if ($row['mark'] >= $row['minimum_marks']) {
                $remark = "<label style='color:darkgreen;'> Passed </label>";
                $table .= <<<EOD
        <tr nobr="true" style="white-space: nowrap" >
EOD;
            }
        } else {
            $remark = $row['remark'];
            $table .= <<<EOD
        <tr nobr="true" style="white-space: nowrap" >
EOD;
        }

        if (isset($_POST['show_parent_name'])) {
            $parent_td = '<th> '.$row['parent_name'].' </th>';
} else {
            $parent_td = ' ';
        }

        if (isset($_POST['show_family_id'])) {
            $family_id_td = '<th> '.$row['family_id'].' </th>';
        } else {
            $family_id_td = ' ';
        }

        if (isset($_POST['show_contact'])) {
            $contact_td = '<th> '.$row['parent_mobile'].' </th>';
        } else {
            $contact_td = ' ';
        }



        $table .= <<<EOD
        <td >  $row[admission_no] </td>
EOD;

        if (isset($_POST['show_ar_names'])) {
            $table .= <<<EOD
            <td align="right" style="white-space: nowrap;"  > $row[student_name] </td>
EOD;
        } else {
            $table .= <<<EOD
            <td align="left" style="white-space: nowrap"  > $row[student_name] </td>
EOD;
        }

        $table .= <<<EOD
        <td > $row[grade] - $row[section] </td>
        <td> $row[subject] </td>
        <td > $row[term] </td>
        <td align="right" > $row[mark] </td>
        <td > $remark </td>
        $parent_td
        $family_id_td
        $contact_td
</tr>
EOD;

    }

    $table .= <<<EOD
        </tbody>
    </table>
EOD;
}


$pdf->writeHTML($table, true, false, true, false, '');
ob_end_clean();
$pdf->Output('Mark List-' . $grade. '-' . $section. '-'.$subject, 'I');
$pdf->Close();
