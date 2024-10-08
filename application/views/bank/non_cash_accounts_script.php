<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [5],
        "mData": 0,
        "mRender": function(data, type, row) {
            return "<a style='cursor: pointer;' class='btn btn-warning btn-sm btn_active edit_btn_datatable' data-original-title='Map New Bank Account'>Map New Bank Account</a>";
        }
    }];
    var action_url = $('#non_cash_bank_account_mapping').attr('action_url');
    oTable = gridSFC('non_cash_bank_account_mapping', action_url, aoColumnDefs);
    get_banks(0);
    $("#bank").change(function(){
        var string = '<option value="">Select Account</option>';
        if ($("#bank").val() != "") {
            get_accounts($("#bank").val(),0);
        }else{
            $("#account").html(string);
        }
    });
    detail('<?php echo base_url() ?>service/Bank_data/edit_non_cash_mode_bank_acct', function(data) {
        detail_edit(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("Map New Bank Account To "+data.editData.non_cash_mode);
        $(".saveButton").text("Map Account");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/map_new_account_to_non_cash_mode");
        $('#payment_mode').val(data.editData.non_cash_mode);
        $("#orig_payment_mode").val(data.editData.non_cash_mode);
        get_banks(data.editData.bank_id);
        get_accounts(data.editData.bank_id,data.editData.account);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    $(".plus_btn").click(function() {
        clear_form();
    });
    function get_banks(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Bank</option>';
                $.each(data.banks, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.bank + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.bank + '</option>';
                    }
                });
                $("#bank").html(string);
            }
        });
    }
    function get_accounts(val1,val2){
        $.ajax({
            url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
            data: {bank: val1},
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                var string = '<option value="">Select Account</option>';
                $.each(data.accounts, function (i, v) {
                    if(val2 == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.account_no + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
                    }
                });
                $("#account").html(string);
            }
        });
    }
</script>
