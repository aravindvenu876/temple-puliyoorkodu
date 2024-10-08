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
    $(".pdf").hide();
    get_reports();

    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bill = $("#bill").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_nadavaravu_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,bill:bill},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        // $date_ex=$row->date;
                        // $date=date("d-m-Y", strtotime($date_ex));
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        
                        reportData += "<td>"+convert_date(v.receipt_date)+"</td>";
                        reportData += "<td>"+v.receipt_no+"</td>";
                        reportData += "<td>"+v.asset_name_eng+"</td>";
                        reportData += "<td  style='text-align:right'>"+v.quantity+"</td>";
                        reportData += "<td style='text-align:right'>"+v.receipt_amount+"</td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.phone+"</td>";
                        reportData += "</tr>";
                        total=+total+ +v.receipt_amount;
                    });  
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
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
        var bill = $("#bill").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_nadavaravu_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,bill:bill},
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
        $("#bill").val();
        get_reports();
    });
    $(".pdf").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bill = $("#bill").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_nadavaravu_pdf?from_date='+from_date+'&to_date='+to_date+'&bill='+bill, '_blank');       
    });
</script>
