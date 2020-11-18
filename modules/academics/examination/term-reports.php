<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'teacher' )
{
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
                            <i class="pe-7s-graph2 icon-gradient bg-premium-dark">
                            </i>
                        </div>
                        <div>STUDENT TERM REPORTS
                            <div class="page-title-subheading">Generate and print term report cards for students
                            </div>
                        </div>
                    </div>
                    <div hidden class="page-title-actions">
                        <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                                class="btn-shadow mr-3 btn btn-dark">
                            <i class="fa fa-star"></i>
                        </button>
                        <div class="d-inline-block dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow dropdown-toggle btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-business-time fa-w-20"></i>
                                            </span>
                                Buttons
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-inbox"></i>
                                            <span>
                                                            Inbox
                                                        </span>
                                            <div class="ml-auto badge badge-pill badge-secondary">86</div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-book"></i>
                                            <span>
                                                            Book
                                                        </span>
                                            <div class="ml-auto badge badge-pill badge-danger">5</div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-picture"></i>
                                            <span>
                                                            Picture
                                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a disabled href="javascript:void(0);" class="nav-link disabled">
                                            <i class="nav-link-icon lnr-file-empty"></i>
                                            <span>
                                                            File Disabled
                                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                        <span>Student Wise</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                        <span>Section Wise</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                        <span>Grade Wise</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">

                <!-- -----------------------------------student wise begins------------------------------------------------>
                <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

                    <div >
                        <form   target="_blank" method="post" class="row"
                         >
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="gradeSelect">Grade</label>
                                                    <select onchange="initialize_hidden_input('gradeSelect','hidden_grade_studentWise');fillSections('sectionSelect',this.id);fillStudents();fillTerms();"
                                                            name="gradeSelect"
                                                            id="gradeSelect" class="form-control-sm form-control ">
                                                        <option disabled selected>Select Grade</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_grade_studentWise"
                                                           id="hidden_grade_studentWise" value=""/>
                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="sectionSelect">Section</label>
                                                    <select onchange="initialize_hidden_input('sectionSelect','hidden_section_studentWise');fillStudents();fillTerms();" name="sectionSelect"
                                                            id="sectionSelect" class="form-control-sm form-control ">
                                                        <option disabled selected>Select Section</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_section_studentWise"
                                                           id="hidden_section_studentWise" value=""/>
                                                </div>
                                            </div>
                                            <div class="position-relative form-group"><label for="studentSelect"
                                                                                             class="">Student</label>
                                                <select onchange="initialize_hidden_input('studentSelect','hidden_student_studentWise');fillTerms();" name="studentSelect" id="studentSelect"
                                                        class="form-control-sm form-control">
                                                    <option>Select Student</option>
                                                </select>
                                                <input type="hidden" name="hidden_student_studentWise"
                                                       id="hidden_student_studentWise" value=""/>

                                            </div>
                                            <div class="position-relative form-group"><label for="termSelect"
                                                                                             class="">Term</label>
                                                <select name="termSelect" id="termSelect" onchange="initialize_hidden_input('termSelect','hidden_term_studentWise');"
                                                        class="form-control-sm form-control">
                                                    <option>Select Term</option>
                                                </select>
                                                <input type="hidden" name="hidden_term_studentWise"
                                                       id="hidden_term_studentWise" value=""/>
                                            </div>

                                            <button formaction="/mysql/exam-center/term-only-reports/generate_pdf_student_wise.php" class="mt-1 btn btn-success" name="studentSubmitTerm" type="submit">
                                                Generate Term Report
                                            </button>


                                            <button formaction="/mysql/exam-center/term-reports/generate_pdf_student_wise.php" class="mt-1 btn btn-success" name="studentSubmit" type="submit">
                                                Generate Complete Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5>Marks Split in Percent</h5>
                                        <div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="studentAssessment">Assessment </label>
                                                    <input name="studentAssessment" type="number" min="0" max="100"
                                                           id="studentAssessment" class="form-control-sm form-control "
                                                           value="70"/>
                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="studentTerm">Term</label>
                                                    <input name="studentTerm" type="number" min="0" max="100"
                                                           id="studentTerm" class="form-control-sm form-control "
                                                           value="30"/></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- --------------------------------section wise begins-------------------------------------------------->

                <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                    <div>
                        <form  target="_blank" method="post" class="row"
                              >
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="sectionGradeSelect">Grade</label>
                                                <select name="sectionGradeSelect"
                                                        onchange="initialize_hidden_input('sectionGradeSelect','hidden_grade');fillSections('sectionSectionSelect',this.id);fillTermsSectionWise(this.id,'sectionSectionSelect','sectionTermSelect','sectionWise');"
                                                        id="sectionGradeSelect" class="form-control-sm form-control ">
                                                    <option disabled selected>Select Grade</option>
                                                </select>
                                                <input type="hidden" name="hidden_grade" id="hidden_grade" value=""/>

                                            </div>
                                            <div class="position-relative form-group col-sm"><label
                                                        for="sectionSectionSelect">Section</label>
                                                <select name="sectionSectionSelect"
                                                        onchange="fillTermsSectionWise('sectionGradeSelect',this.id,'sectionTermSelect','sectionWise');"
                                                        id="sectionSectionSelect" class="form-control-sm form-control">
                                                    <option disabled selected>Select Section</option>
                                                </select>
                                                <input type="hidden" name="hidden_section" id="hidden_section"
                                                       value=""/>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm ">
                                                <label for="sectionTermSelect">Term</label>
                                                <select name="sectionTermSelect" id="sectionTermSelect" onchange="initialize_hidden_input('sectionTermSelect','hidden_term');"
                                                        class="form-control-sm form-control">
                                                    <option>Select Term</option>
                                                </select>
                                                <input type="hidden" name="hidden_term" id="hidden_term" value=""/>
                                            </div>

                                        </div>
                                        <button type="submit"  formaction="/mysql/exam-center/term-only-reports/generate_pdf.php" name="sectionSubmitTerm" class="mt-1 btn btn-success ">
                                            Generate Term Report
                                        </button>

                                        <button type="submit"  formaction="/mysql/exam-center/term-reports/generate_pdf.php" name="sectionSubmit" class="mt-1 btn btn-success ">
                                            Generate Complete Report
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5>Marks Split in Percent</h5>
                                        <div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="sectionAssessment">Assessment </label>
                                                    <input name="sectionAssessment" type="number" min="0" max="100"
                                                           id="sectionAssessment" class="form-control-sm form-control "
                                                           value="70"/>
                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="sectionTerm">Term</label>
                                                    <input name="sectionTerm" type="number" min="0" max="100"
                                                           id="sectionTerm" class="form-control-sm form-control "
                                                           value="30"/></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!---------------------------------------grade wise begins ----------------------------------- ---------->
                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                    <div>
                        <form  target="_blank"  method="post" class="row"
                              >
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">


                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="gradeGradeSelect">Grade</label>
                                                <select
                                                        name="gradeGradeSelect"
                                                        onchange="initialize_hidden_input('gradeGradeSelect','hidden_grade_gradeWise');fillTermsSectionWise(this.id, 'sectionSectionSelect' ,'gradeTermSelect', 'gradeWise')"
                                                        id="gradeGradeSelect" class="form-control-sm form-control ">
                                                    <option disabled selected>Select Grade</option>
                                                </select>
                                                <input type="hidden" name="hidden_grade_gradeWise"
                                                       id="hidden_grade_gradeWise" value=""/>
                                            </div>
                                            <div class="position-relative form-group col-sm ">
                                                <label for="gradeTermSelect">Term</label>
                                                <select name="gradeTermSelect" id="gradeTermSelect"  onchange="initialize_hidden_input('gradeTermSelect','hidden_term_gradeWise');"
                                                        class="form-control-sm form-control">
                                                    <option>Select Term</option>
                                                </select>
                                                <input type="hidden" name="hidden_term_gradeWise"
                                                       id="hidden_term_gradeWise" value=""/>
                                            </div>

                                        </div>
                                        <button type="submit" formaction="/mysql/exam-center/term-only-reports/generate_pdf_grade_wise.php"
                                                name="submitGradeWiseTerm" class="mt-1 btn btn-success">
                                            Generate Term Report
                                        </button>
                                        <button type="submit" formaction="/mysql/exam-center/term-reports/generate_pdf_grade_wise.php"
                                                name="submitGradeWise" class="mt-1 btn btn-success">
                                            Generate Complete Report
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5>Marks Split in Percent</h5>
                                        <div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="gradeAssessment">Assessment </label>
                                                    <input name="gradeAssessment" type="number" min="0" max="100"
                                                           id="gradeAssessment" class="form-control-sm form-control "
                                                           value="70"/>
                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="gradeTerm">Term</label>
                                                    <input name="gradeTerm" type="number" min="0" max="100"
                                                           id="gradeTerm" class="form-control-sm form-control "
                                                           value="30"/></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
<script> document.getElementById('liExamination').classList.add("mm-active")</script>
<script> document.getElementById('liExamination_ReportCard').classList.add("mm-active")</script>
<script type="text/javascript" src="/js/term_reports.js"></script>
<script> document.title = "Reports - InDepth";</script>

<?php }
else {
    header('Location: /no-access/index.html');
}

?>