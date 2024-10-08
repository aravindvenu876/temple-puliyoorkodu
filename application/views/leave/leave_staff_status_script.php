<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3],
            "mData": 'total_leave_count',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [4],
            "mData": 'total_leave_taken',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [5],
            "mData": 'balanceLeaveCount',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [6],
            "mData": 'extraleave',
            "mRender": function(data, type, row) {
                return data;
            }
        }
    ];
    var action_url = $('#leave_status').attr('action_url');
    oTable = gridSFC('leave_status', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#leave_status").dataTable().fnDraw();
    }
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
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
          
            $("#filter_staff").html(string);
        }
    });
</script>