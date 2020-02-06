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
            <ul class="vertical-nav-menu">
                <li  hidden class="app-sidebar__heading">Dashboards</li>
                <li  hidden >
                    <a id="side_bar_home" href="/main.php">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                <li hidden>
                    <a id="home_parent" href="/parent-home.php">
                        <i class="metismenu-icon pe-7s-home"></i>
                      Parent Home
                    </a>
                </li>
                <li class="app-sidebar__heading ">ACADEMICS</li>
                <li id="liExamination">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-graph2"></i>
                        EXAMINATION
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a id="liExamination_ReportCard" href="/modules/academics/examination/generate-reports.php">
                                <i class="metismenu-icon"></i>
                                Evaluation Reports
                            </a>
                        </li>
                    </ul>
                </li>
                <li hidden id="liAssignment">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-note"></i>
                        ASSIGNMENTS
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li hidden>
                            <a id="liAssignment_studentAssignment" href="/modules/academics/assignment/student_assignment.php">
                                <i class="metismenu-icon"></i>
                                My Assignments
                            </a>
                        </li>
                    </ul>
                </li>

                <li hidden class="app-sidebar__heading">FINANCE</li>
                <li hidden >
                    <a href="https://finance.alsanawabar.school/index.php">
                        <i class="metismenu-icon pe-7s-cash">
                        </i>Accounts
                    </a>
                </li>
                <li hidden >
                    <a href="https://wps.alsanawbar.school/index.php">
                        <i class="metismenu-icon pe-7s-credit">
                        </i>WPS
                    </a>
                </li>
                <li hidden class="app-sidebar__heading" >HR</li>
                <li>
                    <a hidden href="https://certs.alsanawbar.school/index.php">
                        <i class="metismenu-icon pe-7s-ribbon">
                        </i>Certificates
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
<div class="app-main__outer">
