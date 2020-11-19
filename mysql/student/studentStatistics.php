<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/fee_defaulter_sql.php');
session_start();

$sql = " select students.last_name student, students.id id from students
inner join users on students.user_id = users.id
where users.username = '$_SESSION[username]' ";

//echo $sql;
$result = $conn->query($sql);
$tabIndex = 0;
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {


        $assignments = "select assignments.id    id,
       title,
       content,
       duedate,
       assignments.created_at posted_on,
       subjects.name          subject,
       subjects.code          sub_code,
       employees.first_name   employee
from assignments
         inner join subjects on assignments.subject_id = subjects.id
         inner join employees on assignments.employee_id = employees.id
where find_in_set($row[id], `student_list`) and DATE(assignments.created_at) = CURDATE()
order by duedate desc ";
        $ass_result = $conn->query($assignments);


        echo '
                    <div class="col-md-8 col-xl-4">
                        <div class="card mb-3 widget-content bg-midnight-bloom">
                            <div class=" text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading align-center"> MY ASSIGNMENTS</div>
                                    <div class="widget-subheading"><a  >Todays\' Assignment</a></div>';


        if ($ass_result->num_rows > 0) {
            while ($ass_row = mysqli_fetch_array($ass_result)) {
                echo '  <div><a style="color: whitesmoke" href="https://alsanawbar.school/assignments/' . $ass_row['id'] . '">' . $ass_row['subject'] . '</a></div>';
            }
        } else {
            echo '  <div> No Assignments</div>';
        }

        echo ' <br> <div class="widget-subheading"><a >Assignment\'s Due For Tomarrow</a></div>';

        $due_ass = " select assignments.id    id,
       title,
       content,
       duedate,
       assignments.created_at posted_on,
       subjects.name          subject,
       subjects.code          sub_code,
       employees.first_name   employee
from assignments
         inner join subjects on assignments.subject_id = subjects.id
         inner join employees on assignments.employee_id = employees.id
where find_in_set($row[id], `student_list`)  AND assignments.duedate = DATE(DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY))
order by duedate desc ";


        $assDue_result = $conn->query($due_ass);
        if ($assDue_result->num_rows > 0) {
            while ($assDue_row = mysqli_fetch_array($assDue_result)) {
                echo '  <div><a style="color: whitesmoke" href="https://alsanawbar.school/assignments/' . $assDue_row['id'] . '">' . $assDue_row['subject'] . '</a></div>';
            }

        } else {
            echo '<div> No Assignments Due</div> ';
        }
//        echo '<a style="float: left; bottom: 0px; color: silver" href="/modules/academics/assignment/student_assignment.php"><small>View All</small></a> ';
        $tabIndex++;
        echo ' </div>


                            </div>
                        </div>
                    </div> ';


    }
} else {
    echo '
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-midnight-bloom">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">No Students</div>    
                                </div>

                            </div>
                        </div>
                    </div>';
}

//check user blocked or not to show report card
$sql = " select students.last_name student, students.id id, students.admission_no admission_no from students
inner join users on students.user_id = users.id
where users.username = '$_SESSION[username]' ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $unpaid = fee_defaulter($row['admission_no']);
        if ($unpaid) {
            echo '  <div  class=" d-lg-block col-md-10 col-xl-6">
                    <div class="card mb-3 widget-content bg-asteroid">
                        <div class="widget-content-wrapper text-white">
                            <div class="widget-content-left">
                                <div class="widget-heading"> MY REPORT CARDS</div>
                                <div class="widget-subheading">Select Term</div>                           
                                <div style="margin-top: 10px" >
                                <select id="student_term" name="student_term"></select></div>
                                <div style="margin-top: 10px"> 
                                <button disabled  formaction="" class="mb-2 mr-2 btn btn-primary btn-lg btn-block"> View Report Card</button>
                                </div>
                            </div>
                            <div class="widget-content-right" style="margin: 10px">
                            <div class="alert alert-warning fade show" role="alert"><small>You are not allowed to view  Report Cards!</small> <br><strong>Please contact system administrator! </strong> </div>
                                                
</div>
                        </div>
                    </div>
                </div>';
        }
        else {
            echo '  <div  class=" d-lg-block col-md-6 col-xl-4">
                    <div class="card mb-3 widget-content bg-asteroid">
                        <div class="widget-content-wrapper text-white">
                            <div class="widget-content-left">
                            <form target="_blank" method="post">
                                <div class="widget-heading"> MY REPORT CARDS</div>
                                <div class="widget-subheading">Select Term</div>
                                <div style="margin-top: 10px" >
                                <select id="student_term" name="student_term"></select></div>
                                <div style="margin-top: 10px">
                                <button type="submit" name="report_submit"  formaction="/mysql/student/view-report.php" class="mb-2 mr-2 btn btn-primary btn-lg btn-block"> View Report Card</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
        }


    }
}


$conn->close();
