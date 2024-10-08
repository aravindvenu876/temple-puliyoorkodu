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
    get_reports();
    $("#ledger_pdf").hide();
    $("#ledger_excel").hide();
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_one_data/get_day_book_report',
            type: 'POST',
            data: {from_date:$("#from_date").val(),to_date:$("#to_date").val()},
            success: function (data) {
                // if (data.content_status == 0) {
                //     $("#ledger_pdf").hide();
                //     $("#ledger_excel").hide(); 
                // }
                $("#report_body").html(data.report_content);
            }
        });
    }
    $(".btn_clear").click(function(){
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>");
        get_reports();
    });
    // $("#ledger_pdf").click(function(){
    //     var from_date   = $("#from_date").val();
    //     var to_date     = $("#to_date").val();
    //     var reg_no      = $("#reg_no").val();
    //     var payment_mode= $("#payment_mode").val();
    //     window.open('<?php echo base_url() ?>service/Reports_one_data/get_payment_report_pdf?from_date='+from_date+'&to_date='+to_date+'&reg_no='+reg_no+'&payment_mode='+payment_mode, '_blank');       
    // });
    // $("#ledger_excel").click(function(){
    //     var from_date   = $("#from_date").val();
    //     var to_date     = $("#to_date").val();
    //     var reg_no      = $("#reg_no").val();
    //     var payment_mode= $("#payment_mode").val();
    //     window.open('<?php echo base_url() ?>service/Reports_one_data/get_payment_report_excel?from_date='+from_date+'&to_date='+to_date+'&reg_no='+reg_no+'&payment_mode='+payment_mode, '_blank');       
    // });
</script>
