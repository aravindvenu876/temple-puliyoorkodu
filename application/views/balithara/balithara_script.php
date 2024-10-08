<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [4],"mData": 4,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [5],"mData": 5,
        "mRender": function(data, type, row) {
            if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
            else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        }
    }, {
        "aTargets": [6],"mData": 5,
        "mRender": function(data, type, row) {
            var btn = "";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
            if (data == 0){
                btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
            }
            return btn;
        }
    }];
    var action_url = $('#balithara_master').attr('action_url');
    oTable = gridSFC('balithara_master', action_url, aoColumnDefs);
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_balithara_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Balithara_data/balithara_editdata', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Balithara_data/balithara_editdata', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_balithara'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/balithara_update_data");
        $('#name_eng').val(data.editData.main.name_eng);
        $('#name_alt').val(data.editData.main.name_alt);
        $('#description_eng').val(data.editData.main.description_eng);
        $('#description_alt').val(data.editData.main.description_alt);
        $('#type').val(data.editData.main.type);
        $('#monthly_rent').val(data.editData.main.monthly_rate);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.main.id));
        $('#account_name1').val(data.editData.main.ledger_id);
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('balithara_english'); ?></th>";
        viewdata += "<td>" + data.editData.main.name_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('balithara_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.main.name_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>" + data.editData.main.type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('monthly_rent'); ?></th>";
        viewdata += "<td>" + data.editData.main.monthly_rate + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_ledger'); ?></th>";
        viewdata += "<td>" + data.editData.main.ledger_name + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_balithara'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/balithara_add");
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
