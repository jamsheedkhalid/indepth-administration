<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if ($_SESSION['user_type'] === 'parent' || $_SESSION['user_type'] === 'admin') {
    ?>

    <!doctype html>
    <html lang="en">
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/head.php');
    ?>
    <body>

    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/app-header.php');
        include($_SERVER['DOCUMENT_ROOT'] . '/theme-setting.php');
        ?>
        <div class="app-main">
            <?php
            include($_SERVER['DOCUMENT_ROOT'] . '/side-bar.php');
            ?>
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="pe-7s-date icon-gradient bg-premium-dark">
                                </i>
                            </div>
                            <div>WEEKLY PLANNER
                                <div class="page-title-subheading">Planner for students academics activities
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-content" id=''>
                    <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <table class="table-bordered table ">
                                    <colgroup>
                                        <col span="5">
                                        <col span="2" style="background-color: #ccc">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>SUNDAY</th>
                                        <th>MONDAY</th>
                                        <th>TUESDAY</th>
                                        <th>WEDNESDAY</th>
                                        <th>THURSDAY</th>
                                        <th>FRIDAY</th>
                                        <th>SATURDAY</th>
                                    </tr>
                                    </th></thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <?php
            include($_SERVER['DOCUMENT_ROOT'] . '/footer-bar.php');
            ?>

            <!--        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>-->

        </div>
    </div>
    </body>


    </html>

    <script type="text/javascript" src="/assets/scripts/main.js"></script>
    <script> document.getElementById('liWeeklyPlanner').classList.add("mm-active")</script>
    <script type="text/javascript" src="/js/planner.js"></script>

    <script> document.title = "Planner - InDepth";</script>
<?php } else {
    header('Location: /no-access/index.html');
}

?>