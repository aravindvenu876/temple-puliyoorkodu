<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    get_default_calendar();
    function get_default_calendar(){
        get_calendar(0,0);
    }
    function get_calendar(val1,val2){
        $.ajax({
            url: '<?php echo base_url() ?>service/Master_data/get_calendar',
            type: 'GET',
            data: {month:val1,year:val2},
            success: function (data) {
                $("#calendar_place").html(data.list);
            }
        });
    }

</script>
