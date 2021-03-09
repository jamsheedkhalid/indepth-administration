window.onload = search();

function search() {
    let show_ar_name = document.getElementById('show_ar_names').checked;
    let show_parent_name = document.getElementById('show_parent_name').checked;
    let show_family_id = document.getElementById('show_family_id').checked;
    let show_contact = document.getElementById('show_contact').checked;

    let filter = document.getElementsByName('filter');
    let filter_value;
    for (let i = 0, length = filter.length; i < length; i++) {
        if (filter[i].checked) {
            filter_value = filter[i].value;
            break;
        }
    }
    let grades = document.getElementById('grade').value;
    let result_div = document.getElementById('result_div');


    let httpResult = new XMLHttpRequest();
    httpResult.onreadystatechange = function () {
        if (this.readyState === 4) {
            result_div.innerHTML = this.responseText;
            students_datatable();
        }
    };

    httpResult.open("GET", "/mysql/marks-list/result.php?grade=" + grades + "&filter=" + filter_value + "&show_ar_name=" + show_ar_name +
        "&show_parent_name=" + show_parent_name + "&show_family_id=" + show_family_id + "&show_contact=" + show_contact, false);
    httpResult.send();
}

function updateProgress() {
    $('#loader').show();
    $(document).ready(function () {
        $('#loader').show();
    });
}

function loadCompleted() {
    $(document).ready(function () {
        $('#loader').hide();
    });
}