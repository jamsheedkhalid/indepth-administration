<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn()
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
                            <i class="pe-7s-note icon-gradient bg-premium-dark">
                            </i>
                        </div>
                        <div>MY ASSIGNMENTS
                            <div class="page-title-subheading">View assignments for your children
                            </div>
                        </div>
                    </div>
                    <div hidden class="page-title-actions">
                        <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                                class="btn-shadow mr-3 btn btn-dark">
                            <i class="fa fa-star"></i>
                        </button>
                    </div>
                </div>
            </div>
            <ul id='studentListAssignment' class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">

            </ul>
            <div class="tab-content">

                <!-- ----------------------------------------------------------------------------------->
                <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

                    <div>

                        <div class="col-md">
                            <div class="main-card mb-3 card">
                                <div id='studentAssignment1'  class="card-body">


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
</body>


</html>

<script type="text/javascript" src="/assets/scripts/main.js"></script>
<script> document.getElementById('liAssignment').classList.add("mm-active")</script>
<script> document.getElementById('liAssignment_studentAssignment').classList.add("mm-active")</script>
<script type="text/javascript" src="/js/assignment.js"></script>
<script> document.title = "Reports - InDepth";</script>