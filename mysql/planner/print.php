<?php
/** @noinspection ALL */
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/libs/tcpdf/tcpdf.php');


class PDF extends TCPDF
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
        $this->Cell(0, 0, 'WEEKLY PLANNER', 0, 2, 'C');
        $this->SetLineWidth(0.2);
        $this->Line(10, 52, 285, 52);
        $this->SetFont('times', '', 10);
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


$grade = $_POST['grade'];
$date = $_POST['week_date'];

//echo $date;

if (date('w', strtotime($date)) == 0) {
//    echo 'Event is on a sunday';
    $date = date('Y-m-d', strtotime($date . ' + 1 days'));
}
//echo $date;
$ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
$year = date('o', $ts);
$week = date('W', $ts);
$weekStart = strtotime($year . 'W' . $week . 0);
$week_start = date('Y-m-d', $weekStart);
$weekEnd = strtotime($year . 'W' . $week . 6);
$week_end = date('Y-m-d', $weekEnd);

$pdf = new PDF('L');
$pdf->SetTitle('Weekly Planner');
$pdf->SetMargins(10, 60, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);


$pdf->AddPage();
$fontFamily = 'Helvetica'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
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
$pdf->Line(100, 77, 200, 77);

$pdf->Ln(10);

$tbl = <<<EOD
<style>
table {
    border-collapse:collapse;
    }
    </style>'
EOD;

$tbl .= <<<EOD
             <table  cellspacing="0" cellpadding="1" border="1" >
        
                
                  <tr nobr="false">
                  <th  width="80" style="border-top-color:#000000;border-top-width:1px;border-top-style:solid;"> DAY \ SUB</th>
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
    $col_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        $tbl .= <<<EOD
   <th   align="center"> $row[name]</th>

EOD;
    }
}

$tbl .= <<<EOD
             </tr>
            
   
           
 EOD;

for ($j = 0; $j <= 6; $j++) {
    // timestamp from ISO week date format
    $ts = strtotime($year . 'W' . $week . $j);
    $days = strtoupper(date('l', $ts));
    $dates = date('d-M', $ts);
    {
        $tbl .= <<<EOD
       <tr><th width="80"> $days <br></th>
EOD;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $date = date('Y-m-d', $ts);
                $task = " select id,title from indepth_weekly_planner where subject_id = '$row[id]' and duedate = '$date'; ";
                $task_result = $conn->query($task);
//                echo $task;
                if ($task_result->num_rows > 0) {
                    $tbl .= <<<EOD
                    <td align="left">
                    EOD;
                    $si = 1;
                    while ($task_row = mysqli_fetch_array($task_result)) {
                        $tbl .= <<<EOD
                        $si. $task_row[title] <br>
                        EOD;
                        $si++;
                    }
                    $tbl .= <<<EOD
                    </td>
                    EOD;
                } else {
                    $tbl .= <<<EOD
                  <td ></td>
                  EOD;
                }

            }
        }


        $tbl .= <<<EOD
            </tr>
         EOD;
    }

}
$tbl .= <<<EOD
</table>  <br><br>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
ob_end_clean();
$pdf->Output('planner.pdf', 'I');
