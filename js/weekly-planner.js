// window.onload = loadStudents("studentList");


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

window.onload = loadWeeklyPlanner("weekly-planner-table", "curr");

function loadWeeklyPlanner(div, date_type) {
    loadStudents("studentList");
    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {

            document.getElementById(div).innerHTML = this.responseText;
            const table = document.querySelector('table');

            let headerCell = null;

            for (let row of table.rows) {
                const firstCell = row.cells[0];

                if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                    headerCell = firstCell;
                } else {
                    headerCell.rowSpan++;
                    firstCell.remove();
                }
            }
        }
    };
    httpStudent.open("GET", "/mysql/planner/weeklyPlanner.php?date_type=" + date_type, false);
    httpStudent.send();

}