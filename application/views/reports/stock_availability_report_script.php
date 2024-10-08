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
                url: '<?php echo base_url() ?>service/Asset_data/get_asset_drop_down',
                type: 'GET',
                async: false,
                success: function (data) {
                    var string = '';
                    var string = '<option value="">Select Asset Type</option>';
                    $.each(data.assets, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.asset_name + '</option>';
                    });
                    $("#type").html(string);
                }
            });
    function get_reports(){
       // alert('1');
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_stockAvailability_report',
            type: 'POST',
            data:{type:type},
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
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.name_eng+"</td>";
                        reportData += "<td>"+v.id+"</td>";
                        reportData += "<td>"+v.quantity_available+"</td>";
                        reportData += "<td>"+v.unit_eng+"</td>";
                        reportData += "</tr>";
                    });  
                }
                $("#report_body").html(reportData);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_stockAvailability_print',
            type: 'POST',
            data:{type:type},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    $(".btn_clear").click(function(){
        $("#type").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var type = $("#type").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_stockAvailability_pdf?type='+type, '_blank');       
    });
      
    
</script>
