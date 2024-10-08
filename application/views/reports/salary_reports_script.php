<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    $(".buttonExcel").hide();
    $(".pdf_report").hide();
    $(".btn_print_html").hide();
    getProcessedSalaryData();
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_year_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option>Select Year</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#year").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_month_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option>Select Month</option>';
            var j = 0;
            $.each(data.data, function (i, v) {
                j++;
                string += '<option value="' + j + '">'+ v.name + '</option>';
            });
            $("#month").append(string);
        }
    });
    $(".getData").click(function(){
        getProcessedSalaryData();
    });
    function getProcessedSalaryData(){
        var month = $("#month").val();
        var year  = $("#year").val();
        if(month != "" && year != ""){
            $("#dynamic_asset_register").html("<tr><td colspan='7' style='text-align:center'><b>LOADING...</b><td></tr>");
            $.ajax({
                url: '<?php echo base_url() ?>service/Salary_data/get_salary_report',
                type: 'POST',
                data:{month:month,year:year},
                success: function (data) {
                    var output = "";
                    var k = 0; 
                    var total=0;
                    if(data.length == ""){
                        output += "<tr><td colspan='7' style='text-align:center'><b>No Records Found</b><td></tr>";
                      
                        $(".buttonExcel").hide();
                        $(".pdf_report").hide();
                       $(".btn_print_html").hide();
                    }else{
                        $.each(data, function (i, v) {
                            k++;
                            output += "<tr>";
                            output += "<td>"+k+"</td>";
                            output += "<td>"+v.name+"</td>";
                            output += "<td style='text-align:right'>"+v.payable_salary+"</td>";
                            output += "<td>"+v.bank+"</td>";
                            output += "<td>"+v.account_no+"</td>";
                            output += "<td>"+v.ifsc_code+"</td><td>"+convert_date(v.date)+"</td>";
                            output += "</tr>";
                            total=+total+ +v.payable_salary;
                        });
                        var total_rate= parseFloat(total,10).toFixed(2); 
                        output += "<tr>";
                        output += "<th colspan='2' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                        output += "<th style='text-align:right'>"+total_rate+"</th>";
                        output += "<th colspan='4'></th></tr>";  
                        $(".buttonExcel").show();
                        $(".pdf_report").show();
                       $(".btn_print_html").show();
                    }
                    $("#dynamic_asset_register").html(output);
                }
            });
        }else{
            bootbox.alert("Please select a year and month");
        }
    }
    $(".btn_print_html").click(function(){
        var month = $("#month").val();
        var year  = $("#year").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_salary_report_print',
            type: 'POST',
            data:{month:month,year:year},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    function getProcessedSalaryDataExcel(){
        var month = $("#month").val();
        var year  = $("#year").val();
        window.open('<?php echo base_url() ?>service/Salary_data/get_salary_report_excel?month='+month+'&year='+year, '_blank'); 
    }
    $(".btn_clear").click(function(){
        $("#month").val("");
        $("#year").val("");
        getProcessedSalaryData();
    });
    $(".pdf_report").click(function(){
        var month = $("#month").val();
        var year  = $("#year").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_salary_report_pdf?month='+month+'&year='+year, '_blank');       
    });
</script>