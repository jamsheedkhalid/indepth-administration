<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();

$sql = " select students.last_name student, students.id id, students.user_id userid from guardians
inner join students on guardians.familyid = students.familyid
where guardians.user_id = '$_SESSION[user]' order by students.last_name asc";

//echo $sql;
$result = $conn->query($sql);
$tabIndex = 0;
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $sql_user_block = "
select * from users where users.id = '$row[userid]' and is_blocked = 1";
        $result_student = $conn->query($sql_user_block);
        if($result_student->num_rows > 0){

            echo '  <div  class=" d-lg-block col-md-10 col-xl-6">
                    <div class="card mb-3 widget-content bg-asteroid">
                        <div class="widget-content-wrapper text-white">
                            <div class="widget-content-left">
                                <div class="widget-heading">'.$row['student'].'</div>
                                <div class="widget-subheading">Select Term</div>                           
                                <div style="margin-top: 10px" >
                                <select class="student_term" name="student_term"></select></div>
                                <div style="margin-top: 10px"> 
                                <button disabled  formaction="" class="mb-2 mr-2 btn btn-primary btn-lg btn-block"> View Report Card</button>
                                </div>
                            </div>
                            <div class="widget-content-right" style="margin: 10px">
                            <div class="alert alert-warning fade show" role="alert"><small>You are not allowed to view  Report Cards!</small> <br><strong>Please clear outstanding fees due! </strong> </div>
                                                
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
                                <div class="widget-heading"> '.$row['student'].'</div>
                                <div class="widget-subheading">Select Term</div>                           
                                <div hidden class="widget-subheading"><input name="student_user_id" value="'.$row['userid'].'"></div>                           
                                <div style="margin-top: 10px" >
                                <select class="student_term form-control-sm form-control" name="student_term"></select></div>
                                <div style="margin-top: 10px"> 
                                <button type="submit" name="report_submit"  formaction="/mysql/parent/view-report.php" class="mb-2 mr-2 btn btn-primary btn-lg btn-block"> View Report Card</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';

        }


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

$conn->close();
