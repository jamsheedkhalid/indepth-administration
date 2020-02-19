<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


//check the current day
if (date('D') != 'Sun') {
    //take the last monday
    $weekStart = date('Y-m-d', strtotime('last Sunday'));
} else {
    $weekStart = date('Y-m-d');
}

if (date('D') != 'Sat') {
    $weekEnd = date('Y-m-d', strtotime('next Saturday'));
} else {
    $weekEnd = date('Y-m-d');
}
echo $weekStart;
echo $weekEnd;


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

            // set current date
            $date = date('Y-m-d', strtotime('Sunday this week'));
// parse about any English textual datetime description into a Unix timestamp
            $ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
            $year = date('o', $ts);
            $week = date('W', $ts);
// print week for the current date
// ------------------------ end week calculation  ---------------------

            while ($row_student = mysqli_fetch_array($student)) {
                $assignment_sql = " select  assignments.id id ,title, content ,duedate ,
                assignments.created_at posted_on, subjects.name subject, employees.first_name employee
                from assignments
                inner join subjects on assignments.subject_id = subjects.id
                inner join employees on assignments.employee_id = employees.id
                where find_in_set($row_student[id], `student_list`) and (assignments.created_at between '$weekStart' and '$weekEnd')";
                $assignment_sql .= '  order by subjects.name asc  ';
//                echo $assignment_sql;


                $result_assignment = $conn->query($assignment_sql);


                echo '<div class="tab-pane tabs-animation fade show "  id="tab-content-' . $i . '"  role="tabpanel">
                    <div>
                        <div class="col-md">
                            <div class="main-card mb-3 card">
                                <div  class="card-body">
                     ';
                $i++;
                if ($result_assignment->num_rows > 0) {
                    echo ' 
     <div  class="table-responsive">
       
                                    <table style="white-space: pre-line!important;" 
                                    class="mb-0 table table-striped table-hover table-bordered mb-0 table">
                                        <colgroup>
                                            <col span="6">
                                            <col span="2" style="background-color: navajowhite">
                                        </colgroup>
                                        <thead>
                                        <tr align="center">
                                            <th>Subject</th>';

                    for ($j = 0; $j <= 6; $j++) {
                        // timestamp from ISO week date format
                        $ts = strtotime($year . 'W' . $week . $j);
                        echo '<th>' . date('l (d/M/y) ', $ts) . ' </th>';
                    }
                    echo ' </tr>
                            </th></thead><tbody style="font-size: 10px">';
                    while ($row_assignment = mysqli_fetch_array($result_assignment)) {
                        echo '
                        <tr >
                           <td>' . $row_assignment['subject'] . '</td>';

                        if (date('N', strtotime($row_assignment['posted_on'])) === 0) {
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '1') {
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '2') {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '3') {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '4') {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '5') {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';
                            echo '<td></td>';
                        }
                        else if (date('N', strtotime($row_assignment['posted_on'])) === '6') {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a></td>';

                        } else {
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
                            echo '<td>  - </td>';
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
