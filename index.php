<?php

session_start();
date_default_timezone_set('Asia/Dubai');

if (isset($_SESSION['login'])) {
    if(S_SESSION['user_type'] === 'admin') {
        header('Location: main.php');
    }
    else if(S_SESSION['user_type'] === 'parent') {
        header('Location: parent-home.php');
    }
    else if(S_SESSION['user_type'] === 'teacher') {
        header('Location: modules/academics/examination/generate-reports.php');
    }
}
else {
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>AL Sanawbar - InDepth</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script src="https://s3.amazonaws.com/api_play/src/js/jquery-2.1.1.min.js"></script>
    <script src="https://s3.amazonaws.com/api_play/src/js/vkbeautify.0.99.00.beta.js"></script>
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="login_assets/images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="login_assets/css/main.css">
    <!--===============================================================================================-->

    <script>
        $(function () {
            $("#generate-button").click(function () {
                var instanceurl = $("#instanceurl").val();
                var client_id = $("#client_id").val();
                var client_secret = $("#client_secret").val();
                var redirect_uri = $("#redirect_uri").val();
                var username = $("#username").val();
                var password = $("#password").val();
                if (username !== "" || password !== "") {
                    var token_input = $("#token");
                    var result_div = $("#result");
                    document.getElementById("iurl").value = document.getElementById("instanceurl").value;
                    generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div);
                }
            });
        });
    </script>

    <script>
        function generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div) {
            token_input.val("");
            result_div.html("");
            try {
                var xmlDoc;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", instanceurl + "/oauth/token", true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function (e) {
                    if (xhr.readyState === 4) {
                        var a = JSON.parse(e.target.responseText);
                        token_input.val(a["access_token"]);
                        if (token_input.val() !== "") {
                            document.getElementById('invalidCredentials').style.display = 'none';

                            $('#welcome-modal').modal('show');
                            setTimeout(function () {
                                $('#welcome-modal').modal('hide');
                            }, 6000);
                            document.getElementById("generate-report").click();
                        } else {
                            document.getElementById('invalidCredentials').style.display = 'inline';

                        }
                        result_div.html(show_response(e.target.responseText));
                        xmlDoc = this.responseText;
                        txt = "";
                    }


                };
                xhr.send("client_id=" + client_id + "&client_secret=" + client_secret + "&grant_type=password&username=" + username + "&password=" + password + "&redirect_uri=" + redirect_uri);
            } catch (err) {
                alert(err.message);
            }
        }


        function show_response(str) {
            str = vkbeautify.xml(str, 4);
            return str.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br />");
        }


        function validateForm() {
            var x = document.forms["frm"]["token"].value;
            if (x === "") {
                alert("Generate an access token first");
                return false;
            }
        }

    </script>

</head>
<body>

<!--API Connecting -->
<input id="instanceurl" type="hidden" name="instanceurl" value="https://alsanawbar.school"/>
<input id="client_id" type="hidden" value="0071f42cbac22497c1a1647d5a8f7ee35a922361c0563460310b0a219849af18"/>
<input id="client_secret" type="hidden" value="977f4bcce24f6fb415f132e2ab2df3ca6b7aa76c8ffb4116ccb7a2ec4631ae34"/>
<input id="redirect_uri" type="hidden" value="https://indepth.alsanawbar.school/"/>

<div class="limiter">
    <div class="  container-login100">
        <div class="wrap-login100"
        ">
        <div style="margin-top: -50px!important;" class="login100-pic js-tilt" data-tilt>
            <img src="assets/images/sanawbar-logo.jpeg " alt="IMG">
        </div>

        <form style="margin-top: -50px!important;"  id="userform" class="login100-form validate-form" onsubmit = "event.preventDefault();">
					<span class="login100-form-title">
						Al Sanawbar School Portal
					</span>

            <?php
            if (isset($_SESSION['notloggedin'])) {
                ?>

                <div id='noaccess' class="alert alert-warning wrap-input100  m-b-8">
                    <strong>Not Logged in!</strong> Please login first to continue.
                </div>

                <?php
                unset($_SESSION['notloggedin']);
            }
            ?>

            <?php
            if (isset($_SESSION['noaccess'])) {
                ?>

                <div id='noaccess' class="alert alert-danger wrap-input100  m-b-12">
                    <strong>Unauthorized!</strong> You are unauthorized to use this system. <br>Only authorized staffs
                    have the access. <br>Please contact system administrator.
                </div>

                <?php
                unset($_SESSION['noaccess']);
            }
            ?>
            <div id='invalidCredentials' class=" " style="display: none; ">
                <strong>Invalid!</strong> <small>Username/Password is invalid.</small>
            </div>


            <div class="wrap-input100 validate-input" data-validate="Valid username is required">
                <input class="input100" type="text" id="username" name="username" placeholder="Username">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Password is required">
                <input class="input100" type="password" id="password" name="password" placeholder="Password">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
            </div>

            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit" id="generate-button">
                    Login
                </button>
            </div>

            <div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
                <a class="txt2" onclick="alert('Contact InDepth Support at support@indepth.ae');">
                    Username / Password?
                </a>
            </div>


        </form>

        <form name="frm" onsubmit="return validateForm()" action="login.php" method="POST" style="display: none">
            <input id="token" type="hidden" name="token">
            <input id="iurl" type="hidden" name="iurl">
            <input id="user" name="user">
            <input type="submit" id="generate-report" value="Generate Reports">
        </form>
        <div id="welcome-modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <p style="text-align: center"><strong> Successfully Logged in. </strong></p>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>


<script>
    let input = document.getElementById("password");
    input.addEventListener("keyup", function (event) {
        document.getElementById("user").value = document.getElementById("username").value;
        if (event.keyCode === 13)
            document.getElementById("generate-button").click();
    });
</script>


<!--===============================================================================================-->
<script src="login_assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="login_assets/vendor/bootstrap/js/popper.js"></script>
<script src="login_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="login_assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="login_assets/vendor/tilt/tilt.jquery.min.js"></script>
<script>
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>
<?php } ?>