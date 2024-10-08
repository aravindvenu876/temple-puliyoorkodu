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
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_aavahanam_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_report").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b>No Records Found</b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        if(v.status == "DRAFT"){
                            var totalPaid = "0.00";
                            var advancePaid = "0.00";
                        }else{
                            var totalPaid = parseFloat(+v.advance_paid + +v.balance_paid).toFixed(2);
                            var advancePaid = parseFloat(v.advance_paid).toFixed(2);
                        }
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.booked_on)+"</td>";
                        reportData += "<td>"+convert_date(v.booked_date)+"</td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.phone+"</td>";
                        reportData += "<td style='text-align:right'>"+advancePaid+"</td>";
                        reportData += "<td style='text-align:right'>"+totalPaid+"</td>";
                        reportData += "<td>"+v.status+"</td>";
                        reportData += "</tr>";
                        if(v.status != "CANCELLED"){
                            total = +total + +totalPaid;
                        }
                    });  
                    reportData += "<tr><td colspan='6' style='text-align:right'>Total Amount</td><td style='text-align:right'>"+parseFloat(total).toFixed(2)+"</td><td></td></tr>";
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_aavahanam_report_print',
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
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_aavahanam_report_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
</script>
