<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
                        <span>
                            <button type="button"
                                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul  class="vertical-nav-menu">
                <li  class="app-sidebar__heading">Dashboards</li>

                <?php if($_SESSION['user_type'] === 'admin'){?>
                <li>
                    <a id="side_bar_home" href="/main.php">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                <?php } if($_SESSION['user_type'] === 'parent' || $_SESSION['user_type'] === 'admin'){?>


                <li>
                    <a id="home_parent" href="/parent-home.php">
                        <i class="metismenu-icon pe-7s-home"></i>
                       Home
                    </a>
                </li>
                <?php }?>
                <?php  if($_SESSION['user_type'] === 'teacher' || $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'parent' ){?>
                <li class="app-sidebar__heading ">ACADEMICS</li>
                <?php  if( $_SESSION['user_type'] === 'admin' ){?>
                <li id="liExamination">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-graph2"></i>
                        EXAMINATION
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a id="liExamination_ReportCard" href="/modules/academics/examination/term-reports.php">
                                <i class="metismenu-icon"></i>
                                Term Reports
                            </a>
                        </li>
                        <li>
                            <a id="liEvaluation_ReportCard" href="/modules/academics/examination/evaluation-reports.php">
                                <i class="metismenu-icon"></i>
                               Class Evaluation Reports
                            </a>
                        </li>
                    </ul>
                </li>
                <?php }} if($_SESSION['user_type'] === 'parent' || $_SESSION['user_type'] === 'admin'){?>
                <li id="liAssignment">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-note"></i>
                        ASSIGNMENTS
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>

                        <li>
                            <a href="/modules/academics/assignment/student_assignment.php">
                                <i class="metismenu-icon"></i>
                                My Assignments
                            </a>
                        </li>
                        <li>
                            <a id="liAssignmentPlanner" href="/modules/academics/planner/assignment_planner.php">
                                <i class="metismenu-icon "></i>
                                Assignments Planner
                            </a>
                        </li>

                    </ul>
                </li>
                <?php } if($_SESSION['user_type'] === 'teacher' || $_SESSION['user_type'] === 'admin'){?>
                    <li  id="liPlanner" >
                    <a href="#"  >
                        <i class="metismenu-icon pe-7s-date"></i>
                       WEEKLY PLANNER
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                        <ul>
                            <li >
                                <a id="liPlanner_edit"  href="/modules/academics/planner/student_planner.php">
                                    <i class="metismenu-icon pe-7s-date"></i>
                                    Edit
                                </a>
                            </li>
                            <li >
                                <a href="/modules/academics/planner/weekly_planner.php" target="_blank">
                                    <i class="metismenu-icon pe-7s-date"></i>
                                  View
                                </a>
                            </li>
                        </ul>
                </li>


                <?php } ?>
                <li  hidden >
                    <a  href="/modules/academics/planner/weekly_planner.php">
                        <i class="metismenu-icon pe-7s-date"></i>
                        WEEKLY PLANNER
                    </a>
                </li>
                <?php if ( $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'teacher'){ ?>

                <li id="liReportCenter">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-graph3"></i>
                        REPORT CENTER
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a id="liReportCenter_StudentAnalysis" href="/modules/academics/report-center/student-analysis.php">
                                <i class="metismenu-icon"></i>
                                Student Analysis
                            </a>
                        </li>
                    </ul>
                </li>



                <?php } if ( $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'finance'){
                    ?>
                <li  class="app-sidebar__heading">FINANCE</li>
                <li hidden  >
                    <a  hidden href="https://finance.alsanawabar.school/index.php">
                        <i class="metismenu-icon pe-7s-cash">
                        </i>ACCOUNTS
                    </a>
                </li>

                    <li >
                    <a  href="#" id="liTax">
                        <i class="metismenu-icon pe-7s-calculator">
                        </i>TAX
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>

                    </a>
                        <ul>
                            <li >
                                <a id="liTax_Invoice" href="/modules/finance/invoices/tax-invoice.php" >
                                    <i class="metismenu-icon"></i>
                                    Tax Invoices
                                </a>
                            </li>
                        </ul>
                </li>
                    <li >
                        <a  target="_blank" href="https://wps.alsanawbar.school/index.php">
                            <i class="metismenu-icon pe-7s-credit">
                            </i>WPS
                        </a>
                    </li>
                <?php } ?>
                <?php  if($_SESSION['user_type'] === 'hr' || $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'finance'){?>
                <li  id="" class="app-sidebar__heading" >HR</li>
                <li  id="liHR">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-ribbon"></i>
                        CERTIFICATES
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li >
                            <a id="liHR_Certificate" href="/modules/hr/certificate/employee-certificate.php" >
                                <i class="metismenu-icon"></i>
                                Employee Certificates
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
            </ul>

        </div>
    </div>
</div>
<div class="app-main__outer">
