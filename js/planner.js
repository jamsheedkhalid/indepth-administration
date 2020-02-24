window.onload = initGrades();

function initGrades() {
    var items="";
    $.getJSON("/mysql/planner/initGrades.php",function(data){
        $.each(data,function(index,item)
        {
            items+="<option value='"+item.course_id+"'>"+item.course_name+"</option>";
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
    var table = document.getElementById('weekly-planner');
    var cells = table.getElementsByTagName('td');

    for (var i = 0; i < cells.length; i++) {
        // Take each cell
        var cell = cells[i];
        // do something on onclick event for cell
        cell.onclick = function () {
            // Get the row id where the cell exists
            var rowId = this.parentNode.rowIndex;
            var colId = this.cellIndex;

            var rowsNotSelected = table.getElementsByTagName('tr');
            for (var row = 0; row < rowsNotSelected.length; row++) {
                rowsNotSelected[row].style.backgroundColor = "";
                rowsNotSelected[row].style.color = "";
                rowsNotSelected[row].classList.remove('selected');
            }
            var rowSelected = table.getElementsByTagName('tr')[rowId];
            // rowSelected.style.backgroundColor = "#D5ECED";
            rowSelected.style.backgroundColor = "#D5ECED";
            rowSelected.style.color = "black";
            rowSelected.className += " selected";

            msg = ' <label> Day: ' + rowSelected.cells[0].innerHTML + ', ' + rowSelected.cells[0].title + '</label>  ';
            msg += '<input type="text" autocapitalize="on" placeholder="Title" class="form-control-sm form-control" /><br>';
            msg += '<textarea type="text" placeholder="Description" class="form-control-sm form-control" />';
            // msg += '\n Sub: ' + rowSelected.cells[1].innerHTML;
            // msg += '\n Cell value: ' + this.innerHTML;
//            alert(msg);

            document.getElementById('modalBody').innerHTML = msg;
            // $("#weeklyModal .modal-body").innerHTML = msg;
            $('#weeklyModal').modal('show');
        }
    }

} //end of function



