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
var print_btn ='<button name="print_list" id="print_list" onclick="printPageArea()" class="btn btn-primary pull-right">PRINT LIST</button>';
function get_list(){
    $.ajax({
        url: '<?php echo base_url() ?>service/Daily_list_data/get_pooja_list',
        type: 'POST',
        data: {date:$("#date").val()},
        success: function (data) {
            $("#list").html(data.list);
            $("#print_btn_div").html(print_btn);
        }
    });
}
function printPageArea(){
    $.ajax({
        url: '<?php echo base_url() ?>service/Daily_list_data/get_pooja_list_print',
        type: 'POST',
        data: {date:$("#date").val()},
        success: function (data) {
            var w = window.open('report:blank');
            w.document.open();
            w.document.write(data.list);
            w.document.close();
        }
    });
    // $.ajax({
    //     url: '<?php echo base_url() ?>service/Daily_list_data/get_pooja_list',
    //     type: 'POST',
    //     data: {date:$("#date").val()},
    //     success: function (data) {
    //         var WinPrint = window.open('', '', 'width=900,height=650');
    //         WinPrint.document.write(data.list);
    //         WinPrint.document.close();
    //         WinPrint.focus();
    //         WinPrint.print();
    //         WinPrint.close();
    //     }
    // });
}
</script>