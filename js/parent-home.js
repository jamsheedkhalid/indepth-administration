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