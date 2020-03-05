loadInvoices("invoice-table");

function loadInvoices(div) {
    let start_date = document.getElementById('start_date').value;
    let end_date = document.getElementById('end_date').value;
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/tax-invoice/tax-invoice.php?start_date=" + start_date + '&end_date=' + end_date, false);
    httpStudent.send();
    initDataTable()

}


 function initDataTable() {
    // Setup - add a text input to each footer cell

    $('#invoiceTable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
     } );

    // DataTable
    var table = $('#invoiceTable').DataTable();

    // Apply the search
    table.columns().every( function () {
        var that = this;
        $( '#invoiceTable tfoot input', this.footer() ).on( 'keyup change clear', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    }
    );
}
