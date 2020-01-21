function fillStudents(grade,student){
    let selected_grade = document.getElementById(grade).options[document.getElementById(grade).selectedIndex].text;
    let studentSelect = document.getElementById(student);
    let httpStudent = new XMLHttpRequest();
    let studentsArray;
    httpStudent.onreadystatechange = function () {
        if (this.readyState === 4) {
            let str = this.responseText;
            studentsArray = str.split("\t");
        }
    };
    httpStudent.open("GET", "/indepth-administration/mysql/fillStudents.php", false);
    httpStudent.send();

    $(studentSelect).multiselect('destroy');
    delete studentsArray[studentsArray.length-1];
    for (let i in studentsArray){
        studentSelect.add(new Option(studentsArray[i]));
    }

}
