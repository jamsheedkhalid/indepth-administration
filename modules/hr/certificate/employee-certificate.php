<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if($_SESSION['user_type'] === 'hr' || $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'finance' )
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
                                <i class="pe-7s-ribbon icon-gradient bg-premium-dark">
                                </i>
                            </div>
                            <div>EMPLOYEE CERTIFICATES
                                <div class="page-title-subheading">Generate and print employee certificates
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
                           title="No Objection Letter">
                            <span>NOL</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2"
                           title="Salary Certificate">
                            <span>SALARY</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <!-- -----------------------------------COE begins------------------------------------------------>
                    <div class="tab-pane tabs-animation fade show active " id="tab-content-0" role="tabpanel">

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
                                                        <input required value="Whom It May Concern"
                                                               name="coe_toAddress" autocapitalize="on" type="text"
                                                               id="coe_toAddress" class="form-control-sm form-control"
                                                               placeholder="To Address">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="position-relative form-group col-sm-4"><label
                                                                for="coe_id">ID</label>
                                                        <input
                                                                name="coe_id" type="text"
                                                                onchange="fillEmployeeDetails();"
                                                                id="coe_id" class="form-control-sm form-control"
                                                                placeholder="ID" required>
                                                        <input
                                                                name="coe_ms" id="coe_ms" type="text" hidden>
                                                        <div>

                                                        </div>

                                                    </div>
                                                    <div class="position-relative form-group col-sm"><label
                                                                for="coe_name">Employee</label>
                                                        <!--                                                                onfocusin="employeeArray(this.value,'coe_name')"-->
                                                        <!--                                                               onchange="fillEmployeeDetails('coe_')"-->
                                                        <input
                                                                name="coe_name" type="text"
                                                                onchange="fillEmployeeDetails();"
                                                                id="coe_name" class="form-control-sm form-control"
                                                                placeholder="Name" required>
                                                        <div>

                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row"

                                                <datalist class="list-inline"
                                                          style="max-height: 200px; overflow-y: scroll"
                                                          id="coe_display"></datalist>

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

                <!-- --------------------------------NOL begins-------------------------------------------------->

                <div class="tab-pane tabs-animation fade  " id="tab-content-1" role="tabpanel">

                    <div>
                        <form target="_blank" method="post" class="row" autocomplete="off"
                              action="/mysql/hr/certificate/print-nol.php">
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm"><label
                                                            for="nol_toAddress">To</label>
                                                    <input required value="Whom It May Concern"
                                                           name="nol_toAddress" autocapitalize="on" type="text"
                                                           id="nol_toAddress" class="form-control-sm form-control"
                                                           placeholder="To Address">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="position-relative form-group col-sm-4"><label
                                                            for="nol_id">ID</label>
                                                    <input
                                                            name="nol_id" type="text"
                                                            onchange="fillEmployeeDetailsNol();"
                                                            id="nol_id" class="form-control-sm form-control"
                                                            placeholder="ID" required>
                                                    <input
                                                            name="nol_ms" id="nol_ms" type="text" hidden>
                                                    <div>

                                                    </div>

                                                </div>
                                                <div class="position-relative form-group col-sm"><label
                                                            for="nol_name">Employee</label>
                                                    <!--                                                                onfocusin="employeeArray(this.value,'coe_name')"-->
                                                    <!--                                                               onchange="fillEmployeeDetails('coe_')"-->
                                                    <input
                                                            name="nol_name" type="text"
                                                            onchange="fillEmployeeDetailsNol();"
                                                            id="nol_name" class="form-control-sm form-control"
                                                            placeholder="Name" required>
                                                    <div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row"

                                            <datalist class="list-inline"
                                                      style="max-height: 200px; overflow-y: scroll"
                                                      id="nol_display"></datalist>

                                        </div>
                                        <div class="row">

                                            <div class="position-relative form-group col-sm">
                                                <label
                                                        for="nol_passport">Passport/EID</label>
                                                <input required
                                                       name="nol_passport"
                                                       id="nol_passport" class="form-control-sm form-control"
                                                       placeholder="Passport Number">

                                            </div>
                                            <div class="position-relative form-group col-sm">
                                                <label
                                                        for="nol_nationality">Nationality</label>
                                                <input required
                                                       name="nol_nationality"
                                                       id="nol_nationality" class="form-control-sm form-control"
                                                       placeholder="Nationality">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="nol_jobTitle">Designation</label>
                                                <input required
                                                       name="nol_jobTitle"
                                                       id="nol_jobTitle" class="form-control-sm form-control"
                                                       placeholder="Job Title">

                                            </div>
                                            <div class="position-relative form-group col-sm">
                                                <label
                                                        for="nol_joinDate">Joining Date</label>
                                                <input required
                                                       name="nol_joinDate"
                                                       id="nol_joinDate" class="form-control-sm form-control"
                                                       placeholder="Date of Joining">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="nol_reason">Reason</label>
                                                <input required
                                                       name="nol_reason" autocapitalize="on" type="text"
                                                       id="nol_reason" class="form-control-sm form-control"
                                                       placeholder="">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="nol_decl">Deceleration</label>
                                                <input required
                                                       name="nol_decl" autocapitalize="on" type="text"
                                                       id="nol_decl" class="form-control-sm form-control"
                                                       placeholder="">

                                            </div>
                                        </div>

                                        <div class="position-relative form-group"><label for="nol_authorizer"
                                                                                         class="">Authorizer</label>
                                            <select name="nol_authorizer" id="nol_authorizer"
                                                    class="form-control-sm form-control">
                                                <option value="1">MR. OMAR SARRIEDINE</option>
                                                <option value="2">MS. REEMA SARRIEDINE</option>
                                                <option value="3">MR. TALAAT SARRIEDINE</option>
                                            </select>
                                            <input type="hidden" name="hidden_nol_authorizer"
                                                   value=""/>
                                        </div>

                                        <button class="mt-1 btn btn-block btn-outline-success" name="nolSubmit"
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


            <!---------------------------------------salary begins ----------------------------------- ---------->
            <div class="tab-pane tabs-animation fade  " id="tab-content-2" role="tabpanel">

                <div>
                    <form target="_blank" method="post" class="row" autocomplete="off"
                          action="/mysql/hr/certificate/print-salary.php">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm"><label
                                                        for="salary_toAddress">To</label>
                                                <input required value="Whom It May Concern"
                                                       name="salary_toAddress" autocapitalize="on" type="text"
                                                       id="salary_toAddress" class="form-control-sm form-control"
                                                       placeholder="To Address">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="position-relative form-group col-sm-4"><label
                                                        for="salary_id">ID</label>
                                                <input
                                                        name="salary_id" type="text"
                                                        onchange="fillEmployeeDetailsSalary();"
                                                        id="salary_id" class="form-control-sm form-control"
                                                        placeholder="ID" required>
                                                <input
                                                        name="salary_ms" id="salary_ms" type="text" hidden>
                                                <div>

                                                </div>

                                            </div>
                                            <div class="position-relative form-group col-sm"><label
                                                        for="salary_name">Employee</label>
                                                <!--                                                                onfocusin="employeeArray(this.value,'coe_name')"-->
                                                <!--                                                               onchange="fillEmployeeDetails('coe_')"-->
                                                <input
                                                        name="salary_name" type="text"
                                                        onfocusout="fillEmployeeDetailsSalary();"
                                                        id="salary_name" class="form-control-sm form-control"
                                                        placeholder="Name" required>
                                                <div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="row"

                                        <datalist class="list-inline"
                                                  style="max-height: 200px; overflow-y: scroll"
                                                  id="salary_display"></datalist>

                                    </div>
                                    <div class="row">

                                        <div class="position-relative form-group col-sm">
                                            <label
                                                    for="salary_passport">Passport/EID</label>
                                            <input required
                                                   name="salary_passport"
                                                   id="salary_passport" class="form-control-sm form-control"
                                                   placeholder="Passport Number">

                                        </div>
                                        <div class="position-relative form-group col-sm">
                                            <label
                                                    for="salary_nationality">Nationality</label>
                                            <input required
                                                   name="salary_nationality"
                                                   id="salary_nationality" class="form-control-sm form-control"
                                                   placeholder="Nationality">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="position-relative form-group col-sm"><label
                                                    for="salaryl_jobTitle">Designation</label>
                                            <input required
                                                   name="salary_jobTitle"
                                                   id="salary_jobTitle" class="form-control-sm form-control"
                                                   placeholder="Job Title">

                                        </div>
                                        <div class="position-relative form-group col-sm">
                                            <label
                                                    for="salary_joinDate">Joining Date</label>
                                            <input required
                                                   name="salary_joinDate"
                                                   id="salary_joinDate" class="form-control-sm form-control"
                                                   placeholder="Date of Joining">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="position-relative form-group col-sm"><label
                                                    for="salary_amount">Salary</label>
                                            <input required
                                                   name="salary_amount" autocapitalize="on" type="text"
                                                   id="salary_amount" class="form-control-sm form-control"
                                                   placeholder="">

                                        </div>
                                    </div>


                                    <div class="position-relative form-group"><label for="salary_authorizer"
                                                                                     class="">Authorizer</label>
                                        <select name="salary_authorizer" id="salary_authorizer"
                                                class="form-control-sm form-control">
                                            <option value="1">MR. OMAR SARRIEDINE</option>
                                            <option value="2">MS. REEMA SARRIEDINE</option>
                                            <option value="3">MR. TALAAT SARRIEDINE</option>
                                        </select>

                                    </div>

                                    <button class="mt-1 btn btn-block btn-outline-success" name="salarySubmit"
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
    <script type="text/javascript" src="/js/autofill_employee_names_nol.js"></script>
    <script type="text/javascript" src="/js/autofill_employee_names_salary.js"></script>
    <script type="text/javascript" src="/js/coe.js"></script>

    <script> document.title = "HR - InDepth";</script>

<?php } else {
    header('Location: /no-access/index.html');
}

?>