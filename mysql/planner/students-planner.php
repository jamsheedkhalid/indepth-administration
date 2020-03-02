<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$date = $_REQUEST['date_type'];
$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];




if(date('w',strtotime($date)) == 0) {
//    echo 'Event is on a sunday';
    $date = date('Y-m-d',strtotime($date. ' + 1 days'));
}
//echo $date;
$ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
$year = date('o', $ts);
$week = date('W', $ts);
$weekStart = strtotime($year . 'W' . $week . 0);
$weekStart = date('Y-m-d', $weekStart);
$weekEnd = strtotime($year . 'W' . $week . 6);
$weekEnd = date('Y-m-d', $weekEnd);

$ts = strtotime($date);
// find the year (ISO-8601 year number) and the current week
$year = date('o', $ts);
$week = date('W', $ts);
// print week for the current date
$show = 'show active';


echo ' 
                     <div style="min-width: 100%!important;" >
       
                                    <table   style="min-width: 100%!important;"
                                    class="mb-0 table table-bordered table-striped  student-planner ">
                                        <thead>
                                        <tr align="center">
                                            <th class="headcol"  >DAY \ SUB</th>';

$sql = "
select subjects.id id, subjects.name name
from subjects
         inner join batches on subjects.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
where subjects.is_deleted = 0
  and courses.id = '$grade'
  and courses.is_deleted = 0
  and batches.is_deleted = 0
  and batches.is_active = 1
  and batches.name LIKE '%2020%'
group by subjects.name
order by subjects.name;
";
//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $col_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        echo '<th id="' . $row['id'] . '">' . $row['name'] . '</th>';
    }
}
echo ' </tr>  </th></thead><tbody >';
for ($j = 0; $j <= 6; $j++) {
    // timestamp from ISO week date format
    $ts = strtotime($year . 'W' . $week . $j);
    {
        echo '<tr><th  class="headcol"  title="' . date('Y-m-d', $ts) . '" >' . strtoupper(date('l', $ts)) . '<br>' . (date('d-M-Y', $ts)) . ' </th>';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $date = date('Y-m-d', $ts);
                $task = " select id,title, created_at, updated_at from indepth_weekly_planner where indepth_weekly_planner.subject_id = '$row[id]' and duedate = '$date'; ";
                $task_result = $conn->query($task);
// echo $task;
                if ($task_result->num_rows > 0) {
                    echo '<td align="center" >';
                    while ($task_row = mysqli_fetch_array($task_result)) {
                        if($task_row['created_at'] == $task_row['updated_at'] ){
echo '<a  onclick="viewTaskDetails(this.id)" id="' . $task_row['id'] . '"  class="mb-2 mr-2 badge badge-warning  " >' . $task_row['title'] . '</a> <br>';}
                        else{
                        echo '<a  onclick="viewTaskDetails(this.id)" id="' . $task_row['id'] . '" class="mb-2 mr-2 badge badge-warning " >' . $task_row['title'] . ' <span style="color:darkred" >&#9733</span></a><br>';
                    }}
                    echo '</td>';
                } else {
                    echo '<td  ></td>';
                }

            }
        }

//        while ($col !== 0) {
//            echo '<td></td>';
//            $col--;
//
//        }
        echo '</tr>';
    }

}
echo '</tbody></table></div>  <br><br>';



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



