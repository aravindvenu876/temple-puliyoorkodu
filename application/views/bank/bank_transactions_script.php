<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],"mData": 2,"mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [3],"mData": 3,"mRender": function(data, type, row) {
                return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [7],"mData": 7,"mRender": function(data, type, row) {
                return "<a class='btn btn-danger btn-sm btn_active uploading_support_document'>Upload</a>";
            }
        },{
            "aTargets": [8],"mData": 7,"mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title='View Data'><i class='fa fa-eye'></i></a>";
                if(row[4] == 'PETTY CASH WITHDRAWAL' || row[4] == 'INCOME CASH DEPOSIT')
                    btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='remv_btn_datatable' data-placement='right' data-original-title='Delete Data'><i class='fa fa-window-close'></i></a>";
                return btn;
            }
        }
    ];
    var action_url = $('#bank_transaction').attr('action_url');
    oTable = gridSFC('bank_transaction', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#bank_transaction").dataTable().fnDraw();
    }
    $('#filter_bank_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $("#filter_bank_bank").change(function(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
            data: {bank: $("#filter_bank_bank").val()},
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                var string = '<option value="">Select Account</option>';
                $.each(data.accounts, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
                });
                $("#filter_bank_account").html(string);
            }
        });
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        //startDate: today
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Bank</option>';
            $.each(data.banks, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
            $("#bank").html(string);
            $("#filter_bank_bank").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_bank_transaction_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Transaction Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_bank_transaction_filter_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Transaction Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#filter_bank_type").html(string);
        }
    });
    $('#bank').on('change', function() {
		var bank = $("#bank").val();
		if (bank != "") {
			$.ajax({
				url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
				data: {bank: bank},
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
	});
    detail('<?php echo base_url() ?>service/Bank_data/bank_transaction_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Bank_data/bank_transaction_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/bank_transaction_update");
        $('#bank').val(data.editData.bank_id);
        $('#account').val(data.editData.account_id);
        $('#type').val(data.editData.type);
        $('#transaction_id').val(data.editData.transaction_id);
        $('#date').val(convert_date(data.editData.date));
        $('#amount').val(data.editData.amount);
        $('#description').val(convert_date(data.editData.description));
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank_english'); ?></th>";
        viewdata += "<td>" + data.editData.bank_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.bank_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_no'); ?></th>";
        viewdata += "<td>" + data.editData.account_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_type'); ?></th>";
        viewdata += "<td>" + data.editData.account_type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_holder'); ?></th>";
        viewdata += "<td>" + data.editData.account_name + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('transaction_type'); ?></th>";
        viewdata += "<td>" + data.editData.type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('transaction_id'); ?></th>";
        viewdata += "<td>" + data.editData.transaction_id + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('date'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.date) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?>(₹)</th>";
        viewdata += "<td>" + convert_date(data.editData.amount) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('description'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.description) + "</td>";
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
        $("#form_title_h2").html("<?php echo $this->lang->line('add_bank_transaction_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/bank_transaction_add");
        clear_form();
    });
    $("table.table tbody").on("click", "a.uploading_support_document", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $("#image_upload_grid").val(grid.attr("id"));
        $("#image_upload_id").val(selected_id);
        $("#image_upload_type").val("Bank");
        $(".document-upload-form").attr('action', '<?php echo base_url() ?>service/Bank_data/upload_support_document');
        $("#form_title_h2").html('');
        $("#modal-dialog-image-upload").modal('show');
    });
    $("table tbody").on("click", "a.remv_btn_datatable", function () {
        var grid    = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var id      = rowData[0];
        var msg     = 'Are you sure you want to cancel this entry? It will create a reverse entry in day book';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $(".load").show();
                $.ajax({
                    url: '<?php echo base_url() ?>service/Bank_data/cancel_bank_transaction',
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
