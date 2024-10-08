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
        }, {
            "aTargets": [4], "mData": 3,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit '></i></a>";
            }
        }
    ];
    var action_url = $('#accounting_map_heads').attr('action_url');
    oTable = gridSFC('accounting_map_heads', action_url, aoColumnDefs);
    function get_accounting_map_heads(){
        $("#accounting_map_heads").dataTable().fnDraw();
    }
    detail('<?php echo base_url() ?>service/Account_basic_data/edit_basic_map_heads', function (data) {
        detail_edit(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_Map_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/update_basic_map_heads");
        $('#map_head').val(data.editData.map_head);
        $('#map_table').val(data.editData.map_table);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_Map_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/add_basic_map_heads");
        clear_form();
    });

</script>




