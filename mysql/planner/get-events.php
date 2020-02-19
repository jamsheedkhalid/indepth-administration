<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
header('Content-Type: application/json');
$assignments[] = '';
$sql = " select familyid from guardians where user_id = '$_SESSION[user]' ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $sql = " select students.id id, first_name, last_name, batches.name section, course_name grade from students 
                inner join batches on students.batch_id = batches.id
                inner join courses on batches. course_id = courses.id where familyid = '$row[familyid]' order by students.last_name ";
        $student = $conn->query($sql);
        if ($student->num_rows > 0) {
            while ($row_student = mysqli_fetch_array($student)) {
                $assignment_sql = " select  assignments.id id ,
                title, 
                content , 
                duedate ,
                assignments.created_at posted_on, 
                subjects.name subject, 
                employees.first_name employee
                from assignments
                inner join subjects on assignments.subject_id = subjects.id
                inner join employees on assignments.employee_id = employees.id
                where find_in_set($row_student[id], `student_list`)  order by duedate desc  ";
//                echo $assignment_sql;
                $result_assignment = $conn->query($assignment_sql);
                if ($result_assignment->num_rows > 0) {
                    while ($row_assignment = mysqli_fetch_array($result_assignment)) {
                        $assignments[] = [
                            'title' => $row_assignment['title'],
                            'start' => $row_assignment['posted_on'],
                            'url' => 'https://alsanawbar.school/assignments/' . $row_assignment['id'] . '">'
                        ];


                    }

                }


                }

        }
    }
    echo json_encode($assignments);
}
//echo json_encode([
//    [
//        'title' => 'Lorem Ipsum',
//        'start' =>  '2019-08-13T13:00:00',
//        'end' =>  '2019-08-13T14:00:00',
//        'description' => 'hello',
//
//    ],
//    [
//        'id' => '123',
//        'title' => 'The Test',
//        'start' =>  '2019-08-10T10:00:00',
//        'description' => 'hello',
//        'url'=> 'http://google.com/'
//    ]
//]);
























