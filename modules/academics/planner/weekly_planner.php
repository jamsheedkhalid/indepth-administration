<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
?>


<!doctype html>
<html lang="en">
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/head.php');
?>
<body>

<div class="app-container app-theme-white body-tabs-shadow">
    <?php
    //        include($_SERVER['DOCUMENT_ROOT'] . '/app-header.php');
    //        include($_SERVER['DOCUMENT_ROOT'] . '/theme-setting.php');
    ?>
    <div class="app-main">
        <?php
        //            include($_SERVER['DOCUMENT_ROOT'] . '/side-bar.php');
        ?>
        <div class="app-main__inner ">
            <div class="app-page-title ">
                <div class="page-title-wrapper">
                    <div class="page-title-heading col ">
                        <div class="page-title-icon">
                            <i class="pe-7s-date icon-gradient bg-premium-dark">
                            </i>
                        </div>
                        <div c>WEEKLY PLANNER
                            <div class="page-title-subheading">Planner for students academics activities
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions col-sm-4 ">
                        <div class="row m-0 p-0">
                        <div class="position-relative form-group col-sm-6 ">
                            <label for="grade"
                                   class="">Select Grade</label>
                            <select name="grade" id="grade" onchange="selectSection();"
                                    class="form-control-sm form-control">
                            </select>
                            <input type="hidden" name="hidden_select"
                                   value=""/>
                        </div>

                        <div  class="d-inline-block dropdown  col-sm-6">
                            <a href="/index.php"
                                    class="btn-shadow btn btn-dark">
                                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                                            <i class="fas fa-edit "></i>
                                                                        </span>
                                Edit Planner
                            </a>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
            <div>
                <div class="
                 mb-3 card">

                        <div class="row">

                            <div hidden class="position-relative form-group col-sm-3">
                                <label for="section"
                                       class="">Select Section</label>
                                <select name="section" id="section"
                                        onchange=" loadWeeklyPlanner('student-planner', 'curr');"
                                        class="form-control-sm form-control">
                                </select>
                                <input type="hidden" name="hidden_section"
                                       value=""/>
                            </div>
                        </div>
                        <div>
                            <div id='weekly-planner' style=" min-width: 100% !important; overflow-x:scroll ">
                            </div>
                        </div>



                </div>

            </div>


        </div>


        <?php
        //            include($_SERVER['DOCUMENT_ROOT'] . '/footer-bar.php');
        ?>

        <!--        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>-->

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


</body>


</html>

<script type="text/javascript" src="/assets/scripts/main.js"></script>
    <script> document.getElementById('liPlanner').classList.add("mm-active")</script>
<script type="text/javascript" src="/js/weekly-planner.js"></script>

<script> document.title = "Planner - InDepth";</script>
