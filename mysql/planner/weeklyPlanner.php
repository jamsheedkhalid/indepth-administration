<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$date_type = $_REQUEST['date_type'];
switch ($date_type) {
    case 'curr':
        $date = date('Y-m-d', strtotime('Sunday this week'));
        $show_date = 0;
        break;
    case 'last':
        $date = date('Y-m-d', strtotime('Sunday last week'));
        $show_date = 1;
        break;
    case 'next':
        $date = date('Y-m-d', strtotime('Sunday next week'));
        $show_date = 1;
        break;
    default:
        $date = date('Y-m-d', strtotime('Sunday this week'));
        $show_date = 0;
}
// parse about any English textual datetime description into a Unix timestamp
$ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
$year = date('o', $ts);
$week = date('W', $ts);
$weekStart = strtotime($year . 'W' . $week . 0);
$weekStart = date('Y-m-d', $weekStart);
$weekEnd = strtotime($year . 'W' . $week . 6);
$weekEnd = date('Y-m-d', $weekEnd);

//echo $weekStart;
//echo $weekEnd;

$sql = " select familyid from guardians where user_id = '$_SESSION[user]' ";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $sql = " select students.id id, first_name, last_name, batches.name section, course_name grade from students 
                inner join batches on students.batch_id = batches.id
                inner join courses on batches. course_id = courses.id where familyid = '$row[familyid]' order by students.last_name ";
        $student = $conn->query($sql);
        if ($student->num_rows > 0) {

//-----------------------week calculation  ------------------------------------
//          // set current date
// parse about any English textual datetime description into a Unix timestamp
            $ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
            $year = date('o', $ts);
            $week = date('W', $ts);
// print week for the current date
// ------------------------ end week calculation  ---------------------
            $show = 'show active';
            while ($row_student = mysqli_fetch_array($student)) {

                $assignment_sql = " select  assignments.id id ,title, content ,duedate ,
                assignments.created_at posted_on, subjects.name subject, employees.first_name employee
                from assignments
                inner join subjects on assignments.subject_id = subjects.id
                inner join employees on assignments.employee_id = employees.id
                where find_in_set($row_student[id], `student_list`) and ((assignments.created_at between '$weekStart' and '$weekEnd')
                OR (assignments.duedate between '$weekStart' and '$weekEnd') )  order by subjects.name asc  ";
//                echo $assignment_sql;

                $result_assignment = $conn->query($assignment_sql);
                echo '<div class="tab-pane tabs-animation fade ' . $show . '  mt-3 "  id="tab-content-' . $i . '"  role="tabpanel">
                    <div>
                        <div class="col-md">
                            <div class="main-card mb-3 card">
                                <div  class="card-body">
                     ';
                $show = '';
                $i++;
                if ($result_assignment->num_rows > 0) {

                    echo ' 
                     <div  class="table-responsive">
       
                                    <table style="table-layout: fixed" 
                                    class="mb-0 table  table-hover table-bordered mb-0 table">
                                        <colgroup>
                                            <col span="6">
                                            <col span="2" style="background-color: navajowhite">
                                        </colgroup>
                                        <thead>
                                        <tr align="center">
                                            <th >Subject</th>';

                    for ($j = 0; $j <= 6; $j++) {
                        // timestamp from ISO week date format
                        $ts = strtotime($year . 'W' . $week . $j);
                        if($show_date === 1) {
                            echo '<th   title="' . date('d-M-Y', $ts) . '" >' . date('l (d/M)', $ts) . ' </th>';
                        }
                        else {
                            echo '<th   title="' . date('d-M-Y', $ts) . '" >' . date('l ', $ts) . ' </th>';
                        }

                    }

                    echo ' </tr>
                            </th></thead><tbody >';

                    while ($row_assignment = mysqli_fetch_array($result_assignment)) {

                        $days = strtotime($row_assignment['duedate']) - strtotime(date('Y-m-d'));
                        $days = (int)round($days / (60 * 60 * 24));
//                        echo $days;

                        $assignmentCard = '<td></td>';
                        if ($days < 0) {
                            $assignmentCard = '
                        <td align = "center" class="card-shadow-secondary border mb-1 card card-body border-secondary "   
                         data-toggle="popover" data-placement="right" data-content="' . $row_assignment['content'] . '"
                        style=" font-size: 10px; padding: 0; ">
                        <a  href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '" class="card-title">' . $row_assignment['title'] . '</a>
                        <a> Due date is over</a>
                       </td>';
                        }
                        else if ($days == 0) {
                       $assignmentCard = '
                        <td align = "center" class="card-shadow-secondary border mb-3 card card-body border-danger"  
                           data-toggle="popover" data-placement="right" data-content="' . $row_assignment['content'] . '"
                        style=" font-size: 10px; padding: 0; ">
                        <a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '" class="card-title">' . $row_assignment['title'] . '</a>
                        <a> Already due </a>

                       </td>
                        ';
                        }
                        else if ($days > 0 && $days < 2) {
                            $assignmentCard = '
                        <td align = "center" class="card-shadow-secondary border mb-3 card card-body border-danger"  
                           data-toggle="popover" data-placement="right" data-content="' . $row_assignment['content'] . '"
                        style=" font-size: 10px; padding: 0; ">
                        <a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '" class="card-title">' . $row_assignment['title'] . '</a>
                        <a> Due ' . time_elapsed_string($row_assignment['duedate']) . ' </a>

                       </td> 
                        ';
                        }
                        else if ($days < 4 && $days >= 2) {
                            $assignmentCard = '
                        <td align = "center" class="card-shadow-secondary border mb-3 card card-body border-warning"  
                           data-toggle="popover" data-placement="right" data-content="' . $row_assignment['content'] . '"
                        style=" font-size: 10px; padding: 0; ">                                              
                        <a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '" class="card-title">' . $row_assignment['title'] . '</a>
                       <a> Due ' . time_elapsed_string($row_assignment['duedate']) . ' </a>
                       </td>
                        ';
                        }
                        else if ($days >= 4) {
                            $assignmentCard = '
                        <td align = "center" class="card-shadow-secondary border mb-3 card card-body border-success"  
                           data-toggle="popover" data-placement="right" data-content="' . $row_assignment['content'] . '"
                        style=" font-size: 10px; padding: 0; ">
                        <a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '" class="card-title">' . $row_assignment['title'] . '</a>
                        <a> Due ' . time_elapsed_string($row_assignment['duedate']) . ' </a>

                       </td>
                        ';
                        }

                        echo '<tr >
                           <td>' . $row_assignment['subject'] . '</td>';

                        if((date('Y-m-d', strtotime($row_assignment['posted_on'] )) >= $weekStart) &&  ( date('Y-m-d', strtotime($row_assignment['posted_on'] )) <= $weekEnd)) {
                            $postDay= (string)date('N', strtotime($row_assignment['posted_on']))%7;
                        if ( $postDay == 0) {
                            echo $assignmentCard ;
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if ( $postDay == 1) {
                            echo '<td></td>';
                            echo $assignmentCard ;
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if ( $postDay == 2){
                            echo '<td></td>';
                            echo '<td></td>';
                            echo $assignmentCard;
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if ( $postDay == 3) {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo $assignmentCard;
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if ( $postDay == 4){
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo $assignmentCard;
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if ( $postDay == 5) {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo $assignmentCard  . date('N', strtotime($row_assignment['posted_on'])) . '----'.date('N', strtotime($row_assignment['duedate']));
                            echo '<td></td>';
                        }
                        else if ( $postDay == 6) {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo $assignmentCard;

                        }
                        else {
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>  </td>';
                            echo '<td>   </td>';
                            echo '<td>  </td>';
                        }
                        }
//                        ---------------------------------
                        else if((date('Y-m-d', strtotime($row_assignment['duedate'])) >= $weekStart) &&  (date('Y-m-d', strtotime($row_assignment['duedate'] )) <= $weekEnd))  {
                            $dueDay= (string)date('N', strtotime($row_assignment['duedate']))%7;
                            if ($dueDay == 0) {
                                echo $assignmentCard;
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                            }
                            else if ($dueDay == 1)  {
                                echo '<td></td>';
                                echo $assignmentCard;
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                            }
                            else if ($dueDay == 2)  {
                                echo '<td></td>';
                                echo '<td></td>';
                                echo $assignmentCard;
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                            }
                            else if ($dueDay == 3)  {
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo $assignmentCard;
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                            }
                            else if ($dueDay == 4)  {
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo $assignmentCard;
                                echo '<td></td>';
                                echo '<td></td>';
                            }
                            else if ($dueDay == 5)  {
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo $assignmentCard;
                                echo '<td></td>';
                            }
                            else if ($dueDay == 6) {
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo $assignmentCard;

                            }
                            else {
                                echo '<td>   </td>';
                                echo '<td>   </td>';
                                echo '<td>   </td>';
                                echo '<td>   </td>';
                                echo '<td>  </td>';
                                echo '<td>   </td>';
                                echo '<td>  </td>';
                            }
                        }
                        else {
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>   </td>';
                            echo '<td>  </td>';
                            echo '<td>   </td>';
                            echo '<td>  </td>';
                        }

                        echo '</tr> ';
                    }
                    echo '</tbody></table></div>  <br><br>';
                } else {
                    echo 'No Assignments Found!';

                }
                echo ' </div>
                            </div>
                        </div>
                         </div>
                       </div>
';
            }
        }
    }
}

function time_elapsed_string($date)
{
    if (empty($date)) {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    $now = time();
    $unix_date = strtotime($date);
// check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }
// is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j] .= "s";
    }
    return "$difference $periods[$j] {$tense}";
}


//echo '
//    <div class="table-responsive">
//                        <div class="tab-pane tabs-animation fade"  id="tab-content-0" role="tabpanel">
//                            <div class="main-card mb-3 card">
//                                <div class="card-body">
//                                    <table class="mb-0 table table-striped table-hover table-bordered mb-0 table">
//                                        <colgroup>
//                                            <col span="6">
//                                            <col span="2" style="background-color: navajowhite">
//                                        </colgroup>
//                                        <thead>
//                                        <tr align="center">
//                                            <th>SUBJECT</th>
//                                            <th>SUNDAY</th>
//                                            <th>MONDAY</th>
//                                            <th>TUESDAY</th>
//                                            <th>WEDNESDAY</th>
//                                            <th>THURSDAY</th>
//                                            <th>FRIDAY</th>
//                                            <th>SATURDAY</th>
//                                        </tr>
//                                        </th></thead>
//                                    </table>
//                                </div>
//                            </div>
//                        </div>
//                    </div>
//';
