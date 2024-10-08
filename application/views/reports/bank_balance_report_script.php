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
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_bank_balance_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
            success: function (data) {
                reportData = "";
                if (data.accountReport.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_payslip").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_payslip").show();
                    var j = 0;
                    var totalOpeningAmount = 0.00;
                    var totalClosingAmount = 0.00;
                    $.each(data.accountReport, function (i, v) {
                        j++;
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.bank_eng+"</td>";
                        reportData += "<td>"+v.account_no+"</td>";
                        reportData += "<td><span class='amntRight'>"+v.opening+"</span></td>";
                        reportData += "<td><span class='amntRight'>"+v.closing+"</span></td>";
                        reportData += "</tr>";
                        totalOpeningAmount = +totalOpeningAmount + +v.opening;
                        totalClosingAmount = +totalClosingAmount + +v.closing;
                    });
                    reportData += "<tr>";
                    reportData += "<td colspan='3' style='text-align:right'><span class='amntRight'><?php echo $this->lang->line('total_amount'); ?></span></td>";
                    reportData += "<td style='text-align:right'><span class='amntRight'>"+parseFloat(totalOpeningAmount).toFixed(2)+"</span></td>";
                    reportData += "<td style='text-align:right'><span class='amntRight'>"+parseFloat(totalClosingAmount).toFixed(2)+"</span></td>";
                    reportData += "</tr>";
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_bank_balance_report_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
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
        window.open('<?php echo base_url() ?>service/Reports_data/get_bank_balance_report_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
</script>
