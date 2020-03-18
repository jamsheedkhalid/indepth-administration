<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/include/loginFunction.php');
checkLoggedIn();
if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'finance') {
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
                        <div class="page-title-actions">
                            <div>
                                <form class="row" method="post" target="_blank"
                                >
                                    <div class="col">
                                        <input type="date" name="start_date" id="start_date"
                                               value="<?php echo date('Y-m-01') ?>">
                                        <!--                                    <input type="date"  name="start_date" id="start_date" value="2019-01-01">-->
                                    </div>
                                    <b class="col">To</b>
                                    <div class="col">
                                        <input type="date" name="end_date" id="end_date"
                                               value="<?php echo date('Y-m-t') ?>">
                                        <!--                                    <input type="date"   name="end_date" id="end_date" value="2019-01-31">-->
                                    </div>
                                    <div class="col-md">
                                        <button type="button" onclick="loadInvoices('invoice-table');"
                                                title="Search Invoices"
                                                class="btn-shadow btn btn-outline-primary">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-search fa-w-20"></i>
                                        </span>

                                        </button>
                                    </div>
                                    <div class="col-md ">
                                        <div class="d-inline-block dropdown">
                                            <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"
                                                    class="btn-shadow  btn btn-dark">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fas fa-print fa-w-20"></i>
                                                    </span>
                                            </button>

                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                 class="dropdown-menu dropdown-menu-right">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);"
                                                           class="nav-link">
                                                        <span>
                                                        <button class="btn btn-sm "
                                                                formaction="/mysql/tax-invoice/print-invoiceAll.php"
                                                                title="Print Invoices"> Invoices</button>
                                                        </span>
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);"
                                                           class="nav-link">
                                                        <span>
                                                        <button class="btn btn-sm "
                                                                formaction="/mysql/tax-invoice/print-taxReport.php"
                                                                title="Print Report "> Fee Collection Report</button>
                                                         </span>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div id='invoice-table'>
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
    <script type="text/javascript" src="/js/dataTable.js"></script>
    <script> document.title = "Tax Invoice - InDepth";</script>
<?php } else {
    header('Location: /no-access/index.html');
}

?>