<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                // btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
                if (data == 0){
                    btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
                }
                return btn;
            }
        }
    ];
    var action_url = $('#web_pooja_prasadams').attr('action_url');
    oTable = gridSFC('web_pooja_prasadams', action_url, aoColumnDefs);
    $.ajax({
        url: '<?php echo base_url() ?>service/Pooja_category_data/get_web_pooja_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">--Select Pooja--</option>';
            $.each(data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#pooja_id").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Item_data/get_prasadam_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">--Select Prasadam--</option>';
            $.each(data.prasadam, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#prasadam_id").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Pooja_category_data/web_pooja_prasadams_edit', function (data) {
        detail_edit(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("UPDATE WEB POOJA PRASADAM");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_category_data/web_pooja_prasadams_update");
        $('#pooja_id').val(data.pooja_id);
        $('#prasadam_id').val(data.prasadam_id);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.id));
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("ADD WEB POOJA PRASADAM");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_category_data/web_pooja_prasadams_add");
        clear_form();
    });
</script>




