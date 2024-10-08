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
    $.ajax({
            url: '<?php echo base_url() ?>service/Purchase_data/get_name_drop_down',
            type: 'GET',
            async:false,
            success: function (data) {
                var string = '<option value="">Select Supplier</option>';
                $.each(data.name, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.name + '</option>';
                });
                $("#name").html(string);
            }
        });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var name = $("#name").val();
        var bill = $("#bill").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_purchase_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,name:name,bill:bill},
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
					var discount = 0;
					var net = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        rate= parseFloat(v.total_rate,10).toFixed(2);
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.purchase_date)+"</td>";
                        reportData += "<td>"+v.purchase_bill_no+"</td>";
                        reportData += "<td>"+v.supplier_name+"</td>";
                        reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                        reportData += "<td style='text-align:right'>"+v.discount+"</td>";
                        reportData += "<td style='text-align:right'>"+v.net+"</td>";
                        reportData += "</tr>";
						reportData += "<tr>";
                        reportData += "<th></th>";
                        reportData += "<th><?php echo $this->lang->line('sl'); ?></th>";
                        reportData += "<th><?php echo $this->lang->line('asset_name'); ?></th>";
                        reportData += "<th><?php echo $this->lang->line('rate'); ?></th>";
                        reportData += "<th><?php echo $this->lang->line('quantity'); ?></th>";
                        reportData += "<th><?php echo $this->lang->line('amount'); ?></th>";
                        reportData += "<th></th>";
                        reportData += "</tr>";
						var k = 0;
						$.each(v.details, function (i1, v1) {
							k++;
							reportData += "<tr>";
							reportData += "<td></td>";
							reportData += "<td>"+k+"</td>";
							reportData += "<td>"+v1.asset_name+"</td>";
							reportData += "<td style='text-align:right'>"+parseFloat(v1.unit_rate,10).toFixed(2)+"</td>";
							reportData += "<td style='text-align:right'>"+v1.quantity+"</td>";
							reportData += "<td style='text-align:right'>"+parseFloat(v1.total_rate,10).toFixed(2)+"</td>";
							reportData += "<td></td>";
							reportData += "</tr>";
						});
                        total = +total + +v.net; 
                    });  
                    var total_rate= parseFloat(total,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='6' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th></tr>";
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var name = $("#name").val();
        var bill = $("#bill").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_purchasereport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,name:name,bill:bill},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    $(".btn_clear").click(function(){
     //   alert(1);
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>");
        $("#bill").val("");
        $("#name").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_purchasereport_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
</script>
