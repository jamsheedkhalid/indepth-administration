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

window.onload = loadAssignmentPlanner("assignment-planner-table", "curr");

function loadAssignmentPlanner(div, date_type) {
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

            if(date_type === 'curr') {
                var d = new Date();
                var n = d.getDay();
                var elements = document.getElementsByClassName("table-assignments-planner");
                for(var i=0; i<elements.length; i++) {
                  let x = elements[i]. getElementsByTagName("col");
                    x[n].style.backgroundColor = "#f0c0c0";
                }

            }

        }
    };
    httpStudent.open("GET", "/mysql/planner/assignments-planner.php?date_type=" + date_type, false);
    httpStudent.send();

}