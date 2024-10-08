<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
		{
        "aTargets": [1],
        "mData": 1,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [2],
        "mData": 0,
        "mRender": function(data, type, row) {
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit '></i></a>";
        }
    }];
    var action_url = $('#postal_charge').attr('action_url');
    oTable = gridSFC('postal_charge', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Master_data/postal_charge_edit', function(data) {
        detail_edit(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_postal_charge'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Master_data/postal_charge_update");
        $('#rate').val(data.editData.rate);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

</script>
