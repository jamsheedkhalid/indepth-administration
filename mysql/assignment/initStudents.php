<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$sql = " select familyid from guardians where user_id = '$_SESSION[user]' ";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
//        echo $row['familyid'];

        $sql = " select id,first_name, last_name from students where familyid = '$row[familyid]' order by students.last_name ";
//        echo $sql;
        $student = $conn->query($sql);
        if ($student->num_rows > 0) {
            $i=0;
            $active = 'active';
            while ($row_student = mysqli_fetch_array($student)) {

                $parent = str_replace(' ', '', $_SESSION['name']);
                $parent = strtolower($parent);
                $student_name = strtolower($row_student['last_name']);
                $student_name = str_replace(' ', '', $student_name);
                $student_name = explode($parent, $student_name);


                echo '                <li class="nav-item">
                    <a role="tab" title="'.$row_student['first_name'].'" class="nav-link '.$active.' " id="tab-1" data-toggle="tab" href="#tab-content-'.$i.'">
                        <span>' .ucwords($student_name[0])  . '</span>
                    </a>
                </li>';
                $active = 'a';
                $i++;
            }
        }


    }
}


