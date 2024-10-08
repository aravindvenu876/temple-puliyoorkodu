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
                reportData = "";
                reportData1 = "";
                reportData_pr="";
                $(".btn_print_html").show();
                $(".pdf_payslip").show();
                var j = 0;
                var total = 0;
                var total1 = 0;
                var total2 = 0;
                var totalpr = 0;
                var total1pr = 0;
                var total2pr = 0;
                var total1 = 0;
                var total11 = 0;
                var total21 = 0;
                 var total_rate1=0;
                    var total_rate2=0;
                    var total_rate3=0;
                reportData += '<thead><tr><th>Sl#</th><th>Pooja Code</th><th>Pooja</th><th>'+data.current+'</th><th>'+data.previous+'</th><th>'+data.prevYear+'</th></tr></thead>';
                $.each(data.poojas, function (i, v) {
                    j++;
                    reportData += "<tr>";
                    reportData += "<td>"+j+"</td>";
                    reportData += "<td>"+v.id+"</td>";
                    reportData += "<td>"+v.pooja_name+"</td>";
                    var rate1 = "0.00";
                    if (data.reports1.length !== 0) {
                        $.each(data.reports1, function (i1, v1) {
                            if(v1.pooja_master_id == v.id){
                                rate1 = v1.total_amount;
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
                    // total=+total+ +rate1;
                    // total1=+total1+ +rate2;
                    // total2=+total2+ +rate3;
                    var rate4 = "0.00";
                    var rate5 = "0.00";
                    var rate6 = "0.00";
                   
                $.each(data.receipt, function (i, vpr) {
                    j++;
                   // rate4 = vpr.total_amount;
                    if (data.receipt.length !== 0) {
                        $.each(data.receipt, function (i4, v2) {
                            if(v2.pooja_master_id == v.id){
                                rate4 = v2.total_amount;
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
                 });
                    reportData += "<tr>";
                    reportData += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate1,10).toFixed(2)+"</th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate2,10).toFixed(2)+"</th>";
                    reportData += "<th style='text-align:right'>"+parseFloat(total_rate3,10).toFixed(2)+"</th>";
                    reportData += "<th></th></tr>"; 
                $("#reportContent").html(reportData);
                // pradasam 
                var total_rate1pr=0;
                var total_rate2pr=0;
                var total_rate3pr=0;
                reportData_pr += '<thead><tr><th>Sl#</th><th>Prasadam Code</th><th>Pooja</th><th>'+data.current+'</th><th>'+data.previous+'</th><th>'+data.prevYear+'</th></tr></thead>';
                $.each(data.prasadam, function (i, v2) {
                    j++;
                    reportData_pr += "<tr>";
                    reportData_pr += "<td>"+j+"</td>";
                    reportData_pr += "<td>"+v2.id+"</td>";
                    reportData_pr += "<td>"+v2.name+"</td>";
                    var rate1pr = "0.00";
                    if (data.reports4.length !== 0) {
                        $.each(data.reports4, function (i1, vpr1) {
                            if(vpr1.item_master_id == v2.id){
                                rate1pr = vpr1.total_amount;
                            }
                        });
                    }
                    var rate2pr = "0.00";
                    if (data.reports5.length !== 0) {
                        $.each(data.reports5, function (i2, vpr2) {
                            if(vpr2.item_master_id == v2.id){
                                rate2pr = vpr2.total_amount;
                            }
                        });
                    }
                    var rate3pr = "0.00";
                    if (data.reports6.length !== 0) {
                        $.each(data.reports6, function (i3, vpr3) {
                            if(vpr3.item_master_id == v2.id){
                                rate3pr = vpr3.total_amount;
                            }
                        });
                    }
                    reportData_pr += "<td style='text-align:right'><b>"+rate1pr+"</b></td>";
                    reportData_pr += "<td style='text-align:right'><b>"+rate2pr+"</b></td>";
                    reportData_pr += "<td style='text-align:right'><b>"+rate3pr+"</b></td>";
                    reportData_pr += "</tr>";
                     total_rate1pr= +total_rate1pr + +rate1pr; 
                     total_rate2pr= +total_rate2pr + +rate2pr;
                     total_rate3pr= +total_rate3pr + +rate3pr;
                });
                    
                    reportData_pr += "<tr>";
                    reportData_pr += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate1pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate2pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th style='text-align:right'>"+parseFloat(total_rate3pr,10).toFixed(2)+"</th>";
                    reportData_pr += "<th></th></tr>"; 
                    $("#reportContent_pr").html(reportData_pr);
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
