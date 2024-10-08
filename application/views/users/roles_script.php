<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [2],
        "mData": 2,
        "mRender": function(data, type, row) {
            if(data == 4){
                return "Non Editable";
            }else{
                if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        }
    }, {
        "aTargets": [3],
        "mData": 2,
        "mRender": function(data, type, row) {
            if(data == 4){
                return "Non Editable";
            }else{
                var chert = "";
                if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit '></i></a>" + chert;
            }
        }
    }];
    var action_url = $('#user_roles').attr('action_url');
    oTable = gridSFC('user_roles', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Role_data/role_edit', function(data) {
        detail_edit(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_role'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Role_data/role_update");
        $('#role').val(data.editData.role);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_role'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Role_data/role_add");
        clear_form();
    });

</script>
