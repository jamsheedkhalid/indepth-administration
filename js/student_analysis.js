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
    delete gradeArray[gradeArray.length - 1];
    for (var i in gradeArray) {
        select.add(new Option(gradeArray[i]));
    }



}
function fillSections(section, grade) {

    var selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
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
    httpSection.open("GET", "/mysql/exam-center/evaluation-reports/fillSections.php?grade=" + selected_grade, false);
    httpSection.send();

    delete sectionArray[sectionArray.length - 1];
    for (var i in sectionArray) {
        sectionSelect.add(new Option(sectionArray[i]));
    }

}
function fillSubjects(grade, section, subject ){
    let selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
    let selected_section = document.getElementById(section).options[document.getElementById(section).selectedIndex].text;

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
    httpSubject.open("GET", "/mysql/student-analysis/fillSubjects.php?grade=" + selected_grade + "&section=" + selected_section, false);
    httpSubject.send();

    delete subjectArray[subjectArray.length - 1];
    for (let i in subjectArray) {
        selectSubject.add(new Option(subjectArray[i]));
    }

}
function fillTerms(grade, section, term ){
    let selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
    let selected_section = document.getElementById(section).options[document.getElementById(section).selectedIndex].text;
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
    httpTerm.open("GET", "/mysql/student-analysis/fillTerms.php?grade=" + selected_grade + "&section=" + selected_section, false);
    httpTerm.send();

    delete termsArray[termsArray.length - 1];
    for (let i in termsArray) {
        selectTerm.add(new Option(termsArray[i]));
    }

}
function getAnalysis(){
    let selected_grade = document.getElementById('gradeSelect').options[document.getElementById('gradeSelect').selectedIndex].text;
    let selected_section = document.getElementById('sectionSelect').options[document.getElementById('sectionSelect').selectedIndex].text;
    let selected_subject = document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    let selected_term = document.getElementById('term').options[document.getElementById('term').selectedIndex].text;
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
    httpResult.open("GET", "/mysql/student-analysis/result.php?grade=" + selected_grade + "&section=" + selected_section + "&subject=" + selected_subject + "&term=" + selected_term + "&filter=" + filter_value  + "&custom_filter=" +
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





