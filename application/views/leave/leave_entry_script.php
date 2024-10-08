<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1],
            "mData": 'staff',
            "mRender": function(data, type, row) {
                return data;
            }
        },  {
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },  {
            "aTargets": [3],
            "mData": 3,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        }, {
            "aTargets": [6], 
            "mData": 6,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Cancel</a>";
                else if (data = '0')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Apply</a>";
                else if (data = '3')
                    return "ACTIVE";
                else if(data == '4' || data == '2')
                    return "CANCELLED";
            }
        }
    ];
    var action_url = $('#leave_entry').attr('action_url');
    oTable = gridSFC('leave_entry', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#leave_entry").dataTable().fnDraw();
    }
    $('#filter_date').datepicker({
        format: "MM-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        todayHighlight: true,
        autoclose: true
    });
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });

    $('#filterfrom_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#filterto_date').datepicker('setStartDate', minDate);
    });
    $('#filterto_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#filterfrom_date').datepicker('setEndDate', maxDate);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
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

    $(".plus_btn").click(function() {
        $('#from_date').datepicker('setEndDate', '');
        $('#to_date').datepicker('setEndDate', '');
        $("#form_title_h2").html("<?php echo $this->lang->line('new_leave_entry'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Leave_data/add_leave_entry");
        clear_form();
    });

</script>