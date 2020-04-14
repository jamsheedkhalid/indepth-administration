window.onload = loadStudentStatistics("student_statistics");


function loadStudentStatistics(div) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/parent/studentStatistics.php", false);
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
    httpStudent.open("GET", "/mysql/parent/initStudentTable.php", false);
    httpStudent.send();

}


window.onload = loadStudentReportCards("student_report_cards");


function loadStudentReportCards(table) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(table).innerHTML = this.responseText;
            fillStudentTerms();
        }
    };
    httpStudent.open("GET", "/mysql/parent/studentReportCards.php", false);
    httpStudent.send();

}

function fillStudentTerms( ){
    let selectTermClass = document.getElementsByClassName('student_term');
    for( let i=0; i<selectTermClass.length; i++){
        let selectTerm = selectTermClass[i];
        while (selectTerm.length > 0)
            selectTerm.remove(0);

        let httpTerm = new XMLHttpRequest();
        httpTerm.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };
        httpTerm.open("GET", "/mysql/parent/fillTerms.php" , false);
        httpTerm.send();
        delete termsArray[termsArray.length - 1];
        for (let i in termsArray) {
            selectTerm.add(new Option(termsArray[i]));
        }





    }



}