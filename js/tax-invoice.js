window.onload = loadInvoices("invoice-table");

function loadInvoices(div) {
    let start_date = document.getElementById('start_date').value;
    let end_date = document.getElementById('end_date').value;
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
            initDataTable();
        }
    };
    httpStudent.open("GET" , "/mysql/tax-invoice/tax-invoice.php?start_date=" + start_date + '&end_date=' + end_date, false);
    httpStudent.send();

}


 function initDataTable() {
    // DataTable
   $('#invoiceTable').DataTable();
}
