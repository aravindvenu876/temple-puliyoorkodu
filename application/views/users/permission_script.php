<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],
            "mData": 'permission_check',
            "mRender": function(data, type, row) {
                if(data == '0'){
                    return "Permission Not Defined";
                }else{
                    return "Permission Defined"
                }
            }
        },{
            "aTargets": [3],
            "mData": 1,
            "mRender": function(data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>"+
                       "<button class='btn btn-primary btn-sm'>DEFINE PERMISSION</button>"+
                       "</a>";
            }
        }
    ];
    var action_url = $('#user_permission').attr('action_url');
    oTable = gridSFC('user_permission', action_url, aoColumnDefs);

    detail('<?php echo base_url() ?>service/Permission_data/permission_edit', function (data) {
        detail_edit(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $(".saveButton").text("<?php echo $this->lang->line('define_permission'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Permission_data/define_user_permission");
        $("#permission_page").html(data.page);
        $("#data_grid").val(oTable.attr("id"));
    }
</script>