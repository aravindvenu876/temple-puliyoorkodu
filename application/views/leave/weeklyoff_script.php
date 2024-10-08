<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
        "aTargets": [3],
        "mData": 1,
        "mRender": function(data, type, row) {
            var chert = "";
            if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = 'Delete Data'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
             return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = 'Edit Data'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    var action_url = $('#staff_weekly_off').attr('action_url');
    oTable = gridSFC('staff_weekly_off', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Staff_data/staffweeklyoff_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Staff_data/staffweeklyoff_edit', function(data) {
        detail_view(data);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_sec',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                
               if(v.designation_eng=='security'){
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
               }
               
            });
            $("#staff").html(string);
            $("#filter_staff").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_leave_type_drop_down',
        type: 'GET',
        success: function (data) {
            var string = "";
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_weeklyoff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Staff_data/update_weeklyoff");
        $('#staff').val(data.editData.staff_id);
        $('#from_date').val(data.editData.off_date);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('staff'); ?></th>";
        viewdata += "<td>" + data.editData.name + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('Off_Date'); ?></th>";
        viewdata += "<td>" + data.editData.off_date + "</td>";
        viewdata += "</tr>";
       
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $('#from_date').datepicker('setEndDate', '');
        $('#to_date').datepicker('setEndDate', '');
        $("#form_title_h2").html("<?php echo $this->lang->line('add_weeklyoff'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Staff_data/add_weeklyoff");
        clear_form();
    });

</script>
