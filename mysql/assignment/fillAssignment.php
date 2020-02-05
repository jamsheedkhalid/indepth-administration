<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$sql = " select  assignments.id id ,title, content ,duedate ,assignments.created_at posted_on, subjects.name subject
from assignments
inner join subjects on assignments.subject_id = subjects.id
where find_in_set('2030', `student_list`) order by duedate desc ";
//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo '<table class="mb-0 table table-striped table-hover table-bordered">';
    echo '<thead>
            <tr>
            <th>Subject</th>
            <th>Assignment</th>
            <th>Due</th>
            <th>Posted On</th>
            </tr>
            </thead>';
    while ($row = mysqli_fetch_array($result)) {
        echo '  <tr>
        <td>' . $row['subject'] . '</td>
        <td title="' . $row['content'] . '"><a href="https://alsanawbar.school/assignments/' . $row['id'] . '">' . $row['title'] . '</a></td>
        <td>' . date('d-M-Y ', strtotime($row['duedate'])) . '</td>
        <td>' . date('d-M-Y ', strtotime($row['posted_on'])) . '</td>
</tr> ';
    }
    echo '</table>';
}


