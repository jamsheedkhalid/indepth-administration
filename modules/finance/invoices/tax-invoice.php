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
                                <i class="pe-7s-calculator icon-gradient bg-premium-dark">
                                </i>
                            </div>
                            <div>TAX INVOICE
                                <div class="page-title-subheading">Generate & Print tax invoices.
                                </div>
                            </div>
                        </div>
<!--                        <div class="page-title-actions">-->
<!--                            <div class="d-inline-block dropdown">-->
<!--                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
<!--                                        class="btn-shadow dropdown-toggle btn btn-info">-->
<!--                                                                        <span class="btn-icon-wrapper pr-2 opacity-7">-->
<!--                                                                            <i class="fas fa-filter fa-w-20"></i>-->
<!--                                                                        </span>-->
<!--                                    Filters-->
<!--                                </button>-->
<!--                                <div tabindex="-1" role="menu" aria-hidden="true"-->
<!--                                     class="dropdown-menu dropdown-menu-right">-->
<!--                                    <ul class="nav flex-column">-->
<!--                                        <li class="nav-item">-->
<!---->
<!--                                            <a href="javascript:void(0);" class="nav-link">-->
<!--                                                <i class="nav-link-icon lnr-picture"></i>-->
<!--                                                <span>-->
<!--                                                                                         <label for="assignment_date"> Select Due Date</label><input-->
<!--                                                            type="date" id="assignment_date"-->
<!--                                                            placeholder="Select Due Date "-->
<!--                                                            name="assignment_date"-->
<!--                                                            onchange="fillAssignments('studentAssignment1','due',this.value);">-->
<!--                                                                                    </span>-->
<!--                                            </a>-->
<!---->
<!---->
<!--                                        </li>-->
<!--                                        <li class="nav-item">-->
<!---->
<!--                                            <a href="javascript:void(0);" class="nav-link">-->
<!--                                                <i class="nav-link-icon lnr-picture"></i>-->
<!--                                                <span>-->
<!--                                                                                         <label for="assignment_date"> Select Post Date</label><input-->
<!--                                                            type="date" id="assignment_date"-->
<!--                                                            placeholder="Select Post Date "-->
<!--                                                            name="assignment_date"-->
<!--                                                            onchange="fillAssignments('studentAssignment1','post',this.value);">-->
<!--                                                                                    </span>-->
<!--                                            </a>-->
<!---->
<!---->
<!--                                        </li>-->
<!--                                        <li class="nav-item">-->
<!--                                            <a href="javascript:void(0);"-->
<!--                                               onclick="fillAssignments('studentAssignment1','today',this.value);"-->
<!--                                               class="nav-link">-->
<!--                                                <i class="nav-link-icon lnr-book"></i>-->
<!--                                                <span>-->
<!--                                                                                        Today's Assignments-->
<!--                                                                                    </span>-->
<!--                                            </a>-->
<!--                                        </li>-->
<!--                                        <li class="nav-item">-->
<!--                                            <a href="javascript:void(0);"-->
<!--                                               onclick="fillAssignments('studentAssignment1','tomarrow',this.value);"-->
<!--                                               class="nav-link">-->
<!--                                                <i class="nav-link-icon lnr-picture"></i>-->
<!--                                                <span>-->
<!--                                                                                        Tomarrow's Assignment-->
<!--                                                                                    </span>-->
<!--                                            </a>-->
<!--                                        </li>-->
<!--                                        <li class="nav-item">-->
<!--                                            <a href="javascript:void(0);"-->
<!--                                               onclick="fillAssignments('studentAssignment1','yesterday',this.value);"-->
<!--                                               class="nav-link">-->
<!--                                                <i class="nav-link-icon lnr-picture"></i>-->
<!--                                                <span>-->
<!--                                                                                       Yesterday's Assignment-->
<!--                                                                                    </span>-->
<!--                                            </a>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>


                <div class="tab-content">
                    <div class=" tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                        <div class="col-lg-11 ">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div id='invoice-table'  class="table-responsive" >
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


        </div>
    </div>
    </body>


    </html>

    <script type="text/javascript" src="/assets/scripts/main.js"></script>
    <script> document.getElementById('liTax').classList.add("mm-active")</script>
    <script> document.getElementById('liTax_Invoice').classList.add("mm-active")</script>
    <script type="text/javascript" src="/js/tax-invoice.js"></script>


    <script> document.title = "Tax Invoice - InDepth";</script>
<?php } else {
    header('Location: /no-access/index.html');
}

?>