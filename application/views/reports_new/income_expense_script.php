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
    function report_html(){
        $("#report_content").html("<b><i>LOADING REPORT... PLEASE WAIT...</i></b>");
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_new_data/income_expense_report_html',
            type: 'GET',
            data:{from_date:$("#from_date").val(),to_date:$("#to_date").val()},
            success: function (data) {
                $("#report_content").html(data);
            }
        });
    }
    function report_pdf(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_new_data/income_expense_report_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');
    }
    function report_clear(){
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>"); 
    }
</script>
