function fillStudents(){
    var selected_grade =  document.getElementById('gradeSelect').options[document.getElementById('gradeSelect').selectedIndex].text;
    var studentSelect = document.getElementById("studentSelect");
    while (studentSelect.length > 0)
        studentSelect.remove(0);

    let httpStudent = new XMLHttpRequest();
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            studentsArray = str.split("\t");
        }
    };
    httpStudent.open("GET", "/indepth-administration/mysql/fillStudents.php?grade=" + selected_grade, false);
    httpStudent.send();

    delete studentsArray[studentsArray.length-1];
    for (var i in studentsArray){
        studentSelect.add(new Option(studentsArray[i]));
    }


}
