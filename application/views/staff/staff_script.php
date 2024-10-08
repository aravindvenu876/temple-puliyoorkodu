<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [6], "mData": 6,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        }, {
            "aTargets": [7], "mData": 6,
            "mRender": function (data, type, row) {
                var chert = "";
                if (data == 0)
                    chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>"+
                            "<i class='fa fa-trash' aria-hidden='true'></i>"+
                            "</a>";
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>"+
                        "<i class='fa fa-edit '></i>"+
                        "</a>" +
                        "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>"+
                        "<i class='fa fa-eye' aria-hidden='true'></i>" +
                        "</a>"+chert;
            }
        }
    ];
    var action_url = $('#staff').attr('action_url');
    oTable = gridSFC('staff', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#staff").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_designation_data/get_designation_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Designation</option>';
            $.each(data.designation, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.designation + '</option>';
            });
            $("#designation").append(string);
            $("#filter_staff_designation").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_userrole_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select User Roles</option>';
            $.each(data.roles, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.role + '</option>';
            });
            $("#role").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_staff_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_system_access_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = "";
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#system_access").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Salary_data/get_salary_scheme_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Salary Scheme</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.scheme + '</option>';
            });
            $("#salary_scheme").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Leave_data/get_leave_scheme_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Leave Scheme</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.scheme + '</option>';
            });
            $("#leave_scheme").append(string);
        }
    });

    detail('<?php echo base_url() ?>service/Staff_data/staff_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Staff_data/staff_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_staff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Staff_data/staff_update");
        $('#name').val(data.editData.staff.name);
        $('#staff_id').val(data.editData.staff.staff_id);
        $('#phone').val(data.editData.staff.phone);
        $('#designation').val(data.editData.staff.designation_id);
        $('#type').val(data.editData.staff.type);
        $('#system_access').val(data.editData.staff.system_access);
        $('#salary_scheme').val(data.editData.staff.salary_scheme_id);
        $('#leave_scheme').val(data.editData.staff.leave_scheme_id);
        $('#address').val(data.editData.staff.address);
        $('#bank').val(data.editData.staff.bank);
        $('#account_no').val(data.editData.staff.account_no);
        $('#ifsc_code').val(data.editData.staff.ifsc_code);
        if(data.editData.staff.system_access == '1'){
            $(".user_section_area").show();
            $.each(data.editData.roles, function (i, v) {
                $("#role option[value='" + v.id + "']").prop("selected", true);
            });
            $("#username").val(data.editData.user.username);
            $("#password").val(data.editData.user.plain);
        }else{
            $(".user_section_area").hide();
        }
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.staff.id));
    }
    function detail_view(data){
        var staff_status = "Active";
        if(data.editData.staff.status == '0'){
            staff_status = "Inactive";
        }
        var system_access = "Yes";
        if(data.editData.staff.system_access == '0'){
            system_access = "No";
        }
        var viewdata = "";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('staff_id'); ?></th>";
        viewdata += "<td>"+data.editData.staff.staff_id+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>"+data.editData.staff.name+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('designation'); ?></th>";
        viewdata += "<td>"+data.editData.staff.designation_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>"+data.editData.staff.phone+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>"+data.editData.staff.address+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>"+data.editData.staff.type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('salary_scheme'); ?></th>";
        viewdata += "<td>"+data.editData.staff.salary_scheme+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('leave_scheme'); ?></th>";
        viewdata += "<td>"+data.editData.staff.leave_scheme+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('bank'); ?></th>";
        viewdata += "<td>"+data.editData.staff.bank+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('account_no'); ?></th>";
        viewdata += "<td>"+data.editData.staff.account_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('ifsc_code'); ?></th>";
        viewdata += "<td>"+data.editData.staff.ifsc_code+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('status'); ?></th>";
        viewdata += "<td>"+staff_status+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('system_access'); ?></th>";
        viewdata += "<td>"+system_access+"</td>";
        viewdata += "</tr>";
        if(data.editData.staff.system_access == '1'){
            var roles = "";
            var j = 0;
            $.each(data.editData.roles, function (i, v) {
                j++;
                if(j > 1){
                    roles += ', ';
                }
                roles += v.role;
            });
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('role'); ?></th>";
            viewdata += "<td>"+roles+"</td>";
            viewdata += "</tr>";
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('username'); ?></th>";
            viewdata += "<td>"+data.editData.user.username+"</td>";
            viewdata += "</tr>";
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('password'); ?></th>";
            viewdata += "<td>"+data.editData.user.plain+"</td>";
            viewdata += "</tr>";
        }
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_staff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $(".user_section_area").show();
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Staff_data/staff_add");
        clear_form();
        $("#role option:selected").prop("selected", false);
    });
    $(document).ready(function(){
        $("#system_access").change(function(){
            if($("#system_access").val() == '1'){
                $(".user_section_area").show();
            }else{
                $(".user_section_area").hide();
            }
        });
    });
</script>




