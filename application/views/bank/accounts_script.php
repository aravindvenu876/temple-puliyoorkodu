<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [6], "mData": 6,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [7],"mData": 7,
            "mRender": function(data, type, row) {
                if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [8],"mData": 7,
            "mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
                if (data == 0){
                    btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
                }
                return btn;
            }
        }
    ];
    var action_url = $('#bank_accounts').attr('action_url');
    oTable = gridSFC('bank_accounts', action_url, aoColumnDefs);
    $('#account_created_on').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Bank</option>';
            $.each(data.banks, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
            $("#bank").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_account_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#account_type").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Bank_data/account_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Bank_data/account_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_account'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/account_update");
        $('#bank').val(data.editData.bank_id);
        $('#account_type').val(data.editData.account_type);
        $('#account_no').val(data.editData.account_no);
        $('#account_name').val(data.editData.account_name);
        $('#account_created_on').val(convert_date(data.editData.account_created_on));
        $('#open_balance').val(data.editData.open_balance);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
        $('#account_name1').val(data.editData.ledger_id);
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
        viewdata += "<th><?php echo $this->lang->line('open_balance'); ?></th>";
        viewdata += "<td>INR " + data.editData.open_balance + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_ledger'); ?></th>";
        viewdata += "<td>" + data.editData.ledger_name + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_bank_account_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/account_add");
        clear_form();
        $.ajax({
			url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
			type: 'GET',
			async: false,
			success: function(data) {
				var string = '<option value="">Select Account Head</option>';
				$.each(data.account_head, function(i, v) {
					string += '<option value="' + v.id + '">' + v.head + '</option>';
				});
				$("#account_name1").html(string);
			}
		});
    });

</script>
