function fillEmployeeDetails(div) {
    let employee = [];
    let details = [];
    let name = document.getElementById(div + 'name').value;
    let httpEmp = new XMLHttpRequest();
    httpEmp.onreadystatechange = function () {
        if (this.readyState === 4) {
            details = [this.responseText];

        }
    };

    httpEmp.open("GET", "/mysql/hr/certificate/coe_employeeDetails.php?emp=" + name, false);
    httpEmp.send();

 var obj = JSON.parse(details);
 alert(obj.Name);
}