<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [4],
        "mData": 4,
        "mRender": function(data, type, row) {
           var chert="";
            if (data == 0) chert = "";
            return "<a style='cursor: pointer;' data-toggle='tooltip'  class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#system_sub_menu_lang').attr('action_url');
    oTable = gridSFC('system_sub_menu_lang', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Configuration_data/sub_menu_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Configuration_data/sub_menu_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_submenu'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Configuration_data/sub_menu_update");
        $('#sub_eng').val(data.editData.sub_menu_eng);
        $('#sub_alt').val(data.editData.sub_menu_alt);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('main_menu_eng'); ?></th>";
        viewdata += "<td>" + data.editData.main_menu_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('main_menu_alt'); ?></th>";
        viewdata += "<td>" + data.editData.main_menu_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('sub_menu_eng'); ?></th>";
        viewdata += "<td>" + data.editData.sub_menu_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('sub_menu_alt'); ?></th>";
        viewdata += "<td>" + data.editData.sub_menu_alt + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
   

</script>
