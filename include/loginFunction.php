<?php
session_start();
function login()
{
    include 'config/database.php';

//    header('Location: dashboard.php');
    $sql = "select users.id user,users.first_name name from users where users.username = '$_POST[user]';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = $row['user'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['user'] = $user;
        }
        $sql = "SELECT
            *
            FROM
            privileges_users AS A
            INNER JOIN privileges_users AS B
            ON
            A.user_id = B.user_id
            WHERE
          (  A.privilege_id = 1 AND A.user_id='$user') OR A.user_id = '5047'";

//        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['login'] = 1;
            $_SESSION['user_type'] = 'teacher';
            header('Location: /../modules/academics/examination/generate-reports.php');
        } else {
            $sql = "SELECT
            *
            FROM users WHERE id='$user' AND ( username = 'James' OR username = 'Rahman' OR username = 'admin' OR username = 'Hesham' ) ";
//        echo $sql;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $_SESSION['login'] = 1;
                $_SESSION['user_type'] = 'admin';
                header('Location:  /main.php');
            } else {
                $sql = " SELECT * from guardians where guardians.user_id ='$user'; ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $_SESSION['login'] = 1;
                    $_SESSION['user_type'] = 'parent';
                    header('Location:  /parent-home.php');
                } else {
                    $sql = " SELECT
            *
            FROM
            privileges_users AS A
            INNER JOIN privileges_users AS B
            ON
            A.user_id = B.user_id
            WHERE
            A.privilege_id = 35 AND A.user_id='$user' ";

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $_SESSION['login'] = 1;
                        $_SESSION['user_type'] = 'hr';
                        header('Location:  /modules/hr/certificate/employee-certificate.php');
                    } else {
                        $_SESSION['noaccess'] = 1;
                        header('Location: /index.php');
                    }
                }

            }
        }


    }


}

function checkLoggedIn()
{
    if (!isset($_SESSION['login'])) {
        $_SESSION['notloggedin'] = 1;
        header('Location: /index.php');
    }
}