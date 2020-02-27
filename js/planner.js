window.onload = initGrades();

function initGrades() {
    var items = "";
    $.getJSON("/mysql/planner/initGrades.php", function (data) {
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
    httpSection.open("GET", "/mysql/planner/fillSections.php?grade=" + selected_grade, false);
    httpSection.send();
    loadStudentPlanner("student-planner", "curr");


}

function loadStudentPlanner(div, date_type) {
    var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    var selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;


    let httpPlanner = new XMLHttpRequest();
    httpPlanner.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById(div).innerHTML = this.responseText;
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
    httpPlanner.open("GET", "/mysql/planner/students-planner.php?date_type=" + date_type + "&section=" + selected_section + "&grade=" + selected_grade, false);
    httpPlanner.send();
    highlight_row();


}


function highlight_row() {

    var table = document.getElementById('student-planner');
    var cells = table.getElementsByTagName('td');
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].text;
    let selected_grade_id = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
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
            let subject = rowSubject.cells[cellId].innerHTML;
            let date = new Date(rowSelected.cells[0].title).toDateString();

            let sub_id = rowSubject.cells[cellId].id;

            let httpTask = new XMLHttpRequest();
            httpTask.onreadystatechange = function () {
                if (this.readyState === 4) {
                    let is_teaching = this.responseText;
                    // alert(is_teaching);
                    if(is_teaching == 1){
                        msg = ' <label>Day: ' + date + '</label>   ';
                        msg += ' <div class="row"><div class="col"><b><label> Grade: ' + selected_grade + ' ' + '</label> </b></div> ';
                        msg += '<div class="col"> <label><b>Sub: ' + subject + '</b> </label></div></div>   ';
                        // msg += '<select id="task-subject" class="form-control-sm form-control"><option selected disabled>Select Subject</option></select><br>';
                        msg += '<input id="subject_id" type="hidden" value="' + rowSubject.cells[cellId].id + '" />';
                        msg += '<input id="date" type="hidden" value="' + rowSelected.cells[0].title + '" />';
                        msg += '<input id="task_title" type="text" required="required" autocapitalize="on" placeholder="Title" class="form-control-sm form-control" /><br>';
                        msg += '<textarea id="task_content" type="text" maxlength="500" style="height: 100px" placeholder="Description (Max 500 words)" class="form-control-sm form-control" ></textarea><br>';
                        msg += '<label for="task-select" hidden >Select Student</label><select hidden id="task-select" name="task-select" multiple="multiple"  size = "5" class="form-control-sm form-control"><option>Select Students</option></select>';
                        // msg += '\n Sub: ' + rowSelected.cells[1].innerHTML;
                        // msg += '\n Cell value: ' + this.innerHTML;
//            alert(msg);

                        document.getElementById('modalBody').innerHTML = msg;
                        loadSubjects();
                        loadStudents('task-select');
                        // $("#weeklyModal .modal-body").innerHTML = msg;
                        $('#weeklyModal').modal('show')
                    }
                    else{
                        alert("Subject is not assigned! Please select your subjects only");
                    }
                }
            };
            httpTask.open("GET", "/mysql/planner/subject-privilege.php?id=" + sub_id + '&grade=' + selected_grade_id , false);
            httpTask.send();


;
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

function loadSubjects() {

    var selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    var selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;

    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('task-subject').innerHTML = this.responseText;
        }
    };
    httpStudent.open("GET", "/mysql/planner/fill-subjects.php?section=" + selected_section + "&grade=" + selected_grade, false);
    httpStudent.send();


}

function saveTask() {
    let selected_students = $('#task-select').val();
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;
    let subject = document.getElementById('subject_id').value;
    let title = document.getElementById('task_title').value;
    let content = document.getElementById('task_content').value;
    let date = document.getElementById('date').value;


    let httpTask = new XMLHttpRequest();
    httpTask.onreadystatechange = function () {
        if (this.readyState === 4) {
            // document.getElementById('student-planner').innerHTML = this.responseText;
        }
    };
    httpTask.open("GET", "/mysql/planner/save-task.php?section=" + selected_section + "&grade=" + selected_grade + "&selected_students=" + selected_students + "&subject=" + subject + "&title=" + title + "&content=" + content + "&date=" + date, false);
    httpTask.send();
    $('#weeklyModal').modal('hide');

    loadStudentPlanner("student-planner", "curr");
}

function viewTaskDetails(id) {
    $('#weeklyModal').modal('hide');
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
    httpTask.open("GET", "/mysql/planner/view-task.php?id=" + id + "&grade=" + selected_grade + "&section=" + selected_section, false);
    httpTask.send();


}

function delTask(id) {
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;

    let conf = confirm("Are you sure you want to delete?");
    if (conf === true) {

        let httpTask = new XMLHttpRequest();
        httpTask.onreadystatechange = function () {
            if (this.readyState === 4) {
                let response = this.responseText;

                if(response == 0){
                    alert("Sorry, you are not allowed to delete this task!");
                }
                else {
                    loadStudentPlanner("student-planner", "curr");
                    // document.getElementById('student-planner').innerHTML=this.responseText;
                }
            }
        };
        httpTask.open("GET", "/mysql/planner/del-task.php?id=" + id + "&grade=" + selected_grade , false);
        httpTask.send();
    } else {
        loadStudentPlanner("student-planner", "curr");

    }
    $('#viewWeeklyModal').modal('hide');
}

function editTask(id) {


    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].text;
    let selected_grade_id = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].text;
    let editModal = document.getElementById('editModalBody');

    let httpTask = new XMLHttpRequest();
    httpTask.onreadystatechange = function () {
        if (this.readyState === 4) {
          let response = this.responseText;
         if(response == 0){
             alert("Sorry, you are not allowed to edit this task!");
         }
         else {
             editModal.innerHTML = response;
             $('#viewWeeklyModal').modal('hide');
             // loadStudents('task-select-edit');
             $('#editWeeklyModal').modal('show');
             loadStudentPlanner("student-planner", "curr");
         }



        }
    };
    httpTask.open("GET", "/mysql/planner/edit-task.php?id=" + id + "&grade=" + selected_grade + "&section=" + selected_section + "&gradeid=" + selected_grade_id, false);
    httpTask.send();

    $('.select_all').click(function () {
        $('#task-select-edit option').prop('selected', true);
    });


}

function updateTask(id) {
    let conf = confirm("Are you sure you want to edit?");
    if (conf === true) {
        let selected_students = $('#task-select-edit').val();
        let title = document.getElementById('edit_title').value;
        let content = document.getElementById('edit_content').value;


        let httpTask = new XMLHttpRequest();
        httpTask.onreadystatechange = function () {
            if (this.readyState === 4) {
                // document.getElementById('student-planner').innerHTML = this.responseText;
                loadStudentPlanner("student-planner", "curr");
            }
        };
        httpTask.open("GET", "/mysql/planner/update-task.php?selected_students=" + selected_students +  "&title=" + title + "&content=" + content + "&id=" + id, false);
        httpTask.send();
    } else {
        loadStudentPlanner("student-planner", "curr");

    }
    $('#weeklyModal').modal('hide');
    $('#viewWeeklyModal').modal('hide');
    $('#editWeeklyModal').modal('hide');

}



