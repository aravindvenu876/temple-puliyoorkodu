<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [5],
        "mData": 5,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    },{
        "aTargets": [6],
        "mData": 6,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    // },{
    //     "aTargets": [7],
    //     "mData": 7,
    //     "mRender": function(data, type, row) {
    //         return "<a class='btn btn-danger btn-sm btn_active uploading_support_document'>Upload</a>";
    //     }
    // }, {
    //     "aTargets": [8],
    //     "mData": 7,
    //     "mRender": function(data, type, row) {
    //         var chert = "";
    //         return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
    //     }
    }];
    var action_url = $('#view_sb_to_sb').attr('action_url');
    oTable = gridSFC('view_sb_to_sb', action_url, aoColumnDefs);
    $('#filter_bank_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    function get_scheduled_pooja_list(){
        $("#view_sb_to_sb").dataTable().fnDraw();
    }
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: today
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
			var string = '';
            var string1 = '<option value="">Select From Bank</option>';
            var string2 = '<option value="">Select To Bank</option>';
            $.each(data.banks, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
            $("#from_bank").html(string1 + string);
            $("#filter_from_bank").html(string1 + string);
            $("#to_bank").html(string2 + string);
            $("#filter_to_bank").html(string2 + string);
        }
    });
    $('#from_bank').on('change', function() {
		var bank = $("#from_bank").val();
		if (bank != "") {
			$.ajax({
				url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
				data: {bank: bank},
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					var string = '<option value="">Select From Account</option>';
					$.each(data.accounts, function (i, v) {
						string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
					});
					$("#from_account").html(string);
				}
			});
		}
	});
    $('#to_bank').on('change', function() {
		var bank = $("#to_bank").val();
		if (bank != "") {
			$.ajax({
				url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
				data: {bank: bank},
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					var string = '<option value="">Select To Account</option>';
					$.each(data.accounts, function (i, v) {
						string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
					});
					$("#to_account").html(string);
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
        viewdata += "<th><?php echo $this->lang->line('created_on'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.account_created_on) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?>(₹)</th>";
        viewdata += "<td>" + convert_date(data.editData.amount) + "</td>";
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
        $("#form_title_h2").html("ADD SB TO SB");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/sb_to_sb_transfer_add");
        clear_form();
    });
</script>
