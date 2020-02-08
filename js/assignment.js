// noinspection JSValidateTypes
window.onload = loadStudents("studentListAssignment");
window.onload = fillAssignments("studentAssignment1","all","");


function loadStudents(div) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/assignment/initStudents.php", false);
    httpStudent.send();

}

function fillAssignments(div,type,date) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/assignment/fillAssignment.php?date=" + date + "&type=" + type, false);
    httpStudent.send();

}