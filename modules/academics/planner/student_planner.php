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
                                    <b >  Updated regularly, <span style="color:darkred" >&#9733</span> Indicates updates tasks </b>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions col-sm-6">
                            <div class="row">
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
                            </div>


                            <!--                        <div class="d-inline-block dropdown">-->
                            <!--                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
                            <!--                                    class="btn-shadow dropdown-toggle btn btn-info">-->
                            <!--                                            <span class="btn-icon-wrapper pr-2 opacity-7">-->
                            <!--                                                <i class="fas fa-filter fa-w-20"></i>-->
                            <!--                                            </span>-->
                            <!--                                Filters-->
                            <!--                            </button>-->
                            <!--                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">-->
                            <!--                                <ul class="nav flex-column">-->
                            <!--                                    <li class="nav-item">-->
                            <!---->
                            <!--                                        <a href="javascript:void(0);" class="nav-link">-->
                            <!--                                            <i class="nav-link-icon lnr-picture"></i>-->
                            <!--                                            <span>-->
                            <!--                                                             <label for="assignment_date"> Select Due Date</label><input-->
                            <!--                                                        type="date" id="assignment_date" placeholder="Select Due Date "-->
                            <!--                                                        name="assignment_date"-->
                            <!--                                                        onchange="fillAssignments('studentAssignment1','due',this.value);">-->
                            <!--                                                        </span>-->
                            <!--                                        </a>-->
                            <!---->
                            <!---->
                            <!--                                    </li>-->
                            <!--                                    <li class="nav-item">-->
                            <!---->
                            <!--                                        <a href="javascript:void(0);" class="nav-link">-->
                            <!--                                            <i class="nav-link-icon lnr-picture"></i>-->
                            <!--                                            <span>-->
                            <!--                                                             <label for="assignment_date"> Select Post Date</label><input-->
                            <!--                                                        type="date" id="assignment_date" placeholder="Select Post Date "-->
                            <!--                                                        name="assignment_date"-->
                            <!--                                                        onchange="fillAssignments('studentAssignment1','post',this.value);">-->
                            <!--                                                        </span>-->
                            <!--                                        </a>-->
                            <!---->
                            <!---->
                            <!--                                    </li>-->
                            <!--                                    <li class="nav-item">-->
                            <!--                                        <a href="javascript:void(0);" onclick="fillAssignments('studentAssignment1','today',this.value);" class="nav-link">-->
                            <!--                                            <i class="nav-link-icon lnr-book"></i>-->
                            <!--                                            <span>-->
                            <!--                                                            Today's Assignments-->
                            <!--                                                        </span>-->
                            <!--                                        </a>-->
                            <!--                                    </li>-->
                            <!--                                    <li class="nav-item">-->
                            <!--                                        <a href="javascript:void(0);" onclick="fillAssignments('studentAssignment1','tomarrow',this.value);" class="nav-link">-->
                            <!--                                            <i class="nav-link-icon lnr-picture"></i>-->
                            <!--                                            <span>-->
                            <!--                                                            Tomarrow's Assignment-->
                            <!--                                                        </span>-->
                            <!--                                        </a>-->
                            <!--                                    </li>-->
                            <!--                                    <li class="nav-item">-->
                            <!--                                        <a href="javascript:void(0);" onclick="fillAssignments('studentAssignment1','yesterday',this.value);" class="nav-link">-->
                            <!--                                            <i class="nav-link-icon lnr-picture"></i>-->
                            <!--                                            <span>-->
                            <!--                                                           Yesterday's Assignment-->
                            <!--                                                        </span>-->
                            <!--                                        </a>-->
                            <!--                                    </li>-->
                            <!--                                </ul>-->
                            <!--                            </div>-->
                            <!--                        </div>-->
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

                <div class="modal-body" id='modalBody' >
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