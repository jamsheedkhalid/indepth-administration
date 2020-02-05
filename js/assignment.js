// noinspection JSValidateTypes
window.onload = loadStudents("studentListAssignment");
window.onload = filAssignments("studentAssignment1");


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

function filAssignments(div) {
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/assignment/fillAssignment.php", false);
    httpStudent.send();

}