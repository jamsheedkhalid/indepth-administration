function fillEmployeeDetails() {
    let id = document.getElementById('coe_id').value;
    document.getElementById('coe_joinDate').value = '';
    document.getElementById('coe_passport').value ='';
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


