<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
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
            url: '<?php echo base_url() ?>service/Reports_data/get_collection_report',
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
                    var total = 0.00;
                   
                    $.each(data.report, function (i, v) {
                        j++;
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.receipt_no+"</td>";
                        reportData += "<td>"+v.receipt_type+"</td>";
                        reportData += "<td>"+v.receipt_status+"</td>";
                        reportData += "<td>"+convert_date(v.receipt_date)+"</td>";
                        reportData += "<td><span class='amntRight'>"+v.receipt_amount+"</span></td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.counter_no+"</td>";
                        reportData += "</tr>";
                        total = +total + +v.receipt_amount;
                     //   var dec="";
                      
                    }); 
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='5'></th></tr>";
                    if(data.session_data != ""){
                        reportData += "<tr>";
                        reportData += "<th colspan='2' style='text-align:left'><?php echo $this->lang->line('session_started_on'); ?></th>";
						if(data.session_data.start == null){
							reportData += "<th colspan='2' style='text-align:left'></th>";
						}else{
                        	reportData += "<th colspan='2' style='text-align:left'>"+data.session_data.start+"</th>";
						}
                        reportData += "<th colspan='2' style='text-align:left'><?php echo $this->lang->line('session_ended_on'); ?></th>";
						if(data.session_data.end == null){
							reportData += "<th colspan='2' style='text-align:left'></th>";
						}else{
                        	reportData += "<th colspan='2' style='text-align:left'>"+data.session_data.end+"</th>";
						}
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
            url: '<?php echo base_url() ?>service/Reports_data/get_collection_report_print',
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
        window.open('<?php echo base_url() ?>service/Reports_data/get_collection_report_pdf?from_date='+from_date+'&to_date='+to_date+'&counter='+counter+'&user='+user, '_blank');       
    });
</script>
