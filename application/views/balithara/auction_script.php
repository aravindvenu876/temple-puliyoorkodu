<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [1],
        "mData": 'balithara',
        "mRender": function(data, type, row) {
            return data;
        }
    },{
        "aTargets": [6],
        "mData": 'start_date',
        "mRender": function(data, type, row) {
            return data;
        }
    },{
        "aTargets": [7],
        "mData": 'end_date',
        "mRender": function(data, type, row) {
            return data;
        }
    }, {
        "aTargets": [8],
        "mRender": function(data, type, row) {
            // return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = 'Edit Data'>" + "<i class='fa fa-edit '></i>" + "</a>" 
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>";
        }
    }];
    var action_url = $('#balithara_auction_master').attr('action_url');
    oTable = gridSFC('balithara_auction_master', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#balithara_auction_master").dataTable().fnDraw();
    }
    $('#filter_from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        StartDate:0
    // }).on('changeDate', function (selected) {
    //     var minDate = new Date(selected.date.valueOf());
    //     $('#filter_to_date').datepicker('setStartDate', minDate);
    });
    // $('#filter_from_date').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true,
    //     StartDate:0
    // }).on('changeDate', function (selected) {
    //     var minDate = new Date(selected.date.valueOf());
    //     $('#filter_to_date').datepicker('setStartDate', minDate);
    // });
    // $('#filter_to_date').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true
    // }).on('changeDate', function (selected) {
    //     var maxDate = new Date(selected.date.valueOf());
    //     $('#filter_from_date').datepicker('setEndDate', maxDate);
    // });
    $.ajax({
        url: '<?php echo base_url() ?>service/Balithara_data/get_balithara_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Balithara</option>';
            $.each(data.balitharas, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#filter_balithara").html(string);
        }
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#from_date').datepicker({
        format: "MM-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        todayHighlight: true,
        startDate: today,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: "MM-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    
    detail('<?php echo base_url() ?>service/Balithara_data/balithara_details_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Balithara_data/balithara_details_edit', function(data) {
        detail_view(data);
    });
    $(".date").change(function(){
        if($("#from_date").val() != "" && $("#to_date").val() != ""){
            get_available_balitharas();
        }
    });
    function get_available_balitharas(){
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url() ?>service/Balithara_data/get_balithara_for_auction_drop_down',
            type: 'POST',
            data:{from_date:$("#from_date").val(),to_date:$("#to_date").val()},
            async:false,
            success: function (data) {
                $("#balithara").html("");
                var string = '<option value="">Select Balithara</option>';
                $.each(data.balithara, function (i, v) {
                    if(v.current_status == 0){
                        string += '<option value="' + v.id + '">'+ v.name + '</option>';
                    }
                });
                $("#balithara").html(string);
            }
        });
        $(".load").hide();
    }
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_balithara'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/balithara_update");
        $('#name_eng').val(data.editData.name_eng);
        $('#name_alt').val(data.editData.name_alt);
        $('#description_eng').val(data.editData.description_eng);
        $('#description_alt').val(data.editData.description_alt);
        $('#type').val(data.editData.type);
        $('#monthly_rent').val(data.editData.monthly_rate);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data){
       // console.log(data);
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>"+data.main.name+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>"+data.main.phone+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>"+data.main.address+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('status'); ?></th>";
        viewdata += "<td>"+data.main.status+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('start_date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.start_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('end_date'); ?></th>";
        viewdata += "<td>"+data.main.end_date_format+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th>Month & Year</th><th><?php echo $this->lang->line('due_date'); ?></th><th> <?php echo $this->lang->line('amount'); ?></th><th><?php echo $this->lang->line('status'); ?></th><th><?php echo $this->lang->line('paid_on'); ?></th><th><?php echo $this->lang->line('receipt_no'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        var receipt_no = "";
        $.each(data.details, function (i, v) {
            j++;
            if(v.receipt_no == null){
                receipt_no = "";
            }else{
                receipt_no = v.receipt_no;
            }
            viewData1 += "<tr><td>"+j+"</td><td>"+v.pay_date+"</td><td>"+convert_date(v.due_date)+"</td><td><span class='amntRight'>₹ "+v.amount+"</span></td><td>"+v.status+"</td><td>"+convert_date(v.paid_on)+"</td><td>"+receipt_no+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }

    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_balithara_auction'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/balithara_auction_add");
        clear_form();
    });

</script>
