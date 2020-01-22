<!doctype html>
<html lang="en">
<?php
include($_SERVER['DOCUMENT_ROOT'] . 'indepth-administration/head.php');
?>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . 'indepth-administration/app-header.php');
    include($_SERVER['DOCUMENT_ROOT'] . 'indepth-administration/theme-setting.php');
    ?>
    <div class="app-main">
        <?php
        include($_SERVER['DOCUMENT_ROOT'] . 'indepth-administration/side-bar.php');
        ?>
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-graph2 icon-gradient bg-premium-dark">
                            </i>
                        </div>
                        <div>STUDENT EVALUATION REPORTS
                            <div class="page-title-subheading">Generate and print evaluation report cards for students
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions">
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
                <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div>
                                        <div class="row">
                                        <div class="position-relative form-group col-sm"><label for="gradeSelect" >Grade</label>
                                            <select onchange="fillSections();fillStudents();fillTerms();" name="gradeSelect"
                                                    id="gradeSelect" class="form-control-sm form-control">
                                                <option disabled selected>Select Grade</option>
                                            </select></div>
                                        <div class="position-relative form-group col-sm"><label for="sectionSelect" >Section</label>
                                            <select onchange="fillStudents();fillTerms();" name="sectionSelect"
                                                    id="sectionSelect" class="form-control-sm form-control">
                                                <option disabled selected>Select Section</option>
                                            </select></div>
                                        </div>
                                        <div class="position-relative form-group"><label for="studentSelect" class="">Student</label>
                                            <select onchange="fillTerms();" name="studentSelect" id="studentSelect"
                                                    class="form-control-sm form-control">
                                                <option>Select Student</option>
                                            </select></div>
                                        <div class="position-relative form-group"><label for="termSelect"
                                                                                         class="">Term</label>
                                            <select name="termSelect" id="termSelect"
                                                    class="form-control-sm form-control">
                                                <option>Select Term</option>
                                            </select></div>
                                        <button class="mt-1 btn btn-primary" onclick="generateReportCard();"
                                                data-toggle="modal" data-target="#reportCardModal">Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Groups</h5>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">@</span>
                                            </div>
                                            <input placeholder="username" type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><input
                                                            aria-label="Checkbox for following text input"
                                                            type="checkbox" class=""></span></div>
                                            <input placeholder="Check it out" type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group"><input placeholder="username" type="text"
                                                                        class="form-control">
                                            <div class="input-group-append"><span
                                                        class="input-group-text">@example.com</span></div>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                        class="input-group-text">$</span><span class="input-group-text">$</span>
                                            </div>
                                            <input placeholder="Dolla dolla billz yo!" type="text" class="form-control">
                                            <div class="input-group-append"><span class="input-group-text">$</span><span
                                                        class="input-group-text">$</span></div>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span>
                                            </div>
                                            <input placeholder="Amount" step="1" type="number" class="form-control">
                                            <div class="input-group-append"><span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Group Button Dropdown</h5>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" class="dropdown-toggle btn btn-secondary">
                                                Button Dropdown
                                            </button>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu"><h6
                                                        tabindex="-1" class="dropdown-header">Header</h6>
                                                <button type="button" disabled="" tabindex="-1"
                                                        class="disabled dropdown-item">Action
                                                </button>
                                                <button type="button" tabindex="0" class="dropdown-item">Another
                                                    Action
                                                </button>
                                                <div tabindex="-1" class="dropdown-divider"></div>
                                                <button type="button" tabindex="0" class="dropdown-item">Another
                                                    Action
                                                </button>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"></div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Group Button Shorthand</h5>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-secondary">To the Left!</button>
                                            </div>
                                            <input type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group"><input type="text" class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary">To the Right!</button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-danger">To the Left!</button>
                                            </div>
                                            <input placeholder="and..." type="text" class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-success">To the Right!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Group Sizing</h5>
                                    <div>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend"><span class="input-group-text">@lg</span>
                                            </div>
                                            <input type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                        class="input-group-text">@normal</span></div>
                                            <input type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">@sm</span>
                                            </div>
                                            <input type="text" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Group Addon</h5>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                        class="input-group-text">To the Left!</span></div>
                                            <input type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group"><input type="text" class="form-control">
                                            <div class="input-group-append"><span
                                                        class="input-group-text">To the Right!</span></div>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                        class="input-group-text">To the Left!</span></div>
                                            <input placeholder="and..." type="text" class="form-control">
                                            <div class="input-group-append"><span
                                                        class="input-group-text">To the Right!</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Input Group Button</h5>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-secondary">I'm a button</button>
                                            </div>
                                            <input type="text" class="form-control"></div>
                                        <br>
                                        <div class="input-group"><input type="text" class="form-control">
                                            <div class="input-group-append">
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" class="dropdown-toggle btn btn-secondary">
                                                    Button Dropdown
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                                    <button type="button" disabled="" tabindex="-1"
                                                            class="disabled dropdown-item">Action
                                                    </button>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action
                                                    </button>
                                                    <div tabindex="-1" class="dropdown-divider"></div>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary">Split Button</button>
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"
                                                        class="dropdown-toggle dropdown-toggle-split btn btn-outline-secondary"><span
                                                            class="sr-only">Toggle Dropdown</span></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                                    <button type="button" disabled="" tabindex="-1"
                                                            class="disabled dropdown-item">Action
                                                    </button>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action
                                                    </button>
                                                    <div tabindex="-1" class="dropdown-divider"></div>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action
                                                    </button>
                                                </div>
                                            </div>
                                            <input placeholder="and..." type="text" class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary">I'm a button</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                    <form class="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5 class="card-title">Checkboxes</h5>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-checkbox custom-control"><input type="checkbox"
                                                                                                   id="exampleCustomCheckbox"
                                                                                                   class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomCheckbox">Check
                                                        this
                                                        custom checkbox</label></div>
                                                <div class="custom-checkbox custom-control"><input type="checkbox"
                                                                                                   id="exampleCustomCheckbox2"
                                                                                                   class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomCheckbox2">Or
                                                        this
                                                        one</label></div>
                                                <div class="custom-checkbox custom-control"><input type="checkbox"
                                                                                                   id="exampleCustomCheckbox3"
                                                                                                   disabled=""
                                                                                                   class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomCheckbox3">But
                                                        not this disabled one</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5 class="card-title">Inline</h5>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-checkbox custom-control custom-control-inline"><input
                                                            type="checkbox" id="exampleCustomInline"
                                                            class="custom-control-input"><label
                                                            class="custom-control-label"
                                                            for="exampleCustomInline">An inline custom
                                                        input</label></div>
                                                <div class="custom-checkbox custom-control custom-control-inline"><input
                                                            type="checkbox" id="exampleCustomInline2"
                                                            class="custom-control-input"><label
                                                            class="custom-control-label"
                                                            for="exampleCustomInline2">and another one</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5 class="card-title">Radios</h5>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-radio custom-control"><input type="radio"
                                                                                                id="exampleCustomRadio"
                                                                                                name="customRadio"
                                                                                                class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomRadio">Select
                                                        this custom radio</label></div>
                                                <div class="custom-radio custom-control"><input type="radio"
                                                                                                id="exampleCustomRadio2"
                                                                                                name="customRadio"
                                                                                                class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomRadio2">Or
                                                        this one</label></div>
                                                <div class="custom-radio custom-control"><input type="radio"
                                                                                                id="exampleCustomRadio3"
                                                                                                disabled=""
                                                                                                class="custom-control-input"><label
                                                            class="custom-control-label" for="exampleCustomRadio3">But
                                                        not this
                                                        disabled one</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-card mb-3 card">
                                    <div class="card-body"><h5 class="card-title">Form Select</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label
                                                            for="exampleCustomSelect" class="">Custom
                                                        Select</label><select type="select" id="exampleCustomSelect"
                                                                              name="customSelect" class="custom-select">
                                                        <option value="">Select</option>
                                                        <option>Value 1</option>
                                                        <option>Value 2</option>
                                                        <option>Value 3</option>
                                                        <option>Value 4</option>
                                                        <option>Value 5</option>
                                                    </select></div>
                                                <div class="position-relative form-group"><label
                                                            for="exampleCustomMutlipleSelect" class="">Custom Multiple
                                                        Select</label><select multiple="" type="select"
                                                                              id="exampleCustomMutlipleSelect"
                                                                              name="customSelect" class="custom-select">
                                                        <option value="">Select</option>
                                                        <option>Value 1</option>
                                                        <option>Value 2</option>
                                                        <option>Value 3</option>
                                                        <option>Value 4</option>
                                                        <option>Value 5</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label
                                                            for="exampleCustomSelectDisabled" class="">Custom Select
                                                        Disabled</label><select type="select"
                                                                                id="exampleCustomSelectDisabled"
                                                                                name="customSelect"
                                                                                disabled="" class="custom-select">
                                                        <option value="">Select</option>
                                                        <option>Value 1</option>
                                                        <option>Value 2</option>
                                                        <option>Value 3</option>
                                                        <option>Value 4</option>
                                                        <option>Value 5</option>
                                                    </select></div>
                                                <div class="position-relative form-group"><label
                                                            for="exampleCustomMutlipleSelectDisabled" class="">Custom
                                                        Multiple Select Disabled</label><select multiple=""
                                                                                                type="select"
                                                                                                id="exampleCustomMutlipleSelectDisabled"
                                                                                                name="customSelect"
                                                                                                disabled=""
                                                                                                class="custom-select">
                                                        <option value="">Select</option>
                                                        <option>Value 1</option>
                                                        <option>Value 2</option>
                                                        <option>Value 3</option>
                                                        <option>Value 4</option>
                                                        <option>Value 5</option>
                                                    </select></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php
        include($_SERVER['DOCUMENT_ROOT'] . 'indepth-administration/footer-bar.php');
        ?>

        <!--        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>-->

    </div>
</div>
<script type="text/javascript" src="/indepth-administration/assets/scripts/main.js"></script>
<script> document.getElementById('li_reports').classList.add("mm-active")</script>
<script> document.getElementById('side_bar_report_card').classList.add("mm-active")</script>
<script type="text/javascript" src="/indepth-administration/js/exam-center.js"></script>

<script> document.title = "Reports - InDepth";</script>
</body>

<!--                    report card modal               -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="reportCardModal"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportCardModalTitle">Student Evaluation Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reportCardModalBody">
                <div class="text-center">
                    <img alt="Al Sanawbar School" src="/indepth-administration/assets/images/sanawbar-logo.jpeg"
                         width="80" height="80"/>
                    <h4 class="pt-lg-2"><strong>AL SANAWBAR SCHOOL</strong></h4>
                    <h6 class="pt-lg-1"><strong>AL AIN - U.A.E</strong></h6>
                    <h6 class="pt-lg-1">
                        <small>STUDENT EVALUATION REPORT</small>
                    </h6>
                    <hr>
                </div>
                <div class="text-center">
                    <h6 class="pt-lg-2"><strong>Academic Year: 2019-2020</strong></h6>
                    <h6 class="pt-lg-1"><strong id="reportCardModalStudentTerm">SECOND TERM</strong></h6>
                    <hr class="header-line-thick" width="200"/>
                </div>
                <div class="row">
                    <div class="col-sm-3 text-right">
                        <p> Student's Name :<br>
                            Grade :<br>
                            Section :</p>
                    </div>
                    <div class="col-sm text-left">
                        <div id="reportCardModalStudentName">Select student name</div>
                        <div id="reportCardModalStudentGrade">Select grade</div>
                        <div id="reportCardModalStudentSection">Select section</div>
                    </div>
                </div>
                <div id="reportCardModalResult" ></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>
<!--                    end report card modal           -->

</html>