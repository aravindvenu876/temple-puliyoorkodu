<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                if (data == 'Child')
                    return "Ledger";
                else if (data != '')
                    return "Group";
            }
        },{
            "aTargets": [5], "mData": 5,
            "mRender": function (data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                return btn;
            }
        }
    ];
    var action_url = $('#account_groups').attr('action_url');
    oTable = gridSFC('account_groups', action_url, aoColumnDefs);
    function get_accounting_map_heads(){
        $("#account_groups").dataTable().fnDraw();
    }
    get_parent_groups(0);
    function get_parent_groups(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/Account_basic_data/get_account_groups_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="0">New Group</option>';
                $.each(data.groupHeads, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.head + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.head + '</option>';
                    }
                });
                $("#parent_group").html(string);
            }
        });
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("Add Group/Ledger");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/add_accounting_group");
        clear_form();
    });
    detail('<?php echo base_url() ?>service/Account_basic_data/edit_account_head', function(data) {
        detail_edit(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("Update Ledger/Group");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/update_accounting_group");
        $('#group').val(data.head);
        $('#group_status').val(data.type);
        get_parent_groups(data.parent_id);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.id));
    }
</script>




