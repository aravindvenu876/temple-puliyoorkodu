<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [6], "mData": 6,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "Yes";
                else if (data != '')
                    return "No";
            }
        },{
            "aTargets": [7], "mData": 7,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "Yes";
                else if (data != '')
                    return "No";
            }
        },{
            "aTargets": [8], "mData": 8,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "Yes";
                else if (data != '')
                    return "No";
            }
        },{
            "aTargets": [9], "mData": 9,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [10], "mData": 0,
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
    var action_url = $('#pooja').attr('action_url');
    oTable = gridSFC('pooja', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#pooja").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url('service/Pooja_category_data/get_pooja_category_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Category</option>';
            $.each(data.pooja_category, function (i, v) {
				string += '<option value="' + v.id + '">'+ v.category + '</option>';
            });
            $("#category").html(string);
            $("#filter_pooja_category").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url('service/Rest_shared/get_pooja_types_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Pooja_data/pooja_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Pooja_data/pooja_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data) { 
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_pooja'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_data/pooja_update");
        $('#category').val(data.editData.pooja_category_id);
        $('#type').val(data.editData.type);
        $('#daily_pooja').val(data.editData.daily_pooja);
        $('#quantity_pooja').val(data.editData.quantity_pooja);
        $('#rate').val(data.editData.rate);
        $('#pooja_eng').val(data.editData.pooja_name_eng);
        $('#pooja_alt').val(data.editData.pooja_name_alt);
        $('#description_eng').val(data.editData.pooja_description_eng);
        $('#description_alt').val(data.editData.pooja_description_alt);
        $('#website_pooja').val(data.editData.website_pooja);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data){
        var viewdata = "";
        var currency = '<?php echo CURRENCY ?>';
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_category_english'); ?></th>";
        viewdata += "<td>"+data.editData.category_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_category_alternate'); ?></th>";
        viewdata += "<td>"+data.editData.category_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('pooja_english'); ?></th>";
        viewdata += "<td>"+data.editData.pooja_name_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_alternate'); ?></th>";
        viewdata += "<td>"+data.editData.pooja_name_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>"+data.editData.type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('rate'); ?></th>";
        viewdata += "<td> ₹"+ data.editData.rate+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('daily_pooja'); ?></th>";
        if(data.editData.daily_pooja == '0'){
            viewdata += "<td>No</td>";
        }else{
            viewdata += "<td>Yes</td>";
        }
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_pooja'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_data/pooja_add");
        clear_form();
    });
    function valNames(e) {
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }
</script>




