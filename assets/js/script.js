
$(document).ready(function () { $('#noti_Counter').css({ opacity: 0 }).css({ top: '-10px' }).animate({ top: '-2px', opacity: 1 }, 500); $('#noti_Button').click(function () { $('#notifications').fadeToggle('fast', 'linear', function () { if ($('#notifications').is(':hidden')) { $('#noti_Button').css('background-color', '#2E467C'); } else $('#noti_Button').css('background-color', '#FFF'); }); $('#noti_Counter').fadeOut('slow'); return false; }); $(document).click(function () { $('#notifications').hide(); if ($('#noti_Counter').is(':hidden')) { $('#noti_Button').css('background-color', '#2E467C'); } }); $('#notifications').click(function () { return false; }); });

$(document).ready(function(){
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-37088350-1']);
    _gaq.push(['_trackPageview']);
    (function () {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    (function () {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
  });
  

$(document).ready(function(){
  var navigation = $('#nav-main').okayNav();
  //scrollBox
  $(".sb-container").scrollBox();


});

$(document).ready(function () {

  var updateOutput = function (e) {
      var list = e.length ? e : $(e.target),
          output = list.data('output');
      if (window.JSON) {
          output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
      } else {
          output.val('JSON browser support required for this demo.');
      }
  };

  

});
$(window).on("load", function () {
  $(".load").hide();
});

