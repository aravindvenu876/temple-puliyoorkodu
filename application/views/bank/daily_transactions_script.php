<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
		{
			"aTargets": [2],"mData": 2,"mRender": function(data, type, row) {
				return convert_date(data);
			}
		},{
			"aTargets": [5],"mData": 5,"mRender": function(data, type, row) {
				return "<span class='amntRight'>"+data+"</span>";
			}
		},{
			"aTargets": [7],"mData": 7,"mRender": function(data, type, row) {
				if (data == '0') return "<b>Voucher Not Generated</b>";
				else if (data == '-1') return "<b>No Voucher</b>";
				else if (data != '') return "<b>Voucher Generated</b>";
			}
		},{
			"aTargets": [8],"mData": 7,"mRender": function(data, type, row) {
				return "<a class='btn btn-danger btn-sm btn_active uploading_support_document'>Upload</a>";
			}
		},{
			"aTargets": [9],"mData": 7,"mRender": function(data, type, row) {
				if (data == "-1"){
					return "-";
				}else{
					if (data == "0") return "<a class='btn btn-warning btn-sm ori_voucher btn_active'>Generate</a>";
					else if (data != '') return "<a class='btn btn-default btn-sm dup_voucher btn_active'>Duplicate</a>";
				}
			}
		},{
			"aTargets": [10],"mData": 7,"mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title='View Data'><i class='fa fa-eye'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='remv_btn_datatable' data-placement='right' data-original-title='Delete Data'><i class='fa fa-window-close'></i></a>";
                return btn;
			}
    	}
	];
    var action_url = $('#daily_transactions').attr('action_url');
    oTable = gridSFC('daily_transactions', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#daily_transactions").dataTable().fnDraw();
    }
    $('#filter_transaction_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    var date 	= new Date();
    var today 	= new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end 	= new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        //startDate: today
    });
	$("#type").change(function(){
		if($("#type").val() == ""){
			var string = '<option value="">Select Transaction Head</option>';
			$("#head").html(string);
		}else{
			$.ajax({
				url: '<?php echo base_url() ?>service/Transaction_head_data/get_transaction_head_drop_down',
				type: 'POST',
				data: {type: $("#type").val()},
				success: function (data) {
					var string = '<option value="">Select Transaction Head</option>';
					$.each(data.transaction_head, function (i, v) {
						string += '<option value="' + v.id + '">'+ v.head_eng + '</option>';
					});
					$("#head").html(string);
				}
			});
		}
	});
	$("#filter_transaction_type").change(function(){
		if($("#filter_transaction_type").val() == ""){
			var string = '<option value="">Select Transaction Head</option>';
			$("#filter_transaction_head").html(string);
		}else{
			$.ajax({
				url: '<?php echo base_url() ?>service/Transaction_head_data/get_transaction_head_drop_down',
				type: 'POST',
				data: {type: $("#filter_transaction_type").val()},
				success: function (data) {
					var string = '<option value="">Select Transaction Head</option>';
					$.each(data.transaction_head, function (i, v) {
						string += '<option value="' + v.id + '">'+ v.head_eng + '</option>';
					});
					$("#filter_transaction_head").html(string);
				}
			});
		}
	});
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_mode_of_payment',
        type: 'GET',
        success: function (data) {
            var string = '';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#payment_mode").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_transaction_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Transaction Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").html(string);
            $("#filter_transaction_type").html(string);
        }
    });
    detail('<?php echo base_url() ?>service/Bank_data/daily_transaction_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Bank_data/daily_transaction_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/daily_transaction_update");
        $('#head').val(data.editData.transaction_heads_id);
        $('#type').val(data.editData.transaction_type);
        $('#amount').val(data.editData.amount);
        $('#name').val(data.editData.name);
        $('#address').val(data.editData.address);
        $('#description').val(data.editData.description);
        $('#date').val(convert_date(data.editData.date));
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('transaction_head_english'); ?></th>";
        viewdata += "<td>" + data.editData.head_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('transaction_head_alternate'); ?></td>";
        viewdata += "<td>" + data.editData.head_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('transaction_type'); ?></th>";
        viewdata += "<td>" + data.editData.transaction_type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td> ₹ " + data.editData.amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>" + data.editData.name + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>" + data.editData.address + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('description'); ?></th>";
        viewdata += "<td>" + data.editData.description + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('entered_on'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.created_on) + "</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<h4><?php echo $this->lang->line('supporting_documents'); ?></h4>";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<tr class=' text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('document'); ?></th></tr>";
        var j = 0;
        $.each(data.documents, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td><a target='blank' href='<?php echo base_url() ?>"+v.document+"' class='btn btn-danger btn-sm btn_active'><?php echo $this->lang->line('view_document'); ?></a></td></tr>";
        });
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_daily_bank_transaction'); ?>");1
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/daily_transaction_add");
        clear_form();
        $("#date").val('<?php echo date("d-m-Y"); ?>')
    });
    $("table tbody").on("click", "a.ori_voucher", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        var msg = 'Are you sure you want to generate voucher for this entry?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Voucher_data/generate_voucher/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                    success: function (data) {
                        if (data.message == 'no enough privilege') {
                            $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                            return;
                        }
                        if (data.message == 'success') {
                            $.toaster({priority: 'success', title: '', message: "Voucher Generated"});
                            $("#" + data.grid).dataTable().fnDraw();
                            var w = window.open('report:blank');
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                        } else {
                            $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                            $("#" + data.grid).dataTable().fnDraw();
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
    $("table tbody").on("click", "a.dup_voucher", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        var msg = 'Are you sure you want to generate voucher for this entry?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Voucher_data/generate_duplicte_voucher/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                    success: function (data) {
                        if (data.message == 'no enough privilege') {
                            $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                            return;
                        }
                        if (data.message == 'success') {
                            $.toaster({priority: 'success', title: '', message: "Voucher Generated"});
                            $("#" + data.grid).dataTable().fnDraw();
                            var w = window.open('report:blank');
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                        } else {
                            $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                            $("#" + data.grid).dataTable().fnDraw();
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
    $("table.table tbody").on("click", "a.uploading_support_document", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $("#image_upload_grid").val(grid.attr("id"));
        $("#image_upload_id").val(selected_id);
        $("#image_upload_type").val("Daily");
        $(".document-upload-form").attr('action', '<?php echo base_url() ?>service/Bank_data/upload_support_document');
        $("#form_title_h2").html('');
        $("#modal-dialog-image-upload").modal('show');
    });
    $("#payment_mode").change(function(){
        getPaymentModeDetails();
    });
    function getPaymentModeDetails(){
        var output = "";
        $(".extra_payment_parametrs").remove();
        if($("#payment_mode").val() == "Cheque"){
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Cheque Number<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="number" name="cheque_no" id="cheque_no" min="0" step ="1" class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Bank<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="bank" id="bank" onchange="get_bank_accounts()" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Account<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Process Date<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="text" name="cheque_date" id="cheque_date" readonly class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
        }else if($("#payment_mode").val() == "DD"){
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">DD Number<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="number" name="cheque_no" id="cheque_no" min="0" step ="1" class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Bank<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="bank" id="bank" onchange="get_bank_accounts()" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Account<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Process Date<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="text" name="cheque_date" id="cheque_date" readonly class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
        }else if($("#payment_mode").val() == "Card"){
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Card Transaction Number<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="number" name="cheque_no" id="cheque_no" min="0" step ="1" class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Bank<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="bank" id="bank" onchange="get_bank_accounts()" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Account<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Transaction Date<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="text" name="cheque_date" id="cheque_date" readonly class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
        }else if($("#payment_mode").val() == "Online"){
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Online Transaction Number<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="number" name="cheque_no" id="cheque_no" min="0" step ="1" class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Bank<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="bank" id="bank" onchange="get_bank_accounts()" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Processing Account<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>';
			output += '</div>';
			output += '</div>';
			output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 extra_payment_parametrs">';
			output += '<span class="span_label">Transfer Date<span class="asterisk">*</span></span>';
			output += '<div class="form-group">';
			output += '<input type="text" name="cheque_date" id="cheque_date" readonly class="form-control parsley-validated" data-required="true"/>';
			output += '</div>';
			output += '</div>';
        }
        $("#additional_params").append(output);
		$.ajax({
			url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
			type: 'GET',
			success: function (data) {
				var string = '<option value="">Select Bank</option>';
				$.each(data.banks, function (i, v) {
					string += '<option value="' + v.id + '">'+ v.bank + '</option>';
				});
				$("#bank").html(string);
			}
		});
        $("#cheque_date").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            // startDate: today
        });
    }
    function get_bank_accounts(){
        if ($("#bank").val() != "") {
            $.ajax({
                url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
                data: {bank: $("#bank").val()},
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    var string = '<option value="">Select Account</option>';
                    $.each(data.accounts, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
                    });
                    $("#account").html(string);
                }
            });
        }
    }
    $("table tbody").on("click", "a.remv_btn_datatable", function () {
        var grid    = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var id      = rowData[0];
        var msg     = 'Are you sure you want to cancel this entry? It will create a reverse entry in day book';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $(".load").show();
                $.ajax({
                    url: '<?php echo base_url() ?>service/Bank_data/cancel_daily_transaction',
                    data: {id:id},
                    type: 'POST',
                    success: function(data) {
                        $(".load").hide();
                        if(data.status == 1){
                            $.toaster({priority: 'success',title: '',message: data.viewMessage});
                            $("#daily_transactions").dataTable().fnDraw();
                        }else{
                            $.toaster({priority: 'error',title: '',message: data.viewMessage});
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
</script>
