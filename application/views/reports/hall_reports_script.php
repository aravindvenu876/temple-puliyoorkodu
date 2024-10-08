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
        url: '<?php echo base_url() ?>service/Hall_data/get_hall_list_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Hall</option>';
            $.each(data.name, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name_eng + '</option>';
            });
            $("#hall").html(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var hall = $("#hall").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_hall_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,hall:hall},
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
                    var total_amt=0;
                    var paid_amt=0;
                    var total_paid_amt=0;
                    $.each(data.report, function (i, v) {
                        j++;
                        // $date_ex=$row->date;
                        // $date=date("d-m-Y", strtotime($date_ex));
                        var total_amt = +v.advance_paid + +v.balance_paid + +v.balance_to_be_paid;
                        var paid_amt = +v.advance_paid + +v.balance_paid;
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.date)+"</td>";
                        reportData += "<td>"+convert_date(v.from_date)+"</td>";
                        reportData += "<td>"+convert_date(v.to_date)+"</td>";
                        reportData += "<td>"+v.hall_name_eng+"</td>";
                        reportData += "<td>"+v.devotee_name+"</td>";
                        reportData += "<td>"+v.phone+"</td>";
                        reportData += "<td style='text-align:right'>"+parseFloat(v.advance_paid,10).toFixed(2)+"</td>";
                        reportData += "<td style='text-align:right'>"+parseFloat(paid_amt,10).toFixed(2)+"</td>";
                        reportData += "<td style='text-align:right'>"+parseFloat(total_amt,10).toFixed(2)+"</td>";
                        reportData += "<td>"+v.payment_status+"</td>";
                        reportData += "</tr>";
                        total = +total + +total_amt; 
                        total_paid_amt = +total_paid_amt + +paid_amt;
                    }); 
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    var total_paid_amt1= parseFloat(total_paid_amt,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='8' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_paid_amt1+"</th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='2'></th></tr>"; 
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var hall = $("#hall").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_hallreport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,hall:hall},
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
        $("#hall").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var hall = $("#hall").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_hallreport_pdf?from_date='+from_date+'&to_date='+to_date+'&hall='+hall, '_blank');       
    });
</script>
