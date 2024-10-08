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
	$("#type").change(function(){
		$.ajax({
			url: '<?php echo base_url() ?>service/Transaction_head_data/get_transaction_head_drop_down',
			type: 'POST',
			data: {type: $("#type").val()},
			success: function (data) {
				var string = '<option value="">--Select--</option>';
				$.each(data.transaction_head, function (i, v) {
					string += '<option value="' + v.id + '">'+ v.head_eng + '</option>';
				});
				$("#head").html(string);
			}
		});
	});
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var head = $("#head").val();
        var name = $("#name").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_expense_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,head:head,name:name},
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
                        if(v.voucher_id=="0"){
                            reportData += "<td>No Voucher Generated</td>";
                        }else if(v.voucher_id=="-1"){
                            reportData += "<td>No Voucher </td>";
                        }else{
                            reportData += "<td>"+v.voucher_id+"</td>";
                        }
                        reportData += "<td>"+v.head_eng+"</td>";
                        reportData += "<td>"+v.transaction_type+"</td>";
                        reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                        reportData += "<td>"+v.payment_type+"</td>";
                        reportData += "<td>"+v.description+"</td>";
                        reportData += "<td>"+v.name+","+v.address+"</td>";
                        reportData += "</tr>";
                        total = +total + +v.amount;
                    });  
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total).toFixed(2);+"</th>";
                    reportData += "<td colspan='3'></td>";
                    reportData += "</td>";
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var head = $("#head").val();
        var name = $("#name").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_expense_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,head:head,name:name},
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
        $("#name").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var head = $("#head").val();
        var name = $("#name").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_expense_report_pdf?from_date='+from_date+'&to_date='+to_date+'&type='+type+'&head='+head+'&name='+name, '_blank');       
    });
    $(".excel_report").click(function(){
        var from_date   = $("#from_date").val();
        var to_date     = $("#to_date").val();
        var type        = $("#type").val();
        var head        = $("#head").val();
        var name = $("#name").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_expense_report_excel?from_date='+from_date+'&to_date='+to_date+'&type='+type+'&head='+head+'&name='+name, '_blank');       
    });
</script>
