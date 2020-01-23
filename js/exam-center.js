window.onload = initGrades();

function initGrades() {
    var gradeArray = ["No Grades!"];
    var httpGrade = new XMLHttpRequest();
    httpGrade.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradeArray = str.split("\t");
        }
    };
    httpGrade.open("GET", "/indepth-administration/mysql/exam-center/initGrades.php", false);
    httpGrade.send();

    var select = document.getElementById('gradeSelect');
    delete gradeArray[gradeArray.length - 1];
    for (var i in gradeArray) {
        select.add(new Option(gradeArray[i]));
    }
}

function fillSections() {
    var selected_grade = document.getElementById('gradeSelect').options[document.getElementById('gradeSelect').selectedIndex].text;
    var sectionSelect = document.getElementById("sectionSelect");
    while (sectionSelect.length > 0)
        sectionSelect.remove(0);

    let httpSection = new XMLHttpRequest();
    httpSection.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            sectionArray = str.split("\t");
        }
    };
    httpSection.open("GET", "/indepth-administration/mysql/exam-center/fillSections.php?grade=" + selected_grade, false);
    httpSection.send();

    delete sectionArray[sectionArray.length - 1];
    for (var i in sectionArray) {
        sectionSelect.add(new Option(sectionArray[i]));
    }
}

function fillStudents() {
    var selected_grade = document.getElementById('gradeSelect').options[document.getElementById('gradeSelect').selectedIndex].text;
    var selected_section = document.getElementById('sectionSelect').options[document.getElementById('sectionSelect').selectedIndex].text;
    var studentSelect = document.getElementById("studentSelect");
    while (studentSelect.length > 0)
        studentSelect.remove(0);

    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            studentsArray = str.split("\t");
        }
    };
    httpStudent.open("GET", "/indepth-administration/mysql/exam-center/fillStudents.php?grade=" + selected_grade + "&section=" + selected_section, false);
    httpStudent.send();

    delete studentsArray[studentsArray.length - 1];
    for (var i in studentsArray) {
        studentSelect.add(new Option(studentsArray[i]));
    }
}

function fillTerms() {
    var selected_student = document.getElementById('studentSelect').options[document.getElementById('studentSelect').selectedIndex].text;
    var termSelect = document.getElementById("termSelect");

    while (termSelect.length > 0)
        termSelect.remove(0);

    let httpTerm = new XMLHttpRequest();
    httpTerm.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            termArray = str.split("\t");
        }
    };
    httpTerm.open("GET", "/indepth-administration/mysql/exam-center/fillTerms.php?student=" + selected_student, false);
    httpTerm.send();

    delete termArray[termArray.length - 1];
    for (var i in termArray) {
        termSelect.add(new Option(termArray[i]));
    }

}

function generateReportCard() {
    let student = document.getElementById("studentSelect").options[document.getElementById('studentSelect').selectedIndex].text;
    document.getElementById("reportCardModalStudentName").innerHTML = student;
    let grade = document.getElementById("gradeSelect").options[document.getElementById('gradeSelect').selectedIndex].text;
    document.getElementById("reportCardModalStudentGrade").innerHTML = grade;
    let section = document.getElementById("sectionSelect").options[document.getElementById('sectionSelect').selectedIndex].text;
    document.getElementById("reportCardModalStudentSection").innerHTML = section;
    let term = document.getElementById("termSelect").options[document.getElementById('termSelect').selectedIndex].text;
    document.getElementById("reportCardModalStudentTerm").innerHTML = term;


    var httpResult = new XMLHttpRequest();
    httpResult.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("reportCardModalResult").innerHTML = this.responseText;
        }
    };
    httpResult.open("GET", "/indepth-administration/mysql/exam-center/fillReportCardResults.php?student=" + student + "&grade=" + grade + "&section=" + section + "&term=" + term, false);
    httpResult.send();

}