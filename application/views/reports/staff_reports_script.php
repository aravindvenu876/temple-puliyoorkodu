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
        url: '<?php echo base_url() ?>service/Staff_designation_data/get_designation_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Designation</option>';
            $.each(data.designation, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.designation + '</option>';
            });
            $("#designation").append(string);
        }
    });
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var designation = $("#designation").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_staff_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,designation:designation},
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
                        reportData += "<td>"+v.staff_id+"</td>";
                        reportData += "<td>"+v.name+"</td>";
                        reportData += "<td>"+v.phone+"</td>";
                        reportData += "<td>"+v.designation_eng+"</td>";
                        reportData += "<td>"+v.type+"</td>";
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
        var designation = $("#designation").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_staffreport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,designation:designation},
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
        $("#designation").val("");
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var designation = $("#designation").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_staffreport_pdf?from_date='+from_date+'&to_date='+to_date+'&designation='+designation, '_blank');       
    });
</script>
