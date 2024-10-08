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
    $.ajax({
        url: '<?php echo base_url() ?>service/Transaction_head_data/get_transaction_head_drop_down1',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Category</option>';
            $.each(data.transaction_head, function (i, v) {
				if(v.type == "Income"){
					string += '<option value="' + v.id + '">'+ v.head_eng+ '</option>';
				}
            });
            $("#category").append(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var category = $("#category").val();
        var from_sec = $("#from_sec").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_mattuvarumanam_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,category:category,from_sec:from_sec},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    // $(".btn_print_html").show();
                    $(".pdf").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.date)+"</td>";
						if(v.from_section == '3'){
							reportData += "<td><?php echo $this->lang->line('mattuvarumanam'); ?> - <?php echo $this->lang->line('receipt_book'); ?></td>";
						}else{
                        	reportData += "<td>"+v.category+"</td>";
						}
						if(v.name == '0'){
							reportData += "<td></td>";
						}else{
                        	reportData += "<td>"+v.name+"</td>";
						}
						if(v.phone == '0'){
							reportData += "<td></td>";
						}else{
                        	reportData += "<td>"+v.phone+"</td>";
						}
                        reportData += "<td style='text-align:right'>"+v.amount+"</td>";
						if(v.from_section == '1'){
							reportData += "<td><b>Counter</b></td>";
						}else if(v.from_section == '2'){
							reportData += "<td><b>Admin</b></td>";
						}else{
                        	reportData += "<td><b>Receipt Book</b></td>";
						}
                        reportData += "</tr>";
                        total=+total+ +v.amount;
                    });  
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='1'></th></tr>"; 
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var category = $("#category").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_donationreport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,category:category},
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
        $("#category").val("");
        $("#from_sec").val("All");
        get_reports();
    });
    $(".pdf").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var category = $("#category").val();
        var from_sec = $("#from_sec").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_mattuvarumanam_report_pdf?from_date='+from_date+'&to_date='+to_date+'&from_sec='+from_sec+'&category='+category, '_blank');       
    });
</script>
