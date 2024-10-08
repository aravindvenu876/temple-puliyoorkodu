//Document Owned by GBS-PLUS Pvt Ltd
//-----------------------------------------------------------
//Document created by -   ID No: 20172017
//Created Date        -   20 September 2017
//Modified Date       -   20 September 2017

$(document).ready(function () {

    $(".ham-btn").click(function () {
        $(".content_area").toggleClass("icon_menu");
        $(".navigation_menu").toggleClass("animation");
    });

    $(".sub_menu").not('.no-hide').hide();
    
    $(".navigation_menu li").click(function(){
        $(this).children(".sub_menu").slideToggle();
        $(this).find('.menuItemUpDown').toggleClass('menuHideItem');
    });
    
    $(".bootstrap-datetimepicker-widget [title='Select Decade']").removeAttr("data-action");
});