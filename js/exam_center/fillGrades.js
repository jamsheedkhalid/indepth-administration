function fillGrades(){
  var gradeArray = ["No Grades!"];
  var httpGrade = new XMLHttpRequest();
  httpGrade.onreadystatechange = function () {
      if(this.readyState ===4){
          var str = this.responseText;
          gradeArray = str.split("\t");
      }
  };
  httpGrade.open("GET", "/indepth-administration/mysql/initGrades.php", false);
  httpGrade.send();

  var select = document.getElementById('gradeSelect');
  delete gradeArray[gradeArray.length-1];
  for(var i in gradeArray){
      select.add(new Option(gradeArray[i]));
  }
}
window.onload = fillGrades();
