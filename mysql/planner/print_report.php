<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');
date_default_timezone_set('Asia/Dubai');


class PDF3 extends TCPDF
{

// Page header
    public function Header()
    {
        // Logo
        $this->Image('../../assets/images/sanawbar-logo.jpeg', 95, 5, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('times', 'B', 13);
        // Move to the right
        $this->Ln(25);
        // Title
        $this->Cell(0, 0, 'Al SANAWBAR SCHOOL', 0, 0, 'C');
        $this->SetFont('times', 'B', 10);
        $this->Ln(7);
        $this->Cell(0, 0, 'Al AIN - U.A.E', 0, 2, 'C');
        $this->Ln(5);
        $this->Cell(0, 0, 'WEEKLY PLANNER REPORT', 0, 2, 'C');
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
//            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, 'As on ' . $date, 0, 0, 'R');
    }
}

if (isset($_POST['grade'], $_POST['week_date'])) {
    $grade = $_REQUEST['grade'];
    $date = $_REQUEST['week_date'];
    $subject_array = [];

    if (date('w', strtotime($date)) == 0) {
//    echo 'Event is on a sunday';
        $date = date('Y-m-d', strtotime($date . ' + 1 days'));
    }
    $ts = strtotime($date);
    $year = date('o', $ts);
    $week = date('W', $ts);
    $weekStart = strtotime($year . 'W' . $week . 0);
    $week_start = date('Y-m-d', $weekStart);
    $weekEnd = strtotime($year . 'W' . $week . 6);
    $week_end = date('Y-m-d', $weekEnd);

    $pdf = new PDF3('L');
    $pdf->SetTitle('Weekly Planner');
    $pdf->SetMargins(10, 60, 10);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
// set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->setFontSubsetting(true);

    $pdf->AddPage('P', 'A4');
    $fontFamily = 'dejavusans'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
    $fontStyle = ''; // 'B', 'I', 'U', 'BI', 'BU', 'IU', 'BIU'
    $fontSize = 8.5; // float, in point
    $pdf->SetFont($fontFamily, $fontStyle, $fontSize);

    $pdf->Cell(0, 0, 'Week: ' . date('d.M.Y', $weekStart) . ' - ' . date('d.M.Y', $weekEnd), 0, 1, 'C', 0);
    $sql = "SELECT course_name grade from courses where id = '$grade'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $pdf->Cell(0, 10, 'Grade: ' . $row['grade'], 0, 2, 'C', 0);
        }
    }
    $pdf->SetLineWidth(0.2);
    $pdf->Line(80, 77, 130, 77);
    $pdf->Ln(10);

    $tbl = <<<EOD
             <table  cellspacing="0" cellpadding="1" border="1"  >
             <tbody style="padding: 5px">
            <tr  style="background-color: lightblue" align="center"><th > Subject</th>
            <th width="290">Teacher</th>
            <th>Task Status</th>
            <th width="50">Tasks </th>
            </tr>
EOD;
    for ($j = 0; $j <= 6; $j++) {
        // timestamp from ISO week date format
        $ts = strtotime($year . 'W' . $week . $j);
        $days = strtoupper(date('l', $ts));
        $dates = date('d-m-y', $ts);
        $dueDate = date('Y-m-d', $ts);
        $tbl .= <<<EOD
        <tr><th colspan="4" style="background-color: lightskyblue" align="center"><b> $days  ($dates) </b></th></tr>
EOD;
        $sql = "
                select subjects.id id, subjects.name name
                from subjects
                inner join batches on subjects.batch_id = batches.id
                inner join courses on batches.course_id = courses.id
                where subjects.is_deleted = 0
                and courses.id = '$grade'
                and courses.is_deleted = 0
                and batches.is_deleted = 0
                and batches.is_active = 1
                and batches.name LIKE '%2020%'
                group by subjects.name
                order by subjects.name;
                ";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {

                $sql_sub = "select subjects.id id, subjects.name name from subjects
                            inner join batches on subjects.batch_id = batches.id
                            inner join courses on batches.course_id = courses.id
                            where courses.id = '$grade'
                            and batches.is_active = 1
                            and subjects.is_deleted = 0
                            and batches.is_deleted = 0
                            and courses.is_deleted = 0
                            and batches.name LIKE '%2020%'
                            and subjects.name = '$row[name]'";
                $result_sub = $conn->query($sql_sub);
                $subject_array = [];
                $flag = 0;
                if ($result_sub->num_rows > 0) {
                    while ($row_sub = mysqli_fetch_array($result_sub)) {
                        $subject_array[] = $row_sub['id'];
                    }
                }
                $subject_ids = implode(',', $subject_array);

                $sql_task = "
                             select   employee_id, count(*) count, updated_at from indepth_weekly_planner 
                             where subject_id in ($subject_ids) and duedate = '$dueDate' group by employee_id; ";
//                echo $sql_task;
                $result_task = $conn->query($sql_task);
                if ($result_task->num_rows > 0) {
                    $row_count = mysqli_num_rows($result_task);
//                    echo $row_count;
                    $tbl .= <<<EOD
                                                   <tr><td rowspan="$row_count"> $row[name] </td>
EOD;
                    $line = 0;

                    while ($row_task = mysqli_fetch_array($result_task)) {

                        $sql_emp = " select first_name name from employees where id = '$row_task[employee_id]' ";
                        echo $sql_emp;
                        $result_emp = $conn->query($sql_emp);
                        if ($result_emp->num_rows > 0) {

                            while ($row_emp = mysqli_fetch_array($result_emp)) {
                               $updated_date = date('d-F-Y', strtotime($row_task['updated_at']));
                                if ($line === 0) {
                                    $tbl .= <<<EOD
                                                   <td> $row_emp[name] </td><td align="center">Updated on $updated_date</td><td align="center"> $row_task[count] </td>
                                                     </tr>
EOD;
                                    $line++;
                                } else {
                                    $tbl .= <<<EOD
                                                  <tr><td> $row_emp[name] </td><td align="center">Updated on $updated_date</td><td align="center"> $row_task[count] </td>
                                                     </tr>
EOD;
                                }

                            }
                        }

//                            $tbl .= <<<EOD
//                             <tr ><td> $row[name] </td>
//                               <td> $row_task[employee_id] </td><td></td><td align="center"> $row_task[count] </td>
//                               </tr>
//EOD;
                    }
                    $flag = 1;
//                    break;
                } else {
                    $tbl .= <<<EOD
 <tr style="background-color: #F7CACA"><td> $row[name] </td><td colspan="3" align="center"> Not yet submitted</td>
                            </tr>
EOD;
                }


            }
        }
    }
    $tbl .= <<<EOD
</tbody></table>
EOD;

    $pdf->writeHTML($tbl, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output('planner', 'I');
    $pdf->Close();

}
