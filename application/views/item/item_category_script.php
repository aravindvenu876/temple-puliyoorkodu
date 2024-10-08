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
            if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = 'Delete Data'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = 'Edit Data'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#item_category').attr('action_url');
    oTable = gridSFC('item_category', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Item_category_data/item_category_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Item_category_data/item_category_edit', function(data) {
        detail_view(data);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Unit_data/get_unit_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Unit</option>';
            $.each(data.units, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.unit +'('+ v.notation +')' + '</option>';
            });
            $("#unit").append(string);
        }
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_prasadam_cat'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Item_category_data/item_category_update");
        $('#item_category_eng').val(data.editData.category_eng);
        $('#item_category_alt').val(data.editData.category_alt);
        $("#unit").val(data.editData.unit);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_category_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.category_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_category_eng'); ?></th>";
        viewdata += "<td>" + data.editData.category_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_english'); ?></th>";
        viewdata += "<td>" + data.editData.unit_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.unit_alt + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_prasadam_cat'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Item_category_data/item_category_add");
        clear_form();
    });

</script>
