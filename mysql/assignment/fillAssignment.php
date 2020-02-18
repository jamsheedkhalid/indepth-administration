<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$date = $_REQUEST['date'];
$type = $_REQUEST['type'];

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
            while ($row_student = mysqli_fetch_array($student)) {

                $assignment_sql = " select  assignments.id id ,title, content ,duedate ,assignments.created_at posted_on, subjects.name subject, employees.first_name employee
                from assignments
                inner join subjects on assignments.subject_id = subjects.id
                inner join employees on assignments.employee_id = employees.id
                where find_in_set($row_student[id], `student_list`)";

                if ($date !== '' && $type === 'due') {
                    $assignment_sql .= " AND DATE(assignments.duedate) = '$date' ";
                } else if ($date !== '' && $type === 'post') {
                    $assignment_sql .= "  AND DATE(assignments.created_at) = '$date' ";
                } else if ($date !== '' && $type === 'tomarrow') {
                    $assignment_sql .= "  AND assignments.duedate = DATE(DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY)) ";
                } else if ($date !== '' && $type === 'today') {
                    $assignment_sql .= " AND DATE(assignments.duedate) = CURDATE() ";
                } else if ($date !== '' && $type === 'yesterday') {
                    $assignment_sql .= "  AND assignments.duedate = DATE(DATE_ADD(CURRENT_DATE, INTERVAL -1 DAY)) ";

                }
                $assignment_sql .= "  order by duedate desc  ";
//                echo $assignment_sql;


                $result_assignment = $conn->query($assignment_sql);


                echo '<div class="tab-pane tabs-animation fade show active"  id="tab-content-' . $i . '"  role="tabpanel">
                    <div>
                        <div class="col-md">
                            <div class="main-card mb-3 card">
                                <div  class="card-body">
                                <div class="align-content-around">
                        <a  ><b>' . $row_student['last_name'] . '</b></a>
                        <a style="float: right" ><b>' .$row_student['grade']. ' '.$row_student['section']. '</b></a>
</div>';
                $i++;
                if ($result_assignment->num_rows > 0) {
                    echo ' 
 <div class="table-responsive">
                    <table class="mb-0 table table-striped table-hover table-bordered ">';
                    echo '<thead>
                    <tr>
                    <th>Subject</th>
                    <th>Assignment</th>
                    <th>Due</th>
                    <th>Assigned On</th>
                    <th>Teacher</th>
                    </tr>
                    </thead>';
                    while ($row_assignment = mysqli_fetch_array($result_assignment)) {
                        echo '  
                        <tr>
                           <td>' . $row_assignment['subject'] . '</td>
                           <td title="' . $row_assignment['content'] . '">
                           <a href="https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">' . $row_assignment['title'] . '</a>
                           </td>
                           <td>' . date('d-M-Y ', strtotime($row_assignment['duedate'])) . '</td>
                           <td>' . date('d-M-Y ', strtotime($row_assignment['posted_on'])) . '</td>
                           <td>' . $row_assignment['employee'] . '</td>
                        </tr> ';
                    }
                    echo '</table></div>  <br><br>';
                } else {
                    if ($type === 'all') {
                        echo 'No Assignments Found!';
                    } else if($type === 'due')  {
                        echo 'No Assignments Due on ' . date("D, d-M-Y", strtotime($date)) . '';
                    }else if($type === 'post')  {
                        echo 'No Assignments Posted on ' . date("D, d-M-Y", strtotime($date)) . '';
                    }else if($type === 'today')  {
                        echo 'No Assignments due for Today';
                    }else if($type === 'tomarrow')  {
                        echo 'No Assignments due for Tomarrow';
                    }else if($type === 'yesterday')  {
                        echo 'No Assignments was due for Yesterday';
                    }
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









