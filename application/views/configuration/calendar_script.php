<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var formChange = 0;
    $('#edit_calendar_form').on('change keyup paste', ':input', function(e) {
        var keycode = e.which;
        if (e.type === 'paste' || e.type === 'change' || (
            (keycode === 46 || keycode === 8) || // delete & backspace
            (keycode > 47 && keycode < 58) || // number keys
            keycode == 32 || keycode == 13 || // spacebar & return key(s) (if you want to allow carriage returns)
            (keycode > 64 && keycode < 91) || // letter keys
            (keycode > 95 && keycode < 112) || // numpad keys
            (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
            (keycode > 218 && keycode < 223))) { // [\]' (in order))
                formChange = 1;
        }
    });
    $("#gregyear").change(function(){
        if(formChange){
            confirm_change('eng');
        }else{
            update_calendar('eng');
        }
    });
    $("#gregmonth").change(function(){
        if(formChange){
            confirm_change('eng');
        }else{
            update_calendar('eng');
        }
    });
    $("#malyear").change(function(){
        if(formChange){
            confirm_change('mal');
        }else{
            update_calendar('mal');
        }
    });
    $("#malmonth").change(function(){
        if(formChange){
            confirm_change('mal');
        }else{
            update_calendar('mal');
        }
    });

    function update_calendar(lang){
        $(".load").show();
        if(lang=='eng'){
            var year = $("#gregyear").val();
            var month = $("#gregmonth").val();
        }
        if(lang=='mal'){
            var year = $("#malyear").val();
            var month = $("#malmonth").val();
        }
        $.ajax({
            url: '<?php echo base_url();?>service/calendar_data/get_calendar_data',
            type: 'POST',
            data: {
                lang: lang,
                year: year,
                month: month
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.calendar_save_status == 1){
                    $("#calendar_save").show();
                }else{
                    $("#calendar_save").hide();
                }
                $("#calendar_heading").html(obj.calendar_heading);
                $("#calendar_content").html(obj.calendar_content);
                $(".load").hide();
            },
            error: function () {
                $(".load").hide();
                $.toaster({priority:'danger',title:'Error',message:'An error occured'});
            }
        });
    }

    function save_calendar_changes(lang=0){
        $.confirm({
            title: 'Notice',
            content: 'Are you sure you want to save these changes to calendar?',
            animation: 'scale',
            closeAnimation: 'scale',
            opacity: 0.5,
            buttons: {
                'confirm': {
                    text: 'Save',
                    btnClass: 'btn-blue',
                    action: function() {
                        save_calendar(lang);
                    }
                },
                'cancel': {
                    text: "Don't save",
                    action: function() {
                        update_calendar(lang);
                    }
                }
            }
        });
    }
    function save_calendar(lang=0){
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url();?>service/calendar_data/save_calendar_changes',
            type: 'POST',
            data: $("#edit_calendar_form").serialize(),
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status==1){
                    if(lang!=0){update_calendar(lang);}
                    $.toaster({priority:'success',title:'Notice',message:obj.message});
                }else{
                    $.toaster({priority:'danger',title:'Notice',message:obj.message});
                }
                $(".load").hide();
            },
            error: function () {
                $(".load").hide();
                $.toaster({priority:'danger',title:'Error',message:'An error occured'});
            }
        });
    }

    function confirm_change(lang){
        formChange=0;
        $.confirm({
                title: 'Notice',
                content: 'You have made some changes in the calendar do you want to save',
                animation: 'scale',
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    'confirm': {
                        text: 'Save',
                        btnClass: 'btn-blue',
                        action: function() {
                            save_calendar_changes(lang);
                        }
                    },
                    'cancel': {
                        text: "Don't save",
                        action: function() {
                            update_calendar(lang);
                        }
                    }
                }
            });
    }

</script>




