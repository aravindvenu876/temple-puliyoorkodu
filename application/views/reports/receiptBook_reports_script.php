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
        url: '<?php echo base_url() ?>service/Receipt_book_data/get_receiptbook_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Book</option>';
            $.each(data.id, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.book + '</option>';
            });
            $("#type").html(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        $("#report_body").html('<tr><td colspan="10" style="text-align:center"><b>...Data...Loading...</b></td></tr>');
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_receiptbook_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    reportData += '<tr><td colspan="10" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf").show();
                    $(".excel_report").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.created_on)+"</td>";
                        reportData += "<td>"+v.book_eng+"</td>";
                        reportData += "<td>"+v.book_no+"</td>";
                        reportData += "<td>"+v.start_page_no+"</td>";
                        reportData += "<td>"+v.end_page_no+"</td>";
                        reportData += "<td>"+v.total_page_used+"</td>";
                        reportData += "<td>"+v.description+"</td>";
                        reportData += "<td style='text-align:right'>"+v.rate+"</td>";
                        reportData += "<td style='text-align:right'>"+v.actual_amount+"</td>";
                        reportData += "</tr>";
                        total=+total+ +v.actual_amount;
                    }); 
                    reportData += "<tr><th colspan='9' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total,10).toFixed(2)+"</th></tr>";  
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_receiptbook_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type},
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
        $("#type").val("");
        get_reports();
    });
    $(".pdf").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_receiptbook_pdf?from_date='+from_date+'&to_date='+to_date+'&type='+type, '_blank');       
    });
    $(".excel_report").click(function(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        var type        = $("#type").val();
        var head        = $("#head").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_receiptbook_excel?from_date='+from_date+'&to_date='+to_date+'&type='+type+'&head='+head, '_blank');       
    });
</script>
