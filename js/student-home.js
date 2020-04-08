window.onload = loadStudentStatistics("student_statistics");


function loadStudentStatistics(div) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
            fillStudentTerms();
        }
    };
    httpStudent.open("GET", "/mysql/student/studentStatistics.php", false);
    httpStudent.send();

}

window.onload = loadStudentInfoTable("parent_studentsInfo_table");
function loadStudentInfoTable(table) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(table).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/student/initStudentTable.php", false);
    httpStudent.send();

}


function fillStudentTerms( ){

   let selectTerm = document.getElementById('student_term');

    while (selectTerm.length > 0)
        selectTerm.remove(0);

    let httpTerm = new XMLHttpRequest();
    httpTerm.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            termsArray = str.split("\t");
        }
    };
    httpTerm.open("GET", "/mysql/student/fillTerms.php" , false);
    httpTerm.send();
    delete termsArray[termsArray.length - 1];
    for (let i in termsArray) {
        selectTerm.add(new Option(termsArray[i]));
    }

    $('#student_term').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering : true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        nSelectedText: ' terms selected!',
        nonSelectedText: 'Select Term',
        dropRight: true
    });

}

