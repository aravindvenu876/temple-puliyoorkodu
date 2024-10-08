<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
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
    $(".pdf_payslip").hide();
    
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
            url: '<?php echo base_url() ?>service/Reports_data/get_staff_wise_amount_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,counter:counter,user:user},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_payslip").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_payslip").show();
                    var j = 0;
                    var totalExcessAmount = 0.00;
                    var totalShortageAmount = 0.00;
                    var excessAmount = "0.00";
                    var shortageAmount = "0.00";
                    var difference = "0.00";
                    $.each(data.report, function (i, v) {
                        j++;
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        excessAmount = "0.00";
                        shortageAmount = "0.00";
                        if(v.actual_closing_amount < v.closing_amount){
                            shortageAmount = +v.closing_amount - +v.actual_closing_amount;
                            totalShortageAmount = +totalShortageAmount + +shortageAmount;
                        }else{
                            excessAmount = +v.actual_closing_amount - +v.closing_amount;
                            totalExcessAmount = +totalExcessAmount + +excessAmount;
                        }
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.session_date)+"</td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.counter_no+"</td>";
                        reportData += "<td>"+v.id+"</td>";
                        reportData += "<td><span class='amntRight'>"+v.closing_amount+"</span></td>";
                        reportData += "<td><span class='amntRight'>"+v.actual_closing_amount+"</span></td>";
                        reportData += "<td><span class='amntRight'>"+parseFloat(excessAmount).toFixed(2)+"</span></td>";
                        reportData += "<td><span class='amntRight'>"+parseFloat(shortageAmount).toFixed(2)+"</span></td>";
                        reportData += "<td>"+v.description+"</td>";
                        reportData += "</tr>";
                    }); 
                    if(totalExcessAmount < totalShortageAmount){
                        difference = +totalShortageAmount - +totalExcessAmount;
                        difference = "-"+difference;
                    }else{
                        difference = +totalExcessAmount - +totalShortageAmount;
                        difference = "+"+difference;
                    }
                    reportData += "<tr>";
                    reportData += "<td colspan='7' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></td>";
                    reportData += "<td style='text-align:right'><span class='amntRight'>"+parseFloat(totalExcessAmount).toFixed(2)+"</span></td>";
                    reportData += "<td style='text-align:right'><span class='amntRight'>"+parseFloat(totalShortageAmount).toFixed(2)+"</span></td>";
                    reportData += "<td colspan='5'></th></tr>";
                    reportData += "<tr>";
                    reportData += "<td colspan='7'><?php echo $this->lang->line('Difference'); ?></td>";
                    reportData += "<td colspan='2'><span class='amntRight'>"+parseFloat(difference).toFixed(2)+"</span></td>";
                    reportData += "<td colspan='5'></td>";
                    reportData += "</tr>";
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
            url: '<?php echo base_url() ?>service/Reports_data/get_staff_wise_amount_report_print',
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
    $(".pdf_payslip").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var counter = $("#counter").val();
        var user = $("#user").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_staff_wise_amount_report_pdf?from_date='+from_date+'&to_date='+to_date+'&counter='+counter+'&user='+user, '_blank');       
    });
</script>
