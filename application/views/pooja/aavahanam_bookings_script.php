<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [8], "mData": 8,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>₹ "+data+"/-</span>";
            }
        },{
            "aTargets": [9], "mData": 9,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>₹"+data+"/-</span>";
            }
        },{
            "aTargets": [10], "mData": 0,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='change_date' data-placement='right' data-original-title='Change Date'><i class='fa fa-edit'></i></a>";
            }
        }
    ];
    var action_url = $('#aavaahanam_poojas').attr('action_url');
    oTable = gridSFC('aavaahanam_poojas', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#aavaahanam_poojas").dataTable().fnDraw();
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
    $("table.table tbody").on("click", "a.change_date", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $("#new_aavahanam_id").val(rowData[0]);
        $("#cur_booking_date").val(rowData[3]);
        $("#new_booking_date").val('');
        $("#form_title_h2").html('Update Aavaahanam# '+rowData[0]+' Date');
        $("#modal-dialog-change-aavaahanam-date").modal('show');
    });
    function update_aavahanam_date(){
        var booking_id = $("#new_aavahanam_id").val();
        var new_booking_date = $("#new_booking_date").val();
        var cur_booking_date = $("#cur_booking_date").val();
        if(new_booking_date == ''){
            bootbox.alert('Please select a date');
        }else{
            var msg = 'Are you sure you want to change the aavaahanam# '+booking_id+' date from '+convert_date(cur_booking_date)+' to '+convert_date(new_booking_date)+'?';
            bootbox.confirm(msg, function (result) {
                if (result) {
                    $(".load").show();
                    $.ajax({
                        url: "<?php echo base_url() ?>" + "service/Pooja_data/aavaahanam_update",
                        data: {booking_id: booking_id,new_booking_date: new_booking_date},
                        type: 'POST',
                        success: function (data) {
                            $(".load").hide();
                            if(data.status == 1){
                                $.toaster({priority: 'success',title: '',message: data.viewMessage});
                                $("#aavaahanam_poojas").dataTable().fnDraw();
                                $("#modal-dialog-change-aavaahanam-date").modal('hide');
                            }else{
                                $.toaster({priority: 'error',title: '',message: data.viewMessage});
                            }
                        }
                    });
                }
            }).find(".modal-dialog").css("width", "30%");
        }
    }
</script>