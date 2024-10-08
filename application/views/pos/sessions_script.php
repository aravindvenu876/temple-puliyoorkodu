<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
			"aTargets": [6],"mData": 6,"mRender": function(data, type, row) {
				return "<span class='amntRight'>"+data+"</span>";
			}
		},{
            "aTargets": [9],"mData": 4,"mRender": function(data, type, row) {
                if(data == "Initiated"){
                    return "<a class='btn btn-warning btn-sm cancel_session btn_active'>Cancel Session</a>";
                }else if(data == "Started" || data == "Ended" || data == "Confirmed"){
                    return "<a class='btn btn-danger btn-sm show_receipts btn_active'>Show Receipts</a>";
                }else{
                    return "";
                }
            }
        },{
            "aTargets": [10],"mData": 4,"mRender": function(data, type, row) {
                if(data == "Ended"){
                    return "<a class='btn btn-primary btn-sm confirm_session btn_active'>Confirm Counter Session</a>";
                }else if(data == "Started"){
                    return "<a class='btn btn-primary btn-sm end_session btn_active'>End Counter Session</a>";
                }else if(data == "Confirmed"){
                    return "Session End Confirmed";
                }else{
                    return "Please wait for session to end";
                }
            }
        },{
            "aTargets": [11],"mData": 4,"mRender": function(data, type, row) {
                var char = "";
                if(data == "Initiated"){
                    char += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>"+
                        "<i class='fa fa-edit '></i>"+
                        "</a>"
                }
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'>"+
                    "<i class='fa fa-eye' aria-hidden='true'></i>" +
                    "</a>" + char;
            }
        },{
            "aTargets": [12],"mData": 10,"mRender": function(data, type, row) {
               return data;
            },
            "bVisible": false
        }
    ];
    var action_url = $('#counter_sessions').attr('action_url');
    oTable = gridSFC('counter_sessions', action_url, aoColumnDefs);
    $("#date").datepicker({
        format: 'dd-mm-yyyy',
        minDate: 0,
        todayHighlight: true,
        autoclose: true
    });
    get_counters_list();
    function get_counters_list(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/POS_data/get_counters_for_session_drop_down_new',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Counter</option>';
                $.each(data.counters, function (i, v) {
                    if(v.id == val){
                        string += '<option value="' + v.id + '" selected>'+ v.counter_no + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.counter_no + '</option>';
                    }
                });
                $("#counter").html(string);
            }
        });
    }
    get_users_list();
    function get_users_list(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/POS_data/get_users_for_session_drop_down_new',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Counter User</option>';
                $.each(data.users, function (i, v) {
                    if(v.id == val){
                        string += '<option value="' + v.id + '" selected>'+ v.name + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.name + '</option>';
                    }
                });
                $("#user").html(string);
            }
        });
    } 
    detail('<?php echo base_url() ?>service/POS_data/counter_detail', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/POS_data/counter_detail', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_counter_session'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/POS_data/session_update");
        $('#date').val(convert_date(data.editData.session_date));
        $('#start').val(data.editData.session_start_time);
        $('#end').val(data.editData.session_close_time);
        get_counters_list(data.editData.counter_id);
        $('#counter').val(data.editData.counter_id);
        get_users_list(data.editData.user_id);
        $('#user').val(data.editData.user_id);
        $('#op').val(data.editData.opening_balance);
        $('#opening_balance').val(data.editData.opening_balance);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('session'); ?></th>";
        viewdata += "<td>" + data.editData.id + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('counter'); ?></th>";
        viewdata += "<td>" + data.editData.counter_id + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('session_mode'); ?></th>";
        viewdata += "<td>" + data.editData.session_mode + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('date'); ?></th>";
        viewdata += "<td>" + data.editData.session_date + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('time'); ?></th>";
        viewdata += "<td>" + data.editData.session_time + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('user'); ?></th>";
        viewdata += "<td>" + data.editData.name + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('open_balance'); ?></th>";
        viewdata += "<td>" + data.editData.opening_balance + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('closing_balance'); ?></th>";
        viewdata += "<td>" + data.editData.closing_amount + "</td>";
        viewdata += "</tr>";
        if(data.editData.session_mode == "Confirmed"){
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('actual_amount_from_counter'); ?></th>";
            viewdata += "<td>" + data.editData.actual_closing_amount + "</td>";
            viewdata += "</tr>";
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('remarks'); ?></th>";
            viewdata += "<td>" + data.editData.description + "</td>";
            viewdata += "</tr>";
        }
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('user_logged_in_time'); ?></th>";
        viewdata += "<td>" + data.editData.session_started_on + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('user_logged_out_time'); ?></th>";
        viewdata += "<td>" + data.editData.session_ended_on + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_new_counter_session'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/POS_data/session_add");
        clear_form();
    });
    $("table tbody").on("click", "a.show_receipts", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        window.open('<?php echo base_url() ?>service/POS_data/print_session_receipts?session_id='+selected_id, '_blank');       
    });
    function export_session_receipts(sessionId){
        window.open('<?php echo base_url() ?>service/POS_data/print_session_receipts?session_id='+sessionId, '_blank');
    }
    $("table tbody").on("click", "a.end_session", function () {
        $(".amount_splitup").remove();
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var opening_balance = rowData[6];
        $("#session_id1").val(selected_id);
        $.ajax({
            url: '<?php echo base_url() ?>service/POS_data/get_session_payment_types',
            type: 'GET',
            data:{sessionId:selected_id},
            async:false,
            success: function (data) {
                var output = "";
                $.each(data.amountBreakups, function (i, v) {
					$("#closing_amount2").val(data.closingAmount);
					$("#closing_amount1").val(data.closingAmount);
					$("#opening_balance1").val(opening_balance);
                    output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 amount_splitup">';
                    output += '<span class="span_label ">'+v.pay_type+'</span>';
                    output += '<div class="form-group">';
                    if(v.pay_type == "Cash"){
                        var amount = +v.closing_amount;
                        output += '<input type="text" name="closing'+i+'" id="closing'+i+'" class="form-control" readonly="" value="'+amount+'">';
                    }else{
                        output += '<input type="text" name="closing'+i+'" id="closing'+i+'" class="form-control" readonly="" value="'+v.closing_amount+'">';
                    }
                    output += '</div>';
                    output += '</div>';
                });
                $("#amount_splitup1").append(output);
            }
        });
        $('#formSessionEnd').modal('show');
    });
    $(".saveData2").click(function () {
        var form = $(".popup-form1");
        var url = "<?php echo base_url() ?>service/POS_data/end_counter_session";
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            async:false,
            success: function(data){
                $("#counter_sessions").dataTable().fnDraw();
                $('#formSessionEnd').modal('hide');
            }
        });
    });
    $("table tbody").on("click", "a.confirm_session", function () {
        $(".amount_splitup").remove();
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var closingAmount = rowData[10];
        $("#closing_amount").val(closingAmount);
        $("#session_id").val(selected_id);
        $.ajax({
            url: '<?php echo base_url() ?>service/POS_data/get_session_payment_types',
            type: 'GET',
            data:{sessionId:selected_id},
            async:false,
            success: function (data) {
                var output = "";
                $.each(data.amountBreakups, function (i, v) {
                    output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 amount_splitup">';
                    output += '<span class="span_label ">'+v.pay_type+'</span>';
                    output += '<div class="form-group">';
                    if(v.pay_type == "Cash"){
                        var amount = +v.closing_amount + +rowData[6];
                        output += '<input type="text" name="closing'+i+'" id="closing'+i+'" class="form-control" readonly="" value="'+amount+'">';
                    }else{
                        output += '<input type="text" name="closing'+i+'" id="closing'+i+'" class="form-control" readonly="" value="'+v.closing_amount+'">';
                    }
                    output += '</div>';
                    output += '</div>';
                });
                $("#amount_splitup").append(output);
            }
        });
        $('#formSessionConfirm').modal('show');
    });
    $(".saveData1").click(function () {
        var form = $(".popup-form");
        var url = "<?php echo base_url() ?>service/POS_data/confirm_ended_session";
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            async:false,
            success: function(data){
                $("#counter_sessions").dataTable().fnDraw();
                $('#formSessionConfirm').modal('hide');
            }
        });
    });
</script>
