$(document).on("change", "input[class='check']", function () {
    if ($(this).is(':checked')) {
        var sub_category = $(this).attr("value");
        var listid = $(this).attr("name")
        $(".selected_service").append("<li id='" + listid + "'><a href=''class='remove_category'><i class='glyphicon glyphicon-remove '></i></a> " + sub_category + "</li>");
    } else {
        var sub_category_remove = $(this).attr("name");
        $("#" + sub_category_remove).remove();
    }
});