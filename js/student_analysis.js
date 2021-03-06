window.onload = initGrades('gradeSelect');

function initGrades(grade) {
    var gradeArray = ["No Grades!"];
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
    document.getElementById(grade).selectedIndex = '3';

    $('#gradeSelect').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        includeSelectAllOption: true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        buttonWidth: '100px',
        nSelectedText: ' grades selected!',
        dropRight: true

    });
    fillSections('sectionSelect','gradeSelect')

}
function fillSections(section, grade) {
    // var selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
    let selections = validateMultipleSelect(grade);
    if(selections == false){
        alert("Atleast select one grade!");
        let first_grade = document.getElementById(grade).options[3].value;
        $('#gradeSelect').multiselect('select', [first_grade]);

    }

    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let options = selected_grade && selected_grade.options;
    let opt;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            grades.push("'"+opt.text+"'");
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

    $('#sectionSelect').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering : true,
        enableCollapsibleOptGroups:true,
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
    fillSubjects('gradeSelect','sectionSelect','subject');
    fillTerms('gradeSelect', 'sectionSelect', 'term' );


}
function fillSubjects(grade, section, subject ){

    let selections = false;
    selections = validateMultipleSelect('sectionSelect');
    if(selections == false){
        alert("Atleast select one section!");
        let first_grade = document.getElementById('sectionSelect').options[0].value;
        $('#sectionSelect').multiselect('select', [first_grade]);

    }

    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let grade_options = selected_grade && selected_grade.options;
    let grade_opt;

    for (let i=0, iLen=grade_options.length; i<iLen; i++) {
        grade_opt = grade_options[i];
        if (grade_opt.selected) {
            grades.push("'"+grade_opt.text+"'");
        }
    }


    let selected_section = document.getElementById('sectionSelect');
    let sections = [];
    let options = selected_section && selected_section.options;
    let opt;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            sections.push("'"+opt.text+"'");
        }
    }

    let selectSubject = document.getElementById(subject);
    while (selectSubject.length > 0)
        selectSubject.remove(0);

    let httpSubject = new XMLHttpRequest();
    httpSubject.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            subjectArray = str.split("\t");
        }
    };
    httpSubject.open("GET", "/mysql/student-analysis/fillSubjects.php?grade=" + grades + "&section=" + sections, false);
    httpSubject.send();
    $('#subject').multiselect('destroy');
    delete subjectArray[subjectArray.length - 1];
    for (let i in subjectArray) {
        selectSubject.add(new Option(subjectArray[i]));
    }

    $('#subject').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering : true,
        includeSelectAllOption: true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        buttonWidth: '140px',
        nSelectedText: ' subjects selected!',
        nonSelectedText: 'Select Subject',
        dropRight: true


    });

    let first_subject = document.getElementById('subject').options[0].value;
    $('#subject').multiselect('select', [first_subject]);




}
function fillTerms(grade, section, term ){

    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let grade_options = selected_grade && selected_grade.options;
    let grade_opt;

    for (let i=0, iLen=grade_options.length; i<iLen; i++) {
        grade_opt = grade_options[i];
        if (grade_opt.selected) {
            grades.push("'"+grade_opt.text+"'");
        }
    }


    let selected_section = document.getElementById('sectionSelect');
    let sections = [];
    let options = selected_section && selected_section.options;
    let opt;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            sections.push("'"+opt.text+"'");
        }
    }
    let selectTerm = document.getElementById(term);

    while (selectTerm.length > 0)
        selectTerm.remove(0);

    let httpTerm = new XMLHttpRequest();
    httpTerm.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            termsArray = str.split("\t");
        }
    };
    httpTerm.open("GET", "/mysql/student-analysis/fillTerms.php?grade=" + grades + "&section=" + sections, false);
    httpTerm.send();
    $('#term').multiselect('destroy');
    delete termsArray[termsArray.length - 1];
    for (let i in termsArray) {
        selectTerm.add(new Option(termsArray[i]));
    }
    $('#term').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering : true,
        includeSelectAllOption: true,
        selectAllJustVisible: true,
        buttonClass: 'form-control-sm form-control',
        buttonWidth: '140px',
        nSelectedText: ' terms selected!',
        nonSelectedText: 'Select Term',
        dropRight: true


    });
    let first_term = document.getElementById('term').options[0].value;
    $('#term').multiselect('select', [first_term]);
    getAnalysis();
}
function getAnalysis(){


     selections = false;
    selections = validateMultipleSelect('subject');
    if(selections == false){
        alert("Atleast select one subject!");
        // $("#subject").multiselect('selectAll', false);
        let first_subject = document.getElementById('subject').options[0].value;
        $('#subject').multiselect('select', [first_subject]);

    }


     selections = false;
    selections = validateMultipleSelect('term');
    if(selections == false){
        alert("Atleast select one term!");
        let first_term = document.getElementById('term').options[0].value;
        $('#term').multiselect('select', [first_term]);

    }

    let selected_grade = document.getElementById('gradeSelect');
    let grades = [];
    let grade_options = selected_grade && selected_grade.options;
    let grade_opt;

    for (let i=0, iLen=grade_options.length; i<iLen; i++) {
        grade_opt = grade_options[i];
        if (grade_opt.selected) {
            grades.push("'"+grade_opt.text+"'");
        }
    }

    let selected_section = document.getElementById('sectionSelect');
    let sections = [];
    let options = selected_section && selected_section.options;
    let opt;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            sections.push("'"+opt.text+"'");
        }
    }

    let selected_subject = document.getElementById('subject');
    let subjects = [];
     options = selected_subject && selected_subject.options;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            subjects.push("'"+opt.text+"'");
        }
    }

    let selected_term = document.getElementById('term');
    let terms = [];
    options = selected_term && selected_term.options;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            terms.push("'"+opt.text+"'");
        }
    }


    let custom_filter = document.getElementById('custom_filter').value;
    let custom_filter_less = document.getElementById('custom_filter_less').value;
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
            initDataTable();
        }
    };
    httpResult.open("GET", "/mysql/student-analysis/result.php?grade=" + grades + "&section=" + sections + "&subject=" + subjects + "&term=" + terms + "&filter=" + filter_value  + "&custom_filter=" +
        custom_filter  + "&custom_filter_less=" + custom_filter_less+ "&show_ar_name=" + show_ar_name + "&show_parent_name=" + show_parent_name  + "&show_family_id=" + show_family_id  + "&show_contact=" + show_contact  , false);
    httpResult.send();




}
function triggerCustomFilter(){
    document.getElementById('custom').checked = true;
    getAnalysis();
}
function triggerCustomFilterLess(){
    document.getElementById('custom_less').checked = true;
    getAnalysis();
}
function initDataTable() {
    // DataTable
    $('#marks_table').DataTable({


    });

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

window.onload = function () {
    initDataTable();

};




