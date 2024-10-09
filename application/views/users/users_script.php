<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [7], "mData": 7,
            "mRender": function (data, type, row) {
                if (data == 0)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Ban User</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Activate User</a>";
            }
        }, {
            "aTargets": [8], "mData": 0,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>"+
                        "<i class='fa fa-edit '></i>"+
                        "</a>";
            }
        }
    ];
    var action_url = $('#users').attr('action_url');
    oTable = gridSFC('users', action_url, aoColumnDefs);
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_userrole_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '';
            $.each(data.roles, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.role + '</option>';
            });
            $("#role").html(string);
        }
    });
    detail('<?php echo base_url() ?>service/System_users_data/user_edit', function (data) {
        detail_edit(data);
    });
    function detail_edit(data) {
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_staff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/System_users_data/user_update");
        $('#name').val(data.editData.name);
        $('#phone').val(data.editData.phone);
        $.each(data.editData.roles, function (i, v) {
            $("#role option[value='" + v.id + "']").prop("selected", true);
        });
        $("#username").val(data.editData.username);
        $("#password").val();
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_staff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/System_users_data/user_add");
        clear_form();
        $("#role option:selected").prop("selected", false);
    });
</script>