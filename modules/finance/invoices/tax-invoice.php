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
                                <form   class="row" method="post" target="_blank" action="/mysql/tax-invoice/print-invoiceAll.php">
                                <div class="col">
                                    <input type="date" onchange="loadInvoices('invoice-table');" name="start_date" id="start_date" value="<?php echo date('Y-m-01') ?>">
                                </div>
                                <b class="col">To</b>
                                <div class="col">
                                    <input type="date"  onchange="loadInvoices('invoice-table');" name="end_date" id="end_date" value="<?php echo date('Y-m-t') ?>">
                                </div>
                                <div class="col">
                                    <button type="submit" title="Print Invoices"
                                            class="btn-shadow btn btn-dark">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                      <i class="fas fa-print fa-w-20"></i>
                                        </span>

                                    </button>
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