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
    var date =  document.getElementById('week_date').value;
    let httpSection = new XMLHttpRequest();
    httpSection.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('section').innerHTML = this.responseText;

        }
    };
    httpSection.open("GET", "/mysql/planner/fillSections.php?grade=" + selected_grade, false);
    httpSection.send();

    loadStudentPlanner("student-planner", date);


}

function loadStudentPlanner(div, date) {
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
    httpPlanner.open("GET", "/mysql/planner/students-planner.php?date_type=" + date + "&section=" + selected_section + "&grade=" + selected_grade, false);
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
                    if (is_teaching == 1) {
                        msg = '<form method="post" enctype="multipart/form-data"> <label>Day: ' + date + '</label>   ';
                        msg += ' <div id="pogress_bar" class="progress-bar progress-bar-animated bg-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 25%;" ></div>';
                        msg += ' <div class="row"><div class="col"><b><label> Grade: ' + selected_grade + ' ' + '</label> </b></div> ';
                        msg += '<div class="col"> <label><b>Sub: ' + subject + '</b> </label></div></div>   ';
                        // msg += '<select id="task-subject" class="form-control-sm form-control"><option selected disabled>Select Subject</option></select><br>';
                        msg += '<input id="subject_id" type="hidden" value="' + rowSubject.cells[cellId].id + '" />';
                        msg += '<input id="date" type="hidden" value="' + rowSelected.cells[0].title + '" />';
                        msg += '<input id="task_title" type="text" required="required" autocapitalize="on" placeholder="Title" class=" form-control-sm form-control" /><br>';
                        msg += '<small id="title_warning" style="display: none"><div class="alert alert-sm alert-danger  alert-dismissible  fade show" role="alert">Please enter Title!</div></small><br>';
                        msg += '<textarea id="task_content" type="text"  style="height: 100px" placeholder="Description" class="form-control-sm form-control" ></textarea><br>';
                        msg += '<div class="position-relative form-group">' +
                            '<label for="file_upload" class="">Upload File</label>'+
                            '<input name="file_upload" id="file_upload" type="file" onchange="fileValidation()" class="form-control-file"><br>'+
                            '<small id="upload_warning" style="display: none"><div class="alert alert-sm alert-danger fade show" role="alert">File too large to upload! (Max 30MB)</div></small></div></form> ';
                        msg += '<label for="task-select" hidden >Select Student</label><select hidden id="task-select" name="task-select" multiple="multiple"  size = "5" class="form-control-sm form-control"><option>Select Students</option></select>';
                        // msg += '\n Sub: ' + rowSelected.cells[1].innerHTML;
                        // msg += '\n Cell value: ' + this.innerHTML;
//            alert(msg);

                        document.getElementById('modalBody').innerHTML = msg;
                        loadSubjects();
                        loadStudents('task-select');
                        // $("#weeklyModal .modal-body").innerHTML = msg;
                        $('#weeklyModal').modal('show')
                    } else {
                        alert("Subject is not assigned! Please select your subjects only");
                    }
                }
            };
            httpTask.open("GET", "/mysql/planner/subject-privilege.php?id=" + sub_id + '&grade=' + selected_grade_id, false);
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


function fileValidation() {
    const fi = document.getElementById('file_upload');
    if (fi.files.length > 0) {
        for (const i = 0; i <= fi.files.length - 1; i++) {

            const fsize = fi.files.item(i).size;
            const file = Math.round((fsize / 1024));
            // The size of the file.
            if (file >= 30720) {

                     document.getElementById("saveBtn").disabled = true;
                     document.getElementById("upload_warning").style.display = 'inline';


            }  else {
                document.getElementById("saveBtn").disabled = false;
                document.getElementById("upload_warning").style.display = 'none';

            }
        }
    }
}


function saveTask() {
    let selected_students = $('#task-select').val();
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;
    let subject = document.getElementById('subject_id').value;
    let title = document.getElementById('task_title').value;
    let content = document.getElementById('task_content').value;
    let date = document.getElementById('date').value;
    var file_data = $('#file_upload').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    content = content.replace(/\n\r?/g, '<br />');
    if (!title.replace(/\s/g, '').length) {
        document.getElementById('title_warning').style.display = 'inline';
    } else {
        document.getElementById("title_warning").style.display = 'none';
        let httpTask = new XMLHttpRequest();
        httpTask.onreadystatechange = function () {
            if (this.readyState === 4) {
                // document.getElementById('student-planner').innerHTML = this.responseText;
                // alert(this.responseText);
                    loadStudentPlanner("student-planner", date);
                    $('#weeklyModal').modal('hide');
            }
        };

        httpTask.open("POST", "/mysql/planner/save-task.php?section=" + selected_section + "&grade=" + selected_grade + "&selected_students=" + selected_students + "&subject=" + subject + "&title=" + title + "&content=" + content + "&date=" + date +"&data=" + form_data, false);
        httpTask.send(form_data);
        // progress bar
        var progress = document.getElementById('progress_bar');

        httpTask.upload.addEventListener("progress", function(e) {
            var pc = parseInt(100 - (e.loaded / e.total * 100));
            progress.setAttribute("aria-valuenow",pc.toString());
        }, false)
    }

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
    var date =  document.getElementById('week_date').value;
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].value;

    let conf = confirm("Are you sure you want to delete?");
    if (conf === true) {

        let httpTask = new XMLHttpRequest();
        httpTask.onreadystatechange = function () {
            if (this.readyState === 4) {
                let response = this.responseText;

                if (response == 0) {
                    alert("Sorry, you are not allowed to delete this task!");
                } else {
                    loadStudentPlanner("student-planner", date);
                    // document.getElementById('student-planner').innerHTML=this.responseText;
                }
            }
        };
        httpTask.open("GET", "/mysql/planner/del-task.php?id=" + id + "&grade=" + selected_grade, false);
        httpTask.send();
    } else {
        loadStudentPlanner("student-planner", date);

    }
    $('#viewWeeklyModal').modal('hide');
}

function editTask(id) {

    var date =  document.getElementById('week_date').value;
    let selected_grade = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].text;
    let selected_grade_id = document.getElementById('grade').options[document.getElementById('grade').selectedIndex].value;
    let selected_section = document.getElementById('section').options[document.getElementById('section').selectedIndex].text;
    let editModal = document.getElementById('editModalBody');

    let httpTask = new XMLHttpRequest();
    httpTask.onreadystatechange = function () {
        if (this.readyState === 4) {
            let response = this.responseText;
            if (!response) {
                alert("Sorry, you are not allowed to edit this task!");
            } else  {
                editModal.innerHTML = response;
                $('#viewWeeklyModal').modal('hide');
                // loadStudents('task-select-edit');
                $('#editWeeklyModal').modal('show');
                loadStudentPlanner("student-planner", date);
            }


        }
    };
    httpTask.open("GET", "/mysql/planner/edit-task.php?id=" + id + "&grade=" +  selected_grade_id, false);
    httpTask.send();

    $('.select_all').click(function () {
        $('#task-select-edit option').prop('selected', true);
    });


}

function updateTask(id) {
    var date =  document.getElementById('week_date').value;

    let conf = confirm("Are you sure you want to edit?");
    if (conf === true) {
        let selected_students = $('#task-select-edit').val();
        let title = document.getElementById('edit_title').value;
        let content = document.getElementById('edit_content').value;


        let httpTask = new XMLHttpRequest();
        httpTask.onreadystatechange = function () {
            if (this.readyState === 4) {
                // document.getElementById('student-planner').innerHTML = this.responseText;
                loadStudentPlanner("student-planner", date);
            }
        };
        httpTask.open("GET", "/mysql/planner/update-task.php?selected_students=" + selected_students + "&title=" + title + "&content=" + content + "&id=" + id, false);
        httpTask.send();
    } else {
        loadStudentPlanner("student-planner", date);

    }
    $('#weeklyModal').modal('hide');
    $('#viewWeeklyModal').modal('hide');
    $('#editWeeklyModal').modal('hide');

}




