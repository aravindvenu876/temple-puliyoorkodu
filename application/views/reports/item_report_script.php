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
                url: '<?php echo base_url() ?>service/Item_register_data/get_item_drop_down',
                type: 'GET',
                async: false,
                success: function (data) {
                    var string = '';
                    var string = '<option value="">Select Item Type</option>';
                    $.each(data.item, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.name + '</option>';
                    });
                    $("#type").html(string);
                }
            });
    function get_reports(){
       // alert('1');
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_itemAvailability_report',
            type: 'POST',
            data:{type:type},
            success: function (data) {
                reportData = "";
                if (data.report.length === 0) {
                    $(".btn_print_html").hide();
                    $(".pdf_report").hide();
                    reportData += '<tr><td colspan="20" style="text-align:center"><b>No Records Found</b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    var j = 0;
                    var total = 0;
                    $.each(data.report, function (i, v) {
                        j++;
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+v.item_eng+"</td>";
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
        var type = $("#type").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_itemAvailability_print',
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
        window.open('<?php echo base_url() ?>service/Reports_data/get_itemAvailability_pdf?type='+type, '_blank');       
    });
      
    
</script>
