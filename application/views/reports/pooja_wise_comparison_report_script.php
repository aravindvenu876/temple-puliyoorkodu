<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: "MM-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        todayHighlight: true,
        autoclose: true
    });
    $(".btn_print_html").hide();
    $(".pdf_payslip").hide();
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var reportDate = $("#date").val();
        var reportDate1 = $("#date").val();
        var reportData_pr = $("#date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_pooja_comparison_reports',
            type: 'POST',
            data:{date:reportDate},
            success: function (data) {
                $(".pdf_payslip").show();
                reportData = "";
                var j = 0;
                var total = 0;
                var total1 = 0;
                var total2 = 0;
                var total_pr = 0;
                var total1_pr = 0;
                var total2_pr = 0;
                var total1 = 0;
                var total11 = 0;
                var total21 = 0;
                var total_rate1=0;
                var total_rate2=0;
                var total_rate3=0;
                var  total_rate1_pr= 0; 
                var  total_rate2_pr= 0;
                var total_rate3_pr= 0;
                reportData += '<thead><tr><th>Sl#</th><th>Pooja Code</th><th>Pooja</th><th>'+data.current+'</th><th>'+data.previous+'</th><th>'+data.prevYear+'</th></tr></thead>';
                $.each(data.poojas, function (i, v) {
                    j++; 
					var rate1=0;
                    reportData += "<tr>";
                    reportData += "<td>"+j+"</td>";
                    reportData += "<td>"+v.id+"</td>";
                    reportData += "<td>"+v.pooja_name+"</td>";
                    var rate1 = "0.00";
                 //   rate1 = v.amount;
                    if (data.reports1.length !== 0) {
                        $.each(data.reports1, function (i2, v0) {
                            if(v0.pooja_master_id == v.id){
                                rate1 = v0.total_amount;
                            }
                        });
                    }
                     var rate2 = "0.00";
                    if (data.reports2.length !== 0) {
                        $.each(data.reports2, function (i2, v2) {
                            if(v2.pooja_master_id == v.id){
                                rate2 = v2.total_amount;
                            }
                        });
                    }
                    var rate3 = "0.00";
                    if (data.reports3.length !== 0) {
                        $.each(data.reports3, function (i3, v3) {
                            if(v3.pooja_master_id == v.id){
                                rate3 = v3.total_amount;
                            }
                        });
                    }
                    var rate4 = "0.00";
                    var rate5 = "0.00";
                    var rate6 = "0.00";
                    $.each(data.receipt, function (i, vpr) {
                   // rate4 = vpr.total_amount;
                    if (data.receipt.length !== 0) {
                        $.each(data.receipt, function (i4, v22) {
                            if(v22.pooja_master_id == v.id){
                                rate4 = v22.total_amount;
                            }
                        });
                    }
                    if (data.receipt1.length !== 0) {
                        $.each(data.receipt1, function (i4, v21) {
                            if(v21.pooja_master_id == v.id){
                                rate5 = v21.total_amount;
                            }
                        });
                    }
                    
                    if (data.receipt2.length !== 0) {
                        $.each(data.receipt2, function (i5, v31) {
                            if(v31.pooja_master_id == v.id){
                                rate6 = v31.total_amount;
                            }
                        });
                    }
                 
                   });
                    total1=+rate1+ +rate4;
                    total11=+rate2+ +rate5;
                    total21=+rate3+ +rate6;
                    reportData += "<td style='text-align:right'><b>"+parseFloat(total1,10).toFixed(2)+"</b></td>";
                    reportData += "<td style='text-align:right'><b>"+parseFloat(total11,10).toFixed(2)+"</b></td>";
                    reportData += "<td style='text-align:right'><b>"+parseFloat(total21,10).toFixed(2)+"</b></td>";
                    reportData += "</tr>";
                    total_rate1= +total_rate1 + +total1; 
                    total_rate2= +total_rate2 + +total11;
                    total_rate3= +total_rate3 + +total21;

                    // reportData += "<td style='text-align:right'>"+v.count+"</td>";
                    // reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                    // reportData += "</tr>";
                    // total= +total+ +v.amount;
                });
                    reportData += "<tr>";
                    reportData += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate1,10).toFixed(2)+"</th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate2,10).toFixed(2)+"</th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate3,10).toFixed(2)+"</th>";
                    reportData += "<th></th></tr>"; 
                $("#reportContent").html(reportData);
                // prasadam start
                
                reportData_pr += '<thead><tr><th>Sl#</th><th>Prasadam Code</th><th>Prasadam</th><th>'+data.current+'</th><th>'+data.previous+'</th><th>'+data.prevYear+'</th></tr></thead>';
				$.each(data.prasadam, function (i, v_pr) {
					j++;
                    reportData_pr += "<tr>";
                    reportData_pr += "<td>"+j+"</td>";
                    reportData_pr += "<td>"+v_pr.id+"</td>";
                    reportData_pr += "<td>"+v_pr.name+"</td>";
                    var rate1_pr = "0.00";
                 //   rate1 = v.amount;
                    if (data.reports4.length !== 0) {
                        $.each(data.reports4, function (i2, v0_pr) {
                            if(v0_pr.item_master_id == v_pr.id){
                                rate1_pr = v0_pr.total_amount;
                            }
                        });
                    }
                     var rate2_pr = "0.00";
                    if (data.reports5.length !== 0) {
                        $.each(data.reports5, function (i2, v2_pr) {
                            if(v2_pr.item_master_id == v_pr.id){
                                rate2_pr = v2_pr.total_amount;
                            }
                        });
                    }
                    var rate3_pr = "0.00";
                    if (data.reports6.length !== 0) {
                        $.each(data.reports6, function (i3, v3_pr) {
                            if(v3.item_master_id == v_pr.id){
                                rate3_pr = v3_pr.total_amount;
                            }
                        });
                    }
                    var rate4_pr = "0.00";
                    var rate5_pr = "0.00";
                    var rate6_pr = "0.00";
                    $.each(data.receipt_pr, function (i, vpr1) {
                  
                   // rate4 = vpr.total_amount;
                    if (data.receipt_pr.length !== 0) {
                        $.each(data.receipt_pr, function (i4, v22_pr) {
                            if(v22_pr.item_master_id == v_pr.id){
                                rate4_pr = v22_pr.total_amount;
                            }
                        });
                    }
                    if (data.receipt1_pr.length !== 0) {
                        $.each(data.receipt1_pr, function (i4, v21_pr) {
                            if(v21_pr.item_master_id == v_pr.id){
                                rate5_pr = v21_pr.total_amount;
                            }
                        });
                    }
                    
                    if (data.receipt2_pr.length !== 0) {
                        $.each(data.receipt2_pr, function (i5, v31_pr) {
                            if(v31_pr.item_master_id == v_pr.id){
                                rate6_pr = v31_pr.total_amount;
                            }
                        });
                    }
                 
                   });
                    total1_pr=+rate1_pr+ +rate4_pr;
                    total11_pr=+rate2_pr+ +rate5_pr;
                    total21_pr=+rate3_pr+ +rate6_pr;
                    reportData_pr += "<td style='text-align:right'><b>"+parseFloat(rate1_pr,10).toFixed(2)+"</b></td>";
                    reportData_pr += "<td style='text-align:right'><b>"+parseFloat(rate2_pr,10).toFixed(2)+"</b></td>";
                    reportData_pr += "<td style='text-align:right'><b>"+parseFloat(rate3_pr,10).toFixed(2)+"</b></td>";
                    reportData_pr += "</tr>";
                    total_rate1_pr= +total_rate1_pr + +total1_pr; 
                    total_rate2_pr= +total_rate2_pr + +total11_pr;
                    total_rate3_pr= +total_rate3_pr + +total21_pr;

                    // reportData += "<td style='text-align:right'>"+v.count+"</td>";
                    // reportData += "<td style='text-align:right'>"+v.amount+"</td>";
                    // reportData += "</tr>";
                    // total= +total+ +v.amount;
                });
                    reportData_pr += "<tr>";
                    reportData_pr += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate1_pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate2_pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate3_pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th></th></tr>"; 
                $("#reportContent_pr").html(reportData_pr);
                // prasadam end
            }
        });

    }
    $(".btn_print_html").click(function(){
        var reportDate = $("#date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_pooja_comparison_reports_print',
            type: 'POST',
            data:{date:reportDate},
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
        var reportDate = $("#date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_pooja_comparison_reports_pdf?date='+reportDate, '_blank');       
    });
</script>
