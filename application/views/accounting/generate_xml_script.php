<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
$('#date').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
$("#date").change(function(){
    balance_entries();
});
function balance_entries(){
    if($("#date").val() == ""){
        bootbox.alert("Please Select Date!");
    }else{
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url() ?>service/Account_basic_data/get_balance_entires?date='+$("#date").val(),
            type: 'GET',
            success: function (data) {
                $(".load").hide();
                $("#sync").html("Sync Receipt Data With Accounting Entries ("+data.balance_sync_entires+" entries left)");
                $("#generate").html("Generate Tally XML ("+data.balance_taly_entries+" entries left)");
            }
        });
    }
}
function sync_accounting_entries(){
    if($("#date").val() == ""){
        bootbox.alert("Please Select Date!");
    }else{
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url();?>service/account_basic_data/sync_receipt_with_accounting_entries?date='+$("#date").val(),
            type: 'GET',
            success: function(response) {
                var obj = JSON.parse(response);
                $(".load").hide();
                $.toaster({priority:'success',title:'Success',message:'Receipt Syncing Completed'});
                balance_entries();
            },
            error: function () {
                $(".load").hide();
                $.toaster({priority:'danger',title:'Error',message:'An error occured'});
            }
        });
    }
}
function generate_tally_xml(){
    if($("#date").val() == ""){
        bootbox.alert("Please Select Date!");
    }else{
        $(".load").show();
        $.ajax({
            url: '<?php echo base_url();?>service/account_basic_data/generate_tally_xml?date='+$("#date").val(),
            type: 'GET',
            success: function(response) {
                var obj = JSON.parse(response);
                $(".load").hide();
                $.toaster({priority:'success',title:'Success',message:'Tally XML Generated'});
                balance_entries();
            },
            error: function () {
                $(".load").hide();
                $.toaster({priority:'danger',title:'Error',message:'An error occured'});
            }
        });
    }
}
</script>




