<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#from_date').datepicker({
        format: "dd-mm-yyyy",
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    get_postal_stickers();
    var print_btn ='<button name="print_list" id="print_list" onclick="printPageArea()" class="btn btn-primary pull-right "><?php echo $this->lang->line('print_list'); ?></button>';
    function get_postal_stickers(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Postal_sticker_data/get_postal_stickers',
            type: 'POST',
            data: {from_date:$("#from_date").val(),to_date:$("#to_date").val()},
            success: function (data) {
                $("#list").html(data.list);
                $("#print_btn_div").html(print_btn);
            }
        });
    }
    function printPageArea(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Postal_sticker_data/get_postal_stickers_print',
            type: 'POST',
            data: {from_date:$("#from_date").val(),to_date:$("#to_date").val()},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.list);
                w.document.close();
            }
        });
    }
</script>