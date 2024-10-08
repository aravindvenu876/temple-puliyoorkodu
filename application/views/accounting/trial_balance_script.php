<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
   var date = new Date();
   var today= new Date(date.getFullYear(), date.getMonth(), date.getDate());
   var end  = new Date(date.getFullYear(), date.getMonth(), date.getDate());
   $('#from_date').datepicker({
        format: "dd-mm-yyyy",
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    $("#ledger_pdf").hide();
    $("#ledger_pdf1").hide();
    $("#ledger_excel").hide();
    $("#btn_submit").click(function(){
        get_trial_balance();
    });
    function get_reports(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_one_data/get_trial_balance',
            type: 'GET',
            success: function (data) {
                $("#report_body").html(data.report_content);
            }
        });
    }
    function get_trial_balance(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_one_data/get_trial_balance_new',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
            success: function (data) {
                $("#report_body").html(data.report_content);
                $("#ledger_pdf").show();
                $("#ledger_pdf1").show();
            }
        });
    }
    function open_child_section(parentId,level){
        var css_prop = $('.child_sec_'+parentId).css("display");
        if(css_prop == 'none'){
            $('.child_sec_' + parentId + '_' + level).css('display','revert');
        }else{
            $('.child_sec_'+parentId).css('display','none');
        }
        // $('.child_sec_'+parentId).css('display','block');
    }
    $("#ledger_pdf").click(function(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        var check_flag  = 0;
        window.open('<?php echo base_url() ?>service/Reports_one_data/get_trial_balance_new_pdf?from_date='+from_date+'&to_date='+to_date+'&check_flag='+check_flag, '_blank');       
    });
    $("#ledger_pdf1").click(function(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        var check_flag  = 1;
        window.open('<?php echo base_url() ?>service/Reports_one_data/get_trial_balance_new_pdf?from_date='+from_date+'&to_date='+to_date+'&check_flag='+check_flag, '_blank');       
    });
</script>
