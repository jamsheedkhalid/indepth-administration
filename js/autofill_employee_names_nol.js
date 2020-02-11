//Getting value from "ajax.php".
function fill(Name,ID) {
    //Assigning value to "employeeName" div in "employeeName.php" file.
    $('#nol_name').val(Name);
    $('#nol_id').val(ID);
    //Hiding "display" div in "employeeName.php" file.
    $('#nol_display').hide();

    // $('#nol_display_id').hide();
}
$(document).ready(function() {
    //On pressing a key on "Search box" in "employeeName.php" file. This function will be called.
    $("#nol_name").keyup(function () {
        //Assigning employeeName box value to javascript variable named as "name".
        var name = $('#nol_name').val();
        //Validating, if "name" is empty.
        if (name == "") {
            //Assigning empty value to "display" div in "employeeName.php" file.
            $("#nol_display").html("");
        }
        //If name is not empty.
        else {
            //AJAX is called.
            $.ajax({
                //AJAX type is "Post".
                type: "POST",
                //Data will be sent to "ajax.php".
                url: "/mysql/hr/certificate/employeeSearch.php",
                //Data, that will be sent to "ajax.php".
                data: {
                    //Assigning value of "name" into "employeeName" variable.
                    name: name
                },

                //If result found, this funtion will be called.
                success: function (html) {
                    //Assigning result to "display" div in "employeeName.php" file.
                    $("#nol_display").html(html).show();
                }
            });
        }
    });





    // employee name search with id
    $("#nol_id").keyup(function () {
        //Assigning employeeName box value to javascript variable named as "name".
        var id = $('#nol_id').val();
        //Validating, if "name" is empty.
        if (id == "") {
            //Assigning empty value to "display" div in "employeeName.php" file.
            $("#nol_display").html("");
        }
        //If name is not empty.
        else {
            //AJAX is called.
            $.ajax({
                //AJAX type is "Post".
                type: "POST",
                //Data will be sent to "ajax.php".
                url: "/mysql/hr/certificate/employeeSearchID.php",
                //Data, that will be sent to "ajax.php".
                data: {
                    //Assigning value of "name" into "employeeName" variable.
                    id: id
                },

                //If result found, this funtion will be called.
                success: function (html) {
                    //Assigning result to "display" div in "employeeName.php" file.
                    $("#nol_display").html(html).show();
                }
            });
        }
    });


});