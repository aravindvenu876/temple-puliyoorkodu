<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
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
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_all_ledgers_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account</option>';
            $.each(data.ledgers, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.head + '</option>';
            });
            $("#head").html(string);
            $('#head').select2({ width: '100%' });
        }
    });
    // get_reports();
    $("#ledger_pdf").hide();
    $("#ledger_excel").hide();
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        $("#report_body").html("<b><i>Please Wait... Data Loading...</i></b>");
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_one_data/get_tally_ledger_report',
            type: 'POST',
            data: {from_date:$("#from_date").val(),to_date:$("#to_date").val(),head:$("#head").val()},
            success: function (data) {
                if (data.content_status == 0) {
                    $("#ledger_pdf").hide();
                }else{
                    $("#ledger_pdf").show();
                }
                $("#report_body").html(data.report_content);
            }
        });
    }
    $(".btn_clear").click(function(){
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>");
        $("#head").val("");
        $("#pay_type").val("");
        get_reports();
    });
    function open_child_section(id){
        var css_prop = $('#'+id).css("display");
        if(css_prop == 'none'){
            $('#'+id).css('display','revert');
        }else{
            $('#'+id).css('display','none');
        }
    }
</script>
