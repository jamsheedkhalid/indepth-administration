<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
$sql = " select familyid from guardians where user_id = '$_SESSION[user]' ";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $i=0;
    while ($row = mysqli_fetch_array($result)) {
        $sql = " select id, first_name, last_name from students where familyid = '$row[familyid]' ";
        $student = $conn->query($sql);
        if ($student->num_rows > 0) {
            while ($row_student = mysqli_fetch_array($student)) {
                $assignment_sql = " select  assignments.id id ,title, content ,duedate ,assignments.created_at posted_on, subjects.name subject, employees.first_name employee
                from assignments
                inner join subjects on assignments.subject_id = subjects.id
                inner join employees on assignments.employee_id = employees.id
                where find_in_set($row_student[id], `student_list`) order by duedate desc ";
                $result_assignment = $conn->query($assignment_sql);


                echo '<div class="tab-pane tabs-animation fade show active"  id="tab-content-'.$i.'"  role="tabpanel">
                    <div>
                        <div class="col-md">
                            <div class="main-card mb-3 card">
                                <div  class="card-body">
                        <h6 ><b>' . $row_student['last_name'] . '</b></h6>
'; $i++;
                if ($result_assignment->num_rows > 0) {
                    echo ' 
 
                    <table class="mb-0 table table-striped table-hover table-bordered">';
                    echo '<thead>
                    <tr>
                    <th>Subject</th>
                    <th>Assignment</th>
                    <th>Due</th>
                    <th>Assigned On</th>
                    <th>Assigned By</th>
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
                    echo '</table>  <br><br>';
                } else {
                    echo 'No Assignments Found!';
                }   echo ' </div>
                            </div>
                        </div>
                         </div>
                </div>';
            }
        }
    }
}









