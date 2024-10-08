<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    $(".buttonExcel").hide();
    $(".pdf_report").hide();
    $(".btn_print_html").hide();
    get_salary_advance();
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_year_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Year</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#filter_year").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_month_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Month</option>';
            var j = 0;
            $.each(data.data, function (i, v) {
                j++;
                string += '<option value="' + j + '">'+ v.name + '</option>';
            });
            $("#filter_month").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
           
            $("#filter_staff").html(string);
        }
    });
    $(".getData").click(function(){
        get_salary_advance();
    });
    function get_salary_advance(){
        var filter_year = $("#filter_year").val();
        var filter_month  = $("#filter_month").val();
        var filter_staff  = $("#filter_staff").val();
       // alert(filter_year);
            $.ajax({
                url: '<?php echo base_url() ?>service/Reports_data/get_salary_advreport',
                type: 'POST',
                data:{filter_year:filter_year,filter_month:filter_month,filter_staff:filter_staff},
                success: function (data) {
                    var output = "";
                    var k = 0;
                    var total=0;
                    if(data.report.length == ""){
                        output += "<tr><td colspan='7' style='text-align:center'><b>No Records Found</b><td></tr>";
                        $(".buttonExcel").hide();
                        $(".pdf_report").hide();
                        $(".btn_print_html").hide();
                    }else{
                        $.each(data.report, function (i, v) {
                            i++;
                            output += "<tr>";
                            output += "<td>"+i+"</td>";
                            output += "<td>"+v.name+"</td>";
                            output += "<td>"+convert_date(v.date)+"</td>";
                            output += "<td style='text-align:right'>"+v.amount+"</td>";
                            output += "<td>"+v.type+"</td>";
                           
                            output += "<td>"+v.description+"</td>";
                            if(v.processed_salary_id==null){
                            output += "<td></td>";
                            }else{
                            output += "<td>"+v.processed_salary_id+"</td>";
                            }
                            output += "<td>"+v.created_on+"</td>";
                            output += "</tr>";
                            total=+total+ +v.amount;
                        });
                        var total_rate= parseFloat(total,10).toFixed(2); 
                        output += "<tr>";
                        output += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                        output += "<th style='text-align:right'>"+total_rate+"</th>";
                        output += "<th colspan='8'></th></tr>"; 
                        
                        $(".buttonExcel").show();
                        $(".pdf_report").show();
                       $(".btn_print_html").show();
                    }
                    $("#report_body").html(output);
                }
            });
        
    }
    $(".btn_print_html").click(function(){
        var filter_staff = $("#filter_staff").val();
        var filter_year = $("#filter_year").val();
        var filter_month = $("#filter_month").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_salary_advreport_print',
            type: 'POST',
            data:{filter_year:filter_year,filter_month:filter_month,filter_staff:filter_staff},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    function get_salary_advance_excel(){
        var staff = $("#filter_staff").val();
        var year = $("#filter_year").val();
        var month = $("#filter_month").val();
        window.open('<?php echo base_url() ?>service/Salary_data/get_salary_advance_excel?staff='+staff+'&year='+year+'&month='+month, '_blank'); 
    }
    $(".btn_clear").click(function(){
        $("#filter_staff").val("");
        $("#filter_year").val("");
        $("#filter_month").val("");
        get_salary_advance()
    });
    $(".pdf_report").click(function(){
        var filter_staff = $("#filter_staff").val();
        var filter_year = $("#filter_year").val();
        var filter_month = $("#filter_month").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_salary_advreportpdf?filter_staff='+filter_staff+'&filter_year='+filter_year+'&filter_month='+filter_month, '_blank');       
    });
</script>