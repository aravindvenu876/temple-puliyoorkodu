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
        url: '<?php echo base_url() ?>service/Reports_data/bank_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Bank name</option>';
            $.each(data.bank, function (i, v) {
                string += '<option value="' + v.bank_eng + '">'+ v.bank_eng + '</option>';
            });
            $("#bank_name").html(string);
        }
    });
     $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_bank_transaction_filter_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Transaction Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
			string += '<option value="EXPENSE WITHDRAWAL">EXPENSE WITHDRAWAL</option>';
            $("#type").append(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
       // alert('1');
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bank_name = $("#bank_name").val();
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_banktransaction_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,bank_name:bank_name,type:type},
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
                        reportData += "<td>"+convert_date(v.date)+"</td>";
                        reportData += "<td>"+v.bank_eng+"</td>";
                        reportData += "<td>"+v.type+"</td>";
                        reportData += "<td  style='text-align:right'>"+v.amount+"</td>";
                        reportData += "</tr>";
                        total = +total + +v.amount; 
                    }); 
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='4' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='5'></th></tr>";
                   
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bank_name = $("#bank_name").val();
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_bank_report_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,bank_name:bank_name,type:type},
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
        $("#bank_name").val("");
        $("#type").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bank_name = $("#bank_name").val();
        var type = $("#type").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_bank_report_pdf?from_date='+from_date+'&to_date='+to_date+'&bank_name='+bank_name+'&type='+type, '_blank');       
    });
    $(".excel_report").click(function(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        var bank_name   = $("#bank_name").val();
        var type        = $("#type").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_bank_report_excel?from_date='+from_date+'&to_date='+to_date+'&bank_name='+bank_name+'&type='+type, '_blank');       
    });
</script>
