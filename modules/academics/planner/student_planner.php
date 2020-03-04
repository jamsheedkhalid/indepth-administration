<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if ($_SESSION['user_type'] === 'teacher' || $_SESSION['user_type'] === 'admin') {
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
                                <div class="page-title-subheading">
                                    Updated regularly, <span style="color:darkred">&#9733</span> Indicates updates tasks
                                    <br> تحديثها بانتظام ، <span style="color:darkred">&#9733</span> ويشير مهام
                                    التحديثات

                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions col-sm-6">

                            <form class="row" id="print_form" method="post"
                                  target="_blank">

                                <div class="col">
                                    <label for="grade"
                                           class="">Select Grade</label>
                                    <select name="grade" id="grade" onchange="selectSection();"
                                            class="form-control-sm form-control">
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="week_date"
                                           class="">Select Week</label>
                                    <input name="week_date" id="week_date" type="date"
                                           onchange="loadStudentPlanner('student-planner',this.value)"
                                           class="form-control-sm form-control"
                                           value="<?php echo date('Y-m-d'); ?>"
                                    >

                                </div>
                                <div class="col">

                                    <div class="d-inline-block dropdown" style="margin-top: 25px">
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"
                                                class="btn-shadow dropdown-toggle btn btn-dark">
                                                                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                                                                    <i class="fas fa-print fa-w-20"></i>
                                                                                </span>
                                            Print
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                             class="dropdown-menu dropdown-menu-right">
                                            <ul class="nav flex-column">

                                                <li class="nav-item">
                                                    <a href="javascript:void(0);"
                                                       class="nav-link">
                                                        <span>
                                                                        <button class="btn btn-sm "
                                                                                formaction="/mysql/planner/print_report.php"
                                                                                title="Print Planner Report"
                                                                        > Submission Report</button>
                                                                                            </span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="javascript:void(0);"
                                                       class="nav-link">
                                                        <span>
                                                                        <button class="btn btn-sm "
                                                                                formaction="/mysql/planner/print.php"
                                                                                title="Print Planner "
                                                                        > Planner</button>
                                                                                            </span>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>


                                    <!--                                    <button class="btn btn-sm " title="Print Planner Report" style="margin-top: 30px"   ><i class="fa fa-print"> Print Report</i></button>-->
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
                <div>
                    <div class="main-card mb-3 card">
                        <div class="card-body ">
                            <div class="row ">

                                <div hidden aria-hidden="true" class="position-relative form-group col-sm-3">
                                    <label for="section"
                                           class="">Select Grade </label>
                                    <select name="section" id="section"
                                            onchange=" loadStudentPlanner('student-planner', 'curr');"
                                            class="form-control-sm form-control">
                                    </select>


                                </div>

                                <div style="overflow-x: scroll;min-width: 100% !important; ">
                                    <div id='student-planner'>
                                    </div>
                                </div>
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


    <!-- Modal -->
    <div class="modal fade" id="weeklyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id='modalBody'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="saveBtn" onclick="saveTask();">Save</button>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewWeeklyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="viewModalBody">

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="editWeeklyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="editModalBody">

                </div>
            </div>

        </div>
    </div>


    </body>


    </html>

    <script type="text/javascript" src="/assets/scripts/main.js"></script>
    <script> document.getElementById('liPlanner').classList.add("mm-active")</script>
    <script> document.getElementById('liPlanner_edit').classList.add("mm-active")</script>
    <script type="text/javascript" src="/js/planner.js"></script>

    <script> document.title = "Planner - InDepth";</script>
<?php } else {
    header('Location: /no-access/index.html');
}

?>