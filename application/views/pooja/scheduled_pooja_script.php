<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        }
    ];
    var action_url = $('#scheduled_poojas').attr('action_url');
    oTable = gridSFC('scheduled_poojas', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#scheduled_poojas").dataTable().fnDraw();
    }
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        StartDate:0
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
</script>