<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    get_list();
    $("#date").change(function(){
        get_list();
    });
    var print_btn ='<button name="print_list" id="print_list" onclick="printPageArea()" class="btn btn-primary pull-right ">PRINT LIST</button>';
    function get_list(){
        $.ajax({
            url: '<?php echo base_url() ?>service/POS_data/get_counter_day_closing',
            type: 'POST',
            data: {date:$("#date").val()},
            success: function (data) {
                $("#list").html(data.list);
                $("#print_btn_div").html(print_btn);
            }
        });
    }
</script>