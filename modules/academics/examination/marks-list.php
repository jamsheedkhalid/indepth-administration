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

    <script>
        function students_datatable() {
            $('#marks_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Al Sanawbar School \n Marks List',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Al Sanawbar School \n Marks List',
                        className: 'btn btn-primary btn-sm'

                    },
                    {
                        extend: 'csv',
                        title: 'Al Sanawbar School \n Marks List',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'print',
                        title: '',
                        messageTop: ' <h4 align="center">Al Sanawbar School</h4>',
                        className: 'btn btn-primary btn-sm'
                    }

                ]
            });
            $('.dataTables_length').addClass('bs-select');
        }
    </script>

    </head>
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
                                <i class="pe-7s-graph3 icon-gradient bg-premium-dark"></i>
                            </div>
                            <div>MARKS LIST
                                <div class="page-title-subheading">Final Term Mark</div>
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm">
                                                <label for="grade">GRADE</label>
                                            </div>
                                            <div class="col-sm">
                                                <select id="grade" class="form-control"
                                                        data-role="select-dropdown" data-profile="minimal"
                                                        onchange="search();">
                                                    <option value="0">SELECT GRADE</option>
                                                    <option value="13">GR1 to GR 3</option>
                                                    <option value="46">GR4 to GR 6</option>
                                                    <option value="79">GR7 to GR 9</option>
                                                    <option value="1012">GR10 to GR 12</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                        <div class="form-group" id="process" style="display:none;" >
                                            <div class="progress" >
                                                <div id="process-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="">    Loading...   </div>
                                            </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title">FILTERS</h5>
                                            <div class="row" style="padding-left: 20px;">
                                                <div class="position-relative col-sm-3">
                                                    <label class="form-check-label"><input onchange="search();"
                                                                                           type="checkbox"
                                                                                           id="show_ar_names"
                                                                                           name="show_ar_names"
                                                                                           class="form-check-input"> Use
                                                        Arabic Names</label>
                                                </div>
                                                <div class="position-relative col-sm-3">
                                                    <label class="form-check-label"><input onchange="search();"
                                                                                           type="checkbox"
                                                                                           id="show_parent_name"
                                                                                           name="show_parent_name"
                                                                                           class="form-check-input">
                                                        Parent Name</label>
                                                </div>
                                                <div class="position-relative col-sm-3">
                                                    <label class="form-check-label"><input onchange="search();"
                                                                                           type="checkbox"
                                                                                           id="show_family_id"
                                                                                           name="show_family_id"
                                                                                           class="form-check-input">
                                                        Family ID</label>
                                                </div>
                                                <div class="position-relative col-sm-3">
                                                    <label class="form-check-label"><input onchange="search();"
                                                                                           type="checkbox"
                                                                                           id="show_contact"
                                                                                           name="show_contact"
                                                                                           class="form-check-input">
                                                        Contact Number</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                    result div -->
                                    <div class="row">
                                        <div class="col" style='padding-top: 20px'>
                                            <h5 class="card-title">marks list</h5>
                                            <div id="result_div">
                                                <div class="alert alert-primary fade show" role="alert">please use the
                                                    drop-downs to view the <strong>mark lists</strong>!
                                                </div>
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
    <script> document.getElementById('liExamination').classList.add("mm-active")</script>
    <script> document.getElementById('liMarksList').classList.add("mm-active")</script>
    <script type="text/javascript" src="/js/marks-list.js"></script>
    <script> document.title = "Report Center - InDepth";</script>

<?php } else {
    header('Location: /no-access/index.html');
}

?>