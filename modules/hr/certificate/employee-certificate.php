<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if ($_SESSION['user_type'] === 'admin') {
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
                                <i class="pe-7s-ribbon icon-gradient bg-premium-dark">
                                </i>
                            </div>
                            <div>EMPLOYEE CERTIFICATES
                                <div class="page-title-subheading">Generate and print employee certificates
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
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                     class="dropdown-menu dropdown-menu-right">
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
                        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0"
                           title="Certificate of Employement">
                            <span>COE</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1"
                           title="Salary Certificate">
                            <span>SALARY</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2"
                           title="No Objection Letter">
                            <span>NOL</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <!-- -----------------------------------COE begins------------------------------------------------>
                    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

                        <div>
                            <form target="_blank" method="post" class="row" autocomplete="off"
                                  action="/mysql/hr/certificate/print-coe.php">
                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <div>
                                                <div class="row">
                                                    <div class="position-relative form-group col-sm"><label
                                                                for="coe_toAddress">To</label>
                                                        <input required
                                                                name="coe_toAddress" autocapitalize="on" type="text"
                                                                id="coe_toAddress" class="form-control-sm form-control"
                                                                placeholder="To Address" >

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="position-relative form-group col-sm"><label
                                                                for="coe_name">Employee</label>
                                                        <input onfocusin="employeeArray(this.value,'coe_name')"
                                                               onchange="fillEmployeeDetails('coe_')"
                                                                name="coe_name" type="text" list="coe_display"
                                                                id="coe_name" class="form-control-sm form-control"
                                                                placeholder="Name" required>

                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="position-relative form-group col-sm">
                                                        <label
                                                                for="coe_passport">Passport/EID</label>
                                                        <input required
                                                                name="coe_passport"
                                                                id="coe_passport" class="form-control-sm form-control"
                                                                placeholder="Passport Number">

                                                    </div>
                                                    <div class="position-relative form-group col-sm">
                                                        <label
                                                                for="coe_nationality">Nationality</label>
                                                        <input required
                                                                name="coe_nationality"
                                                                id="coe_nationality" class="form-control-sm form-control"
                                                                placeholder="Nationality">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="position-relative form-group col-sm"><label
                                                                for="coe_jobTitle">Designation</label>
                                                        <input required
                                                                name="coe_jobTitle"
                                                                id="coe_jobTitle" class="form-control-sm form-control"
                                                                placeholder="Job Title">

                                                    </div>
                                                    <div class="position-relative form-group col-sm">
                                                        <label
                                                                for="coe_joinDate">Joining Date</label>
                                                        <input required
                                                                name="coe_joinDate"
                                                                id="coe_joinDate" class="form-control-sm form-control"
                                                                placeholder="Date of Joining">

                                                    </div>
                                                </div>
                                                <div class="position-relative form-group"><label for="coe_authorizer"
                                                                                                 class="">Authorizer</label>
                                                    <select name="coe_authorizer" id="coe_authorizer"
                                                            class="form-control-sm form-control">
                                                        <option value="1">MR. OMAR SARRIEDINE</option>
                                                        <option value="2">MS. REEMA SARRIEDINE</option>
                                                        <option value="3">MR. TALAAT SARRIEDINE</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_coe_authorizer"
                                                           id="hidden_term_studentWise" value=""/>
                                                </div>

                                                <button class="mt-1 btn btn-block btn-outline-success" name="coeSubmit"
                                                        type="submit">
                                                    Generate
                                                </button>
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
                            <form target="_blank" method="post" class="row"
                                  action="/mysql/exam-center/generate_pdf.php">
                                <div class="col-md-6">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="sectionGradeSelect">Grade</label>
                                                    <select name="sectionGradeSelect"
                                                            onchange="initialize_hidden_input('sectionGradeSelect','hidden_grade');fillSections('sectionSectionSelect',this.id);fillTermsSectionWise(this.id,'sectionSectionSelect','sectionTermSelect','sectionWise');"
                                                            id="sectionGradeSelect"
                                                            class="form-control-sm form-control ">
                                                        <option disabled selected>Select Grade</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_grade" id="hidden_grade"
                                                           value=""/>

                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="sectionSectionSelect">Section</label>
                                                    <select name="sectionSectionSelect"
                                                            onchange="fillTermsSectionWise('sectionGradeSelect',this.id,'sectionTermSelect','sectionWise');"
                                                            id="sectionSectionSelect"
                                                            class="form-control-sm form-control">
                                                        <option disabled selected>Select Section</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_section" id="hidden_section"
                                                           value=""/>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm ">
                                                    <label for="sectionTermSelect">Term</label>
                                                    <select name="sectionTermSelect" id="sectionTermSelect"
                                                            class="form-control-sm form-control">
                                                        <option>Select Term</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_term" id="hidden_term" value=""/>
                                                </div>

                                            </div>
                                            <button type="submit" name="sectionSubmit" class="mt-1 btn btn-success ">
                                                Generate Report
                                            </button>

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!---------------------------------------grade wise begins ----------------------------------- ---------->
                    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                        <div>
                            <form target="_blank" method="post" class="row"
                                  action="/mysql/exam-center/generate_pdf_grade_wise.php">
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
                                                    <select name="gradeTermSelect" id="gradeTermSelect"
                                                            class="form-control-sm form-control">
                                                        <option>Select Term</option>
                                                    </select>
                                                    <input type="hidden" name="hidden_term_gradeWise"
                                                           id="hidden_term_gradeWise" value=""/>
                                                </div>

                                            </div>
                                            <button type="submit" name="submitGradeWise" class="mt-1 btn btn-success">
                                                Generate Report
                                            </button>

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
    <script> document.getElementById('liHR').classList.add("mm-active")</script>
    <script> document.getElementById('liHR_Certificate').classList.add("mm-active")</script>
    <!--    <script type="text/javascript" src="/js/exam-center.js"></script>-->
    <script type="text/javascript" src="/js/autofill_employee_names.js"></script>
    <script type="text/javascript" src="/js/coe.js"></script>

    <script> document.title = "HR - InDepth";</script>

<?php } else {
    header('Location: /no-access/index.html');
}

?>