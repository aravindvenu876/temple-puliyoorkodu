<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [5], "mData": 5,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [6], "mData": 6,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [7], "mData": 0,
            "mRender": function (data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
                if (data == 0){
                    btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
                }
                return btn;
            }
        }
    ];
    var action_url = $('#assets').attr('action_url');
    oTable = gridSFC('assets', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#assets").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Asset_category_data/get_asset_category_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Category</option>';
            $.each(data.asset_category, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.category + '</option>';
            });
            $("#category").html(string);
            $("#filter_asset_category").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_assets_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
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
    detail('<?php echo base_url() ?>service/Asset_data/assets_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Asset_data/assets_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data) {
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_asset'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Asset_data/assets_update");
        $('#category').val(data.asset_category_id);
        $('#type').val(data.type);
        $('#unit').val(data.unit);
        $('#price').val(data.price);
        $('#asset_eng').val(data.name_eng);
        $('#asset_alt').val(data.name_alt);
        $('#description_eng').val(data.description_eng);
        $('#description_alt').val(data.description_alt);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.id));
        $('#account_name1').val(data.ledger_id);
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('asset_category_eng'); ?></th>";
        viewdata += "<td>"+data.category_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('asset_category_alt'); ?></th>";
        viewdata += "<td>"+data.category_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('asset_eng'); ?></th>";
        viewdata += "<td>"+data.name_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('asset_alt'); ?></th>";
        viewdata += "<td>"+data.name_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>"+data.type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_eng'); ?></th>";
        viewdata += "<td>"+data.unit_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('unit_alt'); ?></th>";
        viewdata += "<td>"+data.unit_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('price'); ?>(₹)</th>";
        viewdata += "<td> "+data.price+"</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_asset'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Asset_data/assets_add");
        clear_form();
    });
</script>