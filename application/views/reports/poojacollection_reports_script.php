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
        url: '<?php echo base_url() ?>service/Pooja_category_data/get_pooja_category_drop_down1',
        type: 'GET',
        success: function (data) {
            
            var string = '<option value="">Select Category</option>';
            $.each(data.pooja_category, function (i, v) {
                if(v.temple_id==2){
                var temple_name='('+v.temple+')';
                   }else  if(v.temple_id==3)
                   {
                    var temple_name='('+v.temple+')';
                   }
                   else{
                    var temple_name="";
                   }
                string += '<option value="' + v.id + '">'+ v.category+temple_name+'</option>';

            });
            $("#type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Pooja_data/get_pooja_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Pooja</option>';
            $.each(data.pooja, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.pooja_name_eng + '</option>';
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
        var type = $("#type").val();
        var pooja = $("#pooja").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,pooja:pooja},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf").show();
                    var j = 0;
                    var  total_amount =0.00;
                    var last_id = "";
                    var last_category = "";
                    var total_category_amount = 0.00;
                    $.each(data.report, function (i, v) {
                        j++; 
                        if(j == 1){
                            last_id = v.pooja_category_id;
                            last_category = v.category_alt;
                        }
                        total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                        if(last_id != v.pooja_category_id){
                            reportData += "<tr>";
                            reportData += "<th colspan='5' style='text-align:right'>Total</th>";                           
                            reportData += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                            reportData += "</tr>";
                            total_category_amount = "0.00";
                        }
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        if(v.count == "0"){
                            totalr = v.amount;
                            //  if(v.temple_id==2){
                            //     var temple='('+v.temple+')';
                            // }
                            // else if(v.temple_id==3){
                            //     var temple='('+v.temple+')';
                            // }else{ var temple="";}
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData += "<tr>";
                            reportData += "<td>"+j+"</td>";
                            reportData += "<td>"+v.category+"</td>";
                            reportData += "<td>"+v.pooja_name+"(Receipt Book)</td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'></span></td>";
                            reportData += "<td></td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            var temple="";
                            if(v.temple_id==2){
                                var temple="(ചൊവ്വാഴ്‌ചക്കാവ്)" ;
                                }else if(v.temple_id==3){
                                var temple="(മാതംപിള്ളി)";
                                }else{var temple="";}
                            reportData += "<tr>";
                            reportData += "<td>"+j+"</td>";
                            reportData += "<td>"+v.category_alt+temple+"</td>";
                            reportData += "<td>"+v.pooja_name_alt+"</td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
                            reportData += "<td>"+v.count+"</td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
                            reportData += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                        total_category_amount = +total_category_amount + +totalr;
                        last_id = v.pooja_category_id;
                    });  
                    total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'>Total</th>";                           
                    reportData += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                    reportData += "</tr>";
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right'>"+total_rate+"</th>";
                    reportData += "<th colspan='6'></th></tr>";
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var pooja = $("#pooja").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,pooja:pooja},
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
        $("#pooja").val("");
        $("#type").val("");
        get_reports();
    });

    $(".pdf").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var pooja = $("#pooja").val();
        var type = $("#type").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_poojawise_pdf?from_date='+from_date+'&to_date='+to_date+'&type='+type+'&pooja='+pooja, '_blank');       
    });
</script>
