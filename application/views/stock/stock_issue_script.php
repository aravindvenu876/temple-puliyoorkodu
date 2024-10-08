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
    var print_btn ='<button name="print_list" id="print_list" onclick="issueStock()" class="btn btn-primary pull-right ">ISSUE STOCK</button>';
    var print_btn1 ='<button class="btn btn-primary pull-right ">STOCK ISSUED</button>';
    function get_list(){
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url() ?>service/Stock_data/get_stock_issue_list',
            type: 'POST',
            data: {date:$("#date").val()},
            success: function (data) {
                $("#list").html(data.list);
                if(data.check == "1"){
                    if(data.issue_status == "1"){
                        $("#print_btn_div").html(print_btn);
                    }
                }else{
                    $("#print_btn_div").html(print_btn1);
                }
                $(".load").hide();
            }
        });
    }
    function issueStock(){
        $("#print_btn_div").html("");
        $.ajax({
            url: '<?php echo base_url() ?>service/Stock_data/issue_stock',
            type: 'POST',
            data: $("#stock_issue_form").serialize(),
            success: function (data) {
                $.toaster({priority: 'danger',title: '',message: data.viewMessage});
                get_list();
            }
        });
    }
</script>