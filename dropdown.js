$(document).ready(function () { 
    $("#category0").change(function () {
        var ddl = document.getElementById("category0");
        var cat0 = ddl.options[ddl.selectedIndex].value;
        if (cat0 != "") {
            $.get("get_sub_category.php?category=" + cat0, function (data) {
                $('#sub_category').html(data);
            });
        }
    });
});