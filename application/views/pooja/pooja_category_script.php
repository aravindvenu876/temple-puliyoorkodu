<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2], "mData": 3,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        }, {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                var chert = "";
                if (data == 0)
                    chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = 'Delete Data'>"+
                            "<i class='fa fa-trash' aria-hidden='true'></i>"+
                            "</a>";
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = 'Edit Data'>"+
                        "<i class='fa fa-edit '></i>"+
                        "</a>" +
                        "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'>"+
                        "<i class='fa fa-eye' aria-hidden='true'></i>" +
                        "</a>"+chert;
            }
        }
    ];
    var action_url = $('#pooja_category').attr('action_url');
    oTable = gridSFC('pooja_category', action_url, aoColumnDefs);

    detail('<?php echo base_url() ?>service/Pooja_category_data/pooja_category_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Pooja_category_data/pooja_category_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_pooja_category'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_category_data/pooja_category_update");
        $('#pooja_category_eng').val(data.editData.category_eng);
        $('#pooja_category_alt').val(data.editData.category_alt);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_category_english'); ?></th>";
        viewdata += "<td>"+data.editData.category_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('pooja_category_alternate'); ?></th>";
        viewdata += "<td>"+data.editData.category_alt+"</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_pooja_category'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_category_data/pooja_category_add");
        clear_form();
    });
   
</script>




