function fillEmployeeDetails() {
    let id = document.getElementById('coe_id').value;
    document.getElementById('coe_joinDate').value = '';
    document.getElementById('coe_passport').value = '';
    document.getElementById('coe_nationality').value = '';
    document.getElementById('coe_jobTitle').value = '';
    document.getElementById('coe_name').value = '';
    document.getElementById('coe_id').value = '';

    let details;
    let httpEmployee = new XMLHttpRequest();
    httpEmployee.onreadystatechange = function () {
        if (this.readyState === 4) {
            let details = this.responseText;
            let obj = JSON.parse(details);
            document.getElementById('coe_joinDate').value = obj.joinDate;
            document.getElementById('coe_passport').value = obj.passport;
            document.getElementById('coe_nationality').value = obj.nationality;
            document.getElementById('coe_jobTitle').value = obj.job_title;
            document.getElementById('coe_name').value = obj.name;
            document.getElementById('coe_id').value = obj.employee_number;
            document.getElementById('coe_ms').value = obj.gender;

        }
    };


    httpEmployee.open("GET", "/mysql/hr/certificate/fillEmployeeDetails.php?id=" + id, false);
    httpEmployee.send();
}

function fillEmployeeDetailsNol() {
    let id = document.getElementById('nol_id').value;
    document.getElementById('nol_joinDate').value = '';
    document.getElementById('nol_passport').value = '';
    document.getElementById('nol_nationality').value = '';
    document.getElementById('nol_jobTitle').value = '';
    document.getElementById('nol_name').value = '';
    document.getElementById('nol_id').value = '';

    let details;
    let httpEmployee = new XMLHttpRequest();
    httpEmployee.onreadystatechange = function () {
        if (this.readyState === 4) {
            let details = this.responseText;
            let obj = JSON.parse(details);
            document.getElementById('nol_joinDate').value = obj.joinDate;
            document.getElementById('nol_passport').value = obj.passport;
            document.getElementById('nol_nationality').value = obj.nationality;
            document.getElementById('nol_jobTitle').value = obj.job_title;
            document.getElementById('nol_name').value = obj.name;
            document.getElementById('nol_id').value = obj.employee_number;
            document.getElementById('nol_ms').value = obj.gender;

        }
    };


    httpEmployee.open("GET", "/mysql/hr/certificate/fillEmployeeDetails.php?id=" + id, false);
    httpEmployee.send();
}

function fillEmployeeDetailsSalary() {
    let id = document.getElementById('salary_id').value;
    document.getElementById('salary_joinDate').value = '';
    document.getElementById('salary_passport').value = '';
    document.getElementById('salary_nationality').value = '';
    document.getElementById('salary_jobTitle').value = '';
    document.getElementById('salary_name').value = '';
    document.getElementById('salary_id').value = '';
    document.getElementById('salary_amount').value = '';

    let details;
    let httpEmployee = new XMLHttpRequest();
    httpEmployee.onreadystatechange = function () {
        if (this.readyState === 4) {
             details = this.responseText;
             obj = JSON.parse(details);
            document.getElementById('salary_joinDate').value = obj.joinDate;
            document.getElementById('salary_passport').value = obj.passport;
            document.getElementById('salary_nationality').value = obj.nationality;
            document.getElementById('salary_jobTitle').value = obj.job_title;
            document.getElementById('salary_name').value = obj.name;
            document.getElementById('salary_id').value = obj.employee_number;
            document.getElementById('salary_ms').value = obj.gender;
            document.getElementById('salary_amount').value = obj.salary;


        }
    };


    httpEmployee.open("GET", "/mysql/hr/certificate/fillEmployeeDetails.php?id=" + id, false);
    httpEmployee.send();
}

