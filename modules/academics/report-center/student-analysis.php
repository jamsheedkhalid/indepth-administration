<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'teacher') {
    ?>

    <!doctype html>
    <html lang="en">
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/head.php');
    ?>
    <body onload="initDataTable()">
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

                <form target="_blank" method="post" action="/mysql/student-analysis/print-result.php">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="pe-7s-graph3 icon-gradient bg-premium-dark">
                                </i>
                            </div>
                            <div>STUDENT ANALYSIS
                                <div class="page-title-subheading">Analysis on students based on their marks achieved in
                                    Term Exams
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">

                            <div class="d-inline-block ">
                                <button type="submit" title="Print Mark List"
                                        class="btn-shadow btn btn-dark">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-print "></i>
                                            </span>
                                    Print
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <div  class="row">
                        <div class="col">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                        <div class="row">
                                            <div class="position-relative form-group col-sm-3"><label
                                                        for="gradeSelect">Grade</label>
                                                <select onchange="fillSections('sectionSelect',this.id);fillSubjects('gradeSelect','sectionSelect','subject');fillTerms('gradeSelect','sectionSelect','term');getAnalysis()"
                                                        name="gradeSelect"
                                                        id="gradeSelect" class="form-control-sm form-control ">
                                                    <option disabled selected>Select Grade</option>
                                                </select>
                                            </div>
                                            <div class="position-relative form-group col-sm-3"><label
                                                        for="sectionSelect">Section</label>
                                                <select multiple="multiple" onchange="fillSubjects('gradeSelect','sectionSelect','subject');fillTerms('gradeSelect','sectionSelect','term');getAnalysis()"
                                                        name="sectionSelect"
                                                        id="sectionSelect" class="form-control-sm form-control ">
                                                    <option disabled selected>Select Section</option>
                                                </select>
                                            </div>
                                            <div class="position-relative form-group col-sm-3">
                                                <label for="subject">Subject</label>
                                                <select multiple  name="subject" id="subject"
                                                        onchange="getAnalysis();"
                                                        class="form-control-sm form-control">
                                                    <option disabled selected>Select Subject</option>
                                                </select>
                                            </div>
                                            <div class="position-relative form-group col-sm-3">
                                                <label for="term">Term</label>
                                                <select multiple name="term" id="term"
                                                        onchange="getAnalysis();"
                                                        class="form-control-sm form-control">
                                                    <option disabled selected>Select Term</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                            <h5 class="card-title">FILTERS</h5>
                                                <div class="row"  style="padding-left: 20px;">
                                                    <div class="position-relative col-sm ">
                                                        <label class="form-check-label"><input name="filter" type="radio" value="all" class="form-check-input"   onclick="getAnalysis();"
                                                                                               checked> All</label>
                                                    </div>
                                                    <div class="position-relative col-sm ">
                                                        <label class="form-check-label"><input name="filter" type="radio" value="failed" class="form-check-input"   onclick="getAnalysis();"
                                                                                                > Failed</label>
                                                    </div>
                                                    <div class="position-relative col-sm-3 ">
                                                        <label class="form-check-label"><input name="filter" type="radio" value="passed" class="form-check-input"   onclick="getAnalysis();"
                                                                                               > Passed</label>
                                                    </div>
                                                    <div class="position-relative col-sm-3 ">
                                                        <label class="form-check-label"><input name="filter" type="radio"  id="custom" value="custom" class="form-check-input"  onclick="getAnalysis();">
                                                            More than <input style="padding-left: 10px" type="number" min="0" max="100" id="custom_filter" value="75" onkeyup="triggerCustomFilter();" name="custom_filter"></label>
                                                    </div>
                                                    <div class="position-relative col-sm-3 ">
                                                        <label class="form-check-label"><input name="filter" type="radio"  id="custom_less" value="custom_less" class="form-check-input"  onclick="getAnalysis();">
                                                            Less Than <input style="padding-left: 10px" type="number" min="0" max="100" id="custom_filter_less" value="75" onkeyup="triggerCustomFilterLess();" name="custom_filter_less"></label>
                                                    </div>

                                                </div>
                                                <div class="row"  style="padding-left: 20px;">
                                                    <div class="position-relative col-sm-3">
                                                        <label class="form-check-label"><input onchange="getAnalysis();" type="checkbox" id="show_ar_names" name="show_ar_names" class="form-check-input"> Use Arabic Names</label>
                                                    </div>
                                                    <div class="position-relative col-sm-3">
                                                        <label class="form-check-label"><input onchange="getAnalysis();" type="checkbox" id="show_parent_name" name="show_parent_name" class="form-check-input">  Parent Name</label>
                                                    </div>
                                                    <div class="position-relative col-sm-3">
                                                        <label class="form-check-label"><input onchange="getAnalysis();" type="checkbox" id="show_family_id" name="show_family_id" class="form-check-input">  Family ID</label>
                                                    </div>
                                                    <div class="position-relative col-sm-3">
                                                        <label class="form-check-label"><input onchange="getAnalysis();" type="checkbox" id="show_contact" name="show_contact" class="form-check-input">  Contact Number</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

<!--                                    result div -->
                                    <div class="row">
                                        <div class="col"  style='padding-top: 20px'>
                                            <h5 class="card-title">MARK LIST</h5>
                                            <div id="result_div">
                                                <div class="alert alert-primary fade show" role="alert">Please use the drop-downs to view the <strong>Mark Lists</strong>!</div>

                                            </div>


                                        </div>
                                    </div>
<!--                                    end result div-->

                                </div>
                                    </div>
                                </div>



                    </div>
                </div>
                </form>
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
    <script> document.getElementById('liReportCenter').classList.add("mm-active")</script>
    <script> document.getElementById('liReportCenter_StudentAnalysis').classList.add("mm-active")</script>
    <script type="text/javascript" src="/js/student_analysis.js"></script>
    <script type="text/javascript" src="/js/dataTable.js"></script>
    <script> document.title = "Report Center - InDepth";</script>

<?php } else {
    header('Location: /no-access/index.html');
}

?>