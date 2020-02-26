loadStudents("invoice-table");

function loadStudents(div) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/tax-invoice/tax-invoice.php", false);
    httpStudent.send();

}

