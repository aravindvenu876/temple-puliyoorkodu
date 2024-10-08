<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [3],
        "mData": 3,
        "mRender": function(data, type, row) {
            if(data == 4){
                return "Non Editable";
            }else{
                if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        }
    }, {
        "aTargets": [4],
        "mData": 3,
        "mRender": function(data, type, row) {
            if(data == 4){
                return "Non Editable";
            }else{
                var chert = "";
                if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
            }
        }
    }];
    var action_url = $('#salary_heads').attr('action_url');
    oTable = gridSFC('salary_heads', action_url, aoColumnDefs);
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_head_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Salary_data/salary_head_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Salary_data/salary_head_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_salary_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/salary_head_update");
        $('#name').val(data.editData.head);
        $('#type').val(data.editData.type);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('salary_head'); ?></th>";
        viewdata += "<td>" + data.editData.head + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>" + data.editData.type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_salary_head'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/salary_head_add");
        clear_form();
    });
    function valNames(e) {
    var regex = new RegExp("^[a-zA-Z]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (e.which == 8 || e.which == 32 || e.which == 0) {
        return true;
    } else {
        if (regex.test(str)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    }
}
</script>
