window.onload = initGrades();

function initGrades() {
    var items = "";
    $.getJSON("/mysql/planner/view-planner/initGrades.php", function (data) {
        $.each(data, function (index, item) {
            items += "<option value='" + item.course_id + "'>" + item.course_name + "</option>";
        });
        $("#grade").html(items);
        selectSection();
    });
}

function selectSection() {
    var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;

    let httpSection = new XMLHttpRequest();
    httpSection.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('section').innerHTML = this.responseText;

        }
    };
    httpSection.open("GET", "/mysql/planner/view-planner/fillSections.php?grade=" + selected_grade, false);
    httpSection.send();
    loadWeeklyPlanner("weekly-planner", "curr");


}

function loadWeeklyPlanner(div, date_type) {
    var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    var selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;


    let httpPlanner = new XMLHttpRequest();
    httpPlanner.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('weekly-planner').innerHTML = this.responseText;
            // const table = document.querySelector('table');
            //
            // let headerCell = null;
            //
            // for (let row of table.rows) {
            //     const firstCell = row.cells[0];
            //
            //     if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
            //         headerCell = firstCell;
            //     } else {
            //         headerCell.rowSpan++;
            //         firstCell.remove();
            //     }
            // }

            // if(date_type === 'curr') {
            //             //     var d = new Date();
            //             //     var n = d.getDay();
            //             //     var elements = document.getElementsByClassName("table-weekly-planner");
            //             //     for(var i=0; i<elements.length; i++) {
            //             //         let x = elements[i]. getElementsByTagName("col");
            //             //        x[n+1].style.backgroundColor = "#f0c0c0";
            //             //     }
            //             //  }

        }
    };
    httpPlanner.open("GET", "/mysql/planner/view-planner/weekly-planner.php?date_type=" + date_type + "&section=" + selected_section + "&grade=" + selected_grade, false);
    httpPlanner.send();
    highlight_row();


}


function highlight_row() {

    var table = document.getElementById('weekly-planner');
    var cells = table.getElementsByTagName('td');
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].text;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].text;


    for (var i = 0; i < cells.length; i++) {
        // Take each cell
        var cell = cells[i];
        // do something on onclick event for cell
        cell.onclick = function () {
            // Get the row id where the cell exists
            var rowId = this.parentNode.rowIndex;
            var cellId = this.cellIndex;


            var rowsNotSelected = table.getElementsByTagName('tr');
            for (var row = 0; row < rowsNotSelected.length; row++) {
                rowsNotSelected[row].style.backgroundColor = "";
                rowsNotSelected[row].style.color = "";
                rowsNotSelected[row].classList.remove('selected');
            }
            var rowSelected = table.getElementsByTagName('tr')[rowId];
            var rowSubject = table.getElementsByTagName('tr')[0];
            // rowSelected.style.backgroundColor = "#D5ECED";
            rowSelected.style.backgroundColor = "#D5ECED";
            rowSelected.style.color = "black";
            rowSelected.className += " selected";
            subject = rowSubject.cells[cellId].innerHTML;
            date = new Date(rowSelected.cells[0].title).toDateString();



            loadSubjects();
            loadStudents('task-select');

        }
    }

} //end of function


function loadStudents(id) {

    var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    var selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;

    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(id).innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/planner/task-students.php?section=" + selected_section + "&grade=" + selected_grade, false);
    httpStudent.send();

    $('.select_all').click(function () {
        $('#task-select option').prop('selected', true);
    });

}

// function loadSubjects() {
//
//     var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
//     var selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;
//
//     let httpStudent = new XMLHttpRequest();
//     httpStudent.onreadystatechange = function () {
//         if (this.readyState === 4) {
//             document.getElementById('task-subject').innerHTML = this.responseText;
//         }
//     };
//     httpStudent.open("GET", "/mysql/planner/fill-subjects.php?section=" + selected_section + "&grade=" + selected_grade, false);
//     httpStudent.send();
//
//
// }



function viewTaskDetails(id) {
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].text;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].text;
    let viewModal = document.getElementById('viewModalBody');
    let httpTask = new XMLHttpRequest();
    httpTask.onreadystatechange = function () {
        if (this.readyState === 4) {
            viewModal.innerHTML = this.responseText;
            $('#viewWeeklyModal').modal('show');

        }
    };
    httpTask.open("GET", "/mysql/planner/view-planner/view-task.php?id=" + id + "&grade=" + selected_grade + "&section=" + selected_section, false);
    httpTask.send();


}









