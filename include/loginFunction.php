<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

function login()
{
    include 'config/database.php';
//    header('Location: dashboard.php');
    $sql = "select users.id user,users.first_name name, users.last_name last_name, users.username username from users where users.username = '$_POST[user]';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = $row['user'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['user'] = $user;
            $_SESSION['username'] = $row['username'];
        }
        $sql = "SELECT
            *
            FROM
            privileges_users AS A
            INNER JOIN privileges_users AS B
            ON
            A.user_id = B.user_id
            WHERE
            A.privilege_id = 1 AND A.user_id='$user'";

//        echo $sql;
        $result = $conn->query($sql);
//        if ($result->num_rows > 0) {
        if (  $_SESSION['username'] == '1556') {
            $_SESSION['login'] = 1;
            $_SESSION['user_type'] = 'teacher';
            header('Location: /../modules/academics/examination/term-reports.php');
        } else {
            $sql = "SELECT
            *
            FROM users WHERE id='$user' AND ( username = 'Jamsheed' OR username = 'Rahman' OR username = 'admin' OR username = 'Hesham' OR username = 'Reem' ) ";
//        echo $sql;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $_SESSION['login'] = 1;
                $_SESSION['user_type'] = 'admin';
                header('Location:  /main.php');
            } else {
                $sql = " SELECT * from guardians where guardians.user_id ='$user'; ";
                $result = $conn->query($sql);
//                if ($result->num_rows > 0) {
//                    $_SESSION['login'] = 1;
//                    $_SESSION['user_type'] = 'parent';
//                    header('Location:  /parent-home.php');
//                }
//                else
                    {
                    $sql = " SELECT
            *
            FROM
            privileges_users AS A
            INNER JOIN privileges_users AS B
            ON
            A.user_id = B.user_id
            WHERE
            A.privilege_id = 26 AND A.user_id='$user' ";

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $_SESSION['login'] = 1;
                        $_SESSION['user_type'] = 'finance';
                        header('Location:  /modules/finance/invoices/tax-invoice.php');
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

                            $sql = " SELECT
            *
            FROM
            users  where username = '$_POST[user]' and student = 1;";

                            $result = $conn->query($sql);
//                            if ($result->num_rows > 0) {
//                                $_SESSION['login'] = 1;
//                                $_SESSION['user_type'] = 'student';
//                                header('Location:  /student-home.php');
//
//                            } else
                                {
                                $_SESSION['noaccess'] = 1;
                                header('Location: /index.php');
                            }








                        }


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

function checkMasterAdmin($user){
    $sql = "select * from users where id = '$user' and admin = 1 and general_admin = 0 ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return true;
    }
    else {
        return false;
    }

}