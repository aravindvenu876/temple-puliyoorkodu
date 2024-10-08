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
    $(".btn_print_html").hide();
    $(".pdf_report").hide();
    get_reports();
    $.ajax({
        url: '<?php echo base_url() ?>service/Reports_data/user_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select User</option>';
            $.each(data.users, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#user").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Reports_data/counters_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Counter</option>';
            $.each(data.counters, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.counter_no + '</option>';
            });
            $("#counter").html(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var counter = $("#counter").val();
        var user = $("#user").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_cancel_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,counter:counter,user:user},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_report").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.receipt_no+"</td>";
                        reportData += "<td>"+v.pooja+"</td>";
                        reportData += "<td>"+convert_date(v.receipt_date)+"</td>";
                        reportData += "<td style='text-align:right'>"+v.receipt_amount+"</td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.counter_no+"</td>";
                        reportData += "</tr>";
                        total = +total + +v.receipt_amount;
                    });  
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='4' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='4'></th></tr>";
                    if(data.session_data != ""){
                        reportData += "<tr>";
                        reportData += "<th colspan='2' style='text-align:left'><?php echo $this->lang->line('session_started_on'); ?></th>";
                        reportData += "<th colspan='2' style='text-align:left'>"+data.session_data.start+"</th>";
                        reportData += "<th colspan='2' style='text-align:left'><?php echo $this->lang->line('session_ended_on'); ?></th>";
                        reportData += "<th colspan='2' style='text-align:left'>"+data.session_data.end+"</th>";
                        reportData += "</tr>";
                    }
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var counter = $("#counter").val();
        var user = $("#user").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_cancel_report_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,counter:counter,user:user},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    $(".btn_clear").click(function(){
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>");
        $("#counter").val("");
        $("#user").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var counter = $("#counter").val();
        var user = $("#user").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_cancel_report_pdf?from_date='+from_date+'&to_date='+to_date+'&counter='+counter+'&user='+user, '_blank');       
    });
</script>
