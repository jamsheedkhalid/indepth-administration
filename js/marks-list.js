window.onload = initGrades('gradeSelect');
window.onload = search();

function initGrades(grade) {
    var gradeArray = [];
    var httpGrade = new XMLHttpRequest();
    httpGrade.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradeArray = str.split("\t");
        }
    };
    httpGrade.open("GET", "/mysql/exam-center/evaluation-reports/initGrades.php", false);
    httpGrade.send();

    var select = document.getElementById(grade);
    $('#gradeSelect').multiselect('destroy');

    delete gradeArray[gradeArray.length - 1];

    for (var i in gradeArray) {
        select.add(new Option(gradeArray[i]));
    }
    // document.getElementById(grade).selectedIndex = '0';
    $("#gradeSelect option").attr("selected", "selected");
    $('#gradeSelect').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        includeSelectAllOption: true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        buttonWidth: '100px',
        nSelectedText: 'grades selected!',
        dropRight: true,
        selectAllValue: 'true'
    });

    fillSections('sectionSelect', 'gradeSelect')

}

function fillSections(section, grade) {
    // var selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
    let selections = validateMultipleSelect(grade);
    if (selections == false) {
        let first_grade = document.getElementById(grade).options[0].value;
        $('#gradeSelect').multiselect('select', [first_grade]);
    }
    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let options = selected_grade && selected_grade.options;
    let opt;

    for (let i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            grades.push("'" + opt.text + "'");
        }
    }


    var sectionSelect = document.getElementById(section);
    while (sectionSelect.length > 0)
        sectionSelect.remove(0);

    let httpSection = new XMLHttpRequest();
    httpSection.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            sectionArray = str.split("\t");

        }
    };
    httpSection.open("GET", "/mysql/student-analysis/fillSections.php?grade=" + grades, false);
    httpSection.send();

    $('#sectionSelect').multiselect('destroy');
    delete sectionArray[sectionArray.length - 1];
    for (var i in sectionArray) {
        sectionSelect.add(new Option(sectionArray[i]));
    }
    $("#sectionSelect option").attr("selected", "selected");
    $('#sectionSelect').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        enableCollapsibleOptGroups: true,
        includeSelectAllOption: true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        buttonWidth: '140px',
        nSelectedText: ' sections selected!',
        nonSelectedText: 'Select Section',
        dropRight: true


    });


    let first_section = document.getElementById('sectionSelect').options[0].value;
    $('#sectionSelect').multiselect('select', [first_section]);
}

function search() {
    selections = false;
    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let grade_options = selected_grade && selected_grade.options;
    let grade_opt;

    for (let i = 0, iLen = grade_options.length; i < iLen; i++) {
        grade_opt = grade_options[i];
        if (grade_opt.selected) {
            grades.push("'" + grade_opt.text + "'");
        }
    }

    let selected_section = document.getElementById('sectionSelect');
    let sections = [];
    let options = selected_section && selected_section.options;
    let opt;

    for (let i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            sections.push("'" + opt.text + "'");
        }
    }

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

    let result_div = document.getElementById('result_div');
    let httpResult = new XMLHttpRequest();
    httpResult.onreadystatechange = function () {
        if (this.readyState === 4) {
            result_div.innerHTML = this.responseText;
            students_datatable();
        }
    };
    httpResult.open("GET", "/mysql/marks-list/result.php?grade=" + grades + "&section=" + sections +
                    "&filter=" + filter_value + "&show_ar_name=" + show_ar_name +
                    "&show_parent_name=" + show_parent_name + "&show_family_id=" + show_family_id + "&show_contact=" + show_contact, false);
    httpResult.send();

}

function triggerCustomFilter() {
    document.getElementById('custom').checked = true;
    getAnalysis();
}

function triggerCustomFilterLess() {
    document.getElementById('custom_less').checked = true;
    getAnalysis();
}

function validateMultipleSelect(select) {
    select = document.getElementById(select);
    var hasSelection = false;
    var i;
    for (i = 0; i < select.options.length; i += 1) {
        if (select.options[i].selected) {
            hasSelection = true;
        }
    }
    return hasSelection;
}


