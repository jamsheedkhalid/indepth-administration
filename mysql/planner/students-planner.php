<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

$date_type = $_REQUEST['date_type'];
$section = $_REQUEST['section'];
$grade = $_REQUEST['grade'];


switch ($date_type) {
    case 'curr':
        $date = date('Y-m-d', strtotime('Sunday next week'));
        $show_date = 0;
        break;
    case 'last':
        $date = date('Y-m-d', strtotime('Sunday last week'));
        $show_date = 1;
        break;
    case 'next':
        $date = date('Y-m-d', strtotime('Sunday next week'));
        $show_date = 1;
        break;
    default:
        $date = date('Y-m-d', strtotime('Sunday this week'));
        $show_date = 0;
}
// parse about any English textual datetime description into a Unix timestamp
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

echo '<div class="tab-pane tabs-animation fade ' . $show . '  mt-3 "  id="tab-content-0"  role="tabpanel">
                                               <div class=" card">
                                <div  class="card-body">';
echo ' 
                     <div  class="table-responsive">
       
                                    <table  style="width: 100%" id="weekly-planner"
                                    class="mb-0 table table-bordered weekly-planner zui-table">
                                        <thead>
                                        <tr align="center">
                                            <th class="headcol"  >DAY \ SUB</th>';

$sql = "select subjects.id id, subjects.name name from subjects
where subjects.is_deleted = 0
  and batch_id = '$section';
";
//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $col_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        echo '<th id="'.$row['id'].'">' . $row['name'] . '</th>';
    }
}
echo ' </tr>  </th></thead><tbody >';
for ($j = 0; $j <= 6; $j++) {
    // timestamp from ISO week date format
    $ts = strtotime($year . 'W' . $week . $j);
    if ($show_date === 1) {
        echo '<tr ><th class="headcol"  title="' . date('Y-m-d', $ts) . '" ><' . strtoupper(date('l d/M', $ts)) . ' </th>';
        echo '</tr>';

    } else {
        echo '<tr><th  class="headcol"  title="' . date('Y-m-d', $ts) . '" >' . strtoupper(date('l ', $ts)) . ' </th>';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $date = date('Y-m-d', $ts);
                $task = " select id,title from indepth_weekly_planner where alsanawbar.indepth_weekly_planner.subject_id = '$row[id]' and duedate = '$date'; ";
                $task_result = $conn->query($task);
// echo $task;
                if ($task_result->num_rows > 0) {
                    echo '<td>';
                    while ($task_row = mysqli_fetch_array($task_result)) {
                        echo '<a  onclick="viewTaskDetails(this.id)" id="'.$task_row['id'].'" class="mb-2 mr-2 badge badge-warning " >' .$task_row['title'].'</a><br>';
                    }
                    echo '</td>';
                }
                else {
                    echo '<td ></td>';
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
echo ' </div>
                            </div>
                        </div>
                      
';


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



