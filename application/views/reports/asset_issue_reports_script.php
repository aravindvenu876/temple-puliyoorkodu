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
            $("#asset").html(string);
        }
        });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var asset = $("#asset").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_asset_issue_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,asset:asset},
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
                        // $date_ex=$row->date;
                        // $date=date("d-m-Y", strtotime($date_ex));
                        reportData += "<tr>";
                        reportData += "<td>"+j+"</td>";
                        reportData += "<td>"+convert_date(v.date)+"</td>";
                        reportData += "<td>"+v.asset_eng+"</td>";
                        reportData += "<td>"+v.asset_status+"</td>";
                        reportData += "<td>"+v.quantity+"</td>";
                        if(v.returned_quantity!=null){
                            reportData += "<td>"+v.returned_quantity+"</td>";
                        }
                        else{
                            reportData += "<td></td>";
                        }
                        if(v.scrapped_quantity!=null){
                            reportData += "<td>"+v.scrapped_quantity+"</td>";
                        }
                        else{
                            reportData += "<td></td>";
                        }
                        
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
        var asset = $("#asset").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_issuereport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,asset:asset},
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
        $("#asset").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_issuereport_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
</script>
