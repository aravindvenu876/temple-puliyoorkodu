<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [4],
        "mData": 3,
        "mRender": function(data, type, row) {
            var chert = "";
            if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#supplier').attr('action_url');
    oTable = gridSFC('supplier', action_url, aoColumnDefs);
   
    detail('<?php echo base_url() ?>service/Supplier_data/supplier_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Supplier_data/supplier_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Supplier_data/supplier_update");
        $('#name').val(data.editData.name);
        $('#store').val(data.editData.store);
        $('#phone').val(data.editData.phone);
        $('#email').val(data.editData.email);
        $('#pan').val(data.editData.pan);
        $('#gst').val(data.editData.gst);
        $('#bank').val(data.editData.bank);
        $('#account_no').val(data.editData.account_no);
        $('#ifsc').val(data.editData.ifsc);
        $('#address').val(data.editData.address);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
        $('#account_name1').val(data.editData.accounting_head_id);
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>" + data.editData.name + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('store_name'); ?></th>";
        viewdata += "<td>" + data.editData.store + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>" + data.editData.phone+ "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('email'); ?></th>";
        viewdata += "<td>" + data.editData.email + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('pan_no'); ?></th>";
        viewdata += "<td>" + data.editData.pan + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('GST'); ?></th>";
        viewdata += "<td>" + data.editData.gst + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank_name'); ?></th>";
        viewdata += "<td>" + data.editData.bank + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_no'); ?></th>";
        viewdata += "<td>" + data.editData.account_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('ifsc_code'); ?></th>";
        viewdata += "<td>" + data.editData.ifsc + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>" + data.editData.address + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_ledger'); ?></th>";
        viewdata += "<td>" + data.editData.head + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_supplier_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Supplier_data/supplier_add");
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
