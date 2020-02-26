<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
session_start();

$sql = "SELECT familyid from guardians where user_id = '$_SESSION[user]'";
//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $student_sql = "Select 
       students.id id, 
       students.last_name last_name, 
       students.first_name first_name, 
       admission_no, 
       course_name grade, 
       batches.name section
        from students 
        inner join batches on students.batch_id = batches.id
        inner join courses on courses.id = batches.course_id  
        where familyid = '$row[familyid]'
        order by last_name  ";
//        echo $student_sql;
        $result1 = $conn->query($student_sql);
        if ($result1->num_rows > 0) {

            echo '
                               <table  class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th>Name</th>
                                        <th class="text-lg-left">Grade</th>
                                        <th class="text-center">Outstanding Fees</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>                        
    <tbody>';
            while ($row1 = mysqli_fetch_array($result1)) {

                $fee_balance = " Select CAST(sum(balance) as DECIMAL (9,2)) balance from finance_fees where student_id = '$row1[id]' ";
                $result_fee = $conn->query($fee_balance);
                if ($result_fee->num_rows > 0) {
                    while ($row_fee = mysqli_fetch_array($result_fee)) {
                        echo '     
                                    <tr>
                                        <td class="text-center text-muted">' . $row1['admission_no'] . '</td>
                                        <td>
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
              
                                                    </div>
                                                    <div class="widget-content-left flex2">
                                                        <div class="widget-heading">' . $row1['last_name'] . '</div>
                                                        <div class="widget-subheading opacity-7">' . $row1['first_name'] . '</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-lg-left">' . $row1['grade'] . '-' . $row1['section'] . '</td>
                                      
                                        <td class="text-center">
                                        ';
                        if($row_fee['balance'] !== '0.00') {
                            echo '   <a class="badge badge-warning">' . $row_fee['balance'] . '</a>';
                        }
                        else if ($row_fee['balance'] === '0.00') {
                            echo '   <div class="badge badge-success"> PAID </div>';
                        }

                            echo '       </td>
                                        <td class="text-center">
                                            <a type="button" id="PopoverCustomT-1" href="https://alsanawbar.school/student/profile/' . $row1['id'] . '" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>

                                   ';

                    }
                }
            }
        }
        echo '</tbody></table>';


    }
}
