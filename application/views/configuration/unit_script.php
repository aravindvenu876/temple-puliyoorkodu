<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [3],
        "mData": 3,
        "mRender": function(data, type, row) {
            if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
            else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        }
    }, {
        "aTargets": [4],
        "mData": 3,
        "mRender": function(data, type, row) {
            var chert = "";
            if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#unit').attr('action_url');
    oTable = gridSFC('unit', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Unit_data/unit_edit', function(data) {
        $('#hide').hide();
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Unit_data/unit_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_unit'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Unit_data/unit_update");
        $('#unit_eng').val(data.unit_eng);
        $('#unit_alt').val(data.unit_alt);
        $('#unit_notation').val(data.notation);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.id));
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_english'); ?></th>";
        viewdata += "<td>" + data.unit_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_alternate'); ?></th>";
        viewdata += "<td>" + data.unit_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_notation'); ?></th>";
        viewdata += "<td>" + data.notation + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_new_unit'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Unit_data/unit_add");
        clear_form();
    });
    $(".saveButton").click(function() {
        $('#hide').show();
    });
    $("#cancelEdit").click(function() {
        $('#hide').show();
    });
</script>
