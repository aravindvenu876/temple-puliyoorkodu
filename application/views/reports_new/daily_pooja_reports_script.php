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
    $(".pdf_payslip").hide();
    // get_reports();
    // $.ajax({
    //     url: '<?php echo base_url() ?>service/Reports_data/user_list',
    //     type: 'GET',
    //     success: function (data) {
    //         var string = '<option value="">Select User</option>';
    //         $.each(data.users, function (i, v) {
    //             string += '<option value="' + v.id + '">'+ v.name + '</option>';
    //         });
    //         $("#user").html(string);
    //     }
    // });
    // $.ajax({
    //     url: '<?php echo base_url() ?>service/Reports_data/counters_list',
    //     type: 'GET',
    //     success: function (data) {
    //         var string = '<option value="">Select Counter</option>';
    //         $.each(data.counters, function (i, v) {
    //             string += '<option value="' + v.id + '">'+ v.counter_no + '</option>';
    //         });
	// 		string += '<option value="Web">Web Booking</option>';
    //         $("#counter").html(string);
    //     }
    // });
    $.ajax({
        url: '<?php echo base_url() ?>service/Pooja_data/pooja_drop_down_with_all_poojas',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Pooja</option>';
			var currentTemple = '<?php echo $this->session->userdata('temple') ?>';
            $.each(data.pooja, function (i, v) {
				if(currentTemple == 1){
					if(v.temple_id == currentTemple){
                		string += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
					}else{
                		string += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
                		// string += '<option value="' + v.id + '">'+ v.pooja_name + '(' + v.temple + ')</option>';
					}
				}else{
					if(currentTemple == v.temple_id){
                		string += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
					}
				}
            });
            $("#pooja").html(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        // var counter = $("#counter").val();
        // var user = $("#user").val();
        var pooja = $("#pooja").val();
        $("#report_body").html('<tr><td colspan="7" style="text-align:center"><b>...Please Wait...</b></td></tr>');
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_daily_pooja_report',
            type: 'POST',
            // data:{from_date:from_date,to_date:to_date,counter:counter,user:user,pooja:pooja},
            data:{from_date:from_date,to_date:to_date,pooja:pooja},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_payslip").hide();
                    reportData += '<tr><td colspan="7" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_payslip").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        reportData += "<tr>";
                        // reportData += "<td>"+j+"</td>";
                        // reportData += "<td>"+convert_date(v.receipt_date)+"</td>";
                        // reportData += "<td>"+convert_date(v.date)+"</td>";
                        // reportData += "<td>"+v.pooja+"</td>";
                        // reportData += "<td>"+v.star+"</td>";
                        // reportData += "<td>"+v.pooja_type+"</td>";
                        // reportData += "<td>"+v.receipt_no+"</td>";
                        // reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                        // reportData += "<td>"+v.name+"</td>";                      
                        // if(v.phone==null){
                        //     reportData += "<td></td>";
                        // }else{
                        //     reportData += "<td>"+v.phone+"</td>";
                        // }
                        // reportData += "<td>"+v.user_name+"</td>";
                        // reportData += "<td>"+v.pos_counter_id+"</td>";
                        
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.pooja+"</td>";
                        reportData += "<td>"+v.name+"</td>";                      
                        reportData += "<td>"+v.star+"</td>";
                        reportData += "<td style='text-align:right'>"+v.rate+"</td>";
                        reportData += "<td style='text-align:right'>"+v.quantity+"</td>";
                        reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                        reportData += "</tr>";
                        total = +total + +v.amount;
                    });  
                    reportData += "<tr><th colspan='6' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total,10).toFixed(2)+"</th></tr>";
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
        var pooja = $("#pooja").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_daily_pooja_report_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,counter:counter,user:user,pooja:pooja},
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
        $("#pooja").val("");
        get_reports();
    });

    $(".pdf_payslip").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var counter = $("#counter").val();
        var user = $("#user").val();
        var pooja = $("#pooja").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_pooja_report_pdf?from_date='+from_date+'&to_date='+to_date+'&counter='+counter+'&user='+user+'&pooja='+pooja, '_blank');       
    });
</script>
