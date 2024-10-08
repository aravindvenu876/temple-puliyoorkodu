<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [2],
        "mData": 2,
        "mRender": function(data, type, row) {
            if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
            else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        }
    }, {
        "aTargets": [3],
        "mData": 2,
        "mRender": function(data, type, row) {
            var chert = "";
            if (data == 0) chert = "";
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#leave_heads').attr('action_url');
    oTable = gridSFC('leave_heads', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Leave_data/leave_head_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Leave_data/leave_head_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_leave_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Leave_data/leave_head_update");
        $('#name').val(data.editData.head);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('leave_head'); ?></th>";
        viewdata += "<td>" + data.editData.head + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_leave_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Leave_data/leave_head_add");
        clear_form();
    });

</script>
