<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return "<span class='amntRight'>"+data+"</span>";
            }
        }, {
            "aTargets": [3],
            "mData": 'received_date',
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        }, {
            "aTargets": [4],
            "mData": 4,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        }, {
            "aTargets": [7],
            "mData": 'bank',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [9],
            "mData": '8',
            "mRender": function(data, type, row) {
                if(data == "RECEIVED"){
                    return "<a class='btn btn-warning btn-sm process btn_active'>PROCESS</a>";
                }else if(data == "PROCESSING"){
                    return "<a class='btn btn-warning btn-sm process_result btn_active'>CASHED/BOUNCED</a>";
                }else{
                    return "DD Processed";
                }
            }
        }, {
            "aTargets": [10],
            "mData": 8,
            "mRender": function(data, type, row) {
                var chert = "";
                if(data == "BOUNCED"){
                    chert += "<a style='cursor: pointer;' data-toggle='tooltip' class='repay_bounced' data-placement='right' data-original-title='Repay'><i class='fa fa-plus '></i></a>";
                }
                return chert + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#cheque_management').attr('action_url');
    oTable = gridSFC('cheque_management', action_url, aoColumnDefs);
    viewData('<?php echo base_url() ?>service/Cheque_data/get_cheque_details', function(data) {
        detail_view(data);
    });
    var bankSelection = "";
    bankSelection +='<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 cashed">';
    bankSelection +='<span class="span_label ">Bank</span>';
    bankSelection +='<div class="form-group">';
    bankSelection +='<select name="bank" id="bank" class="form-control" onchange="getBankAccounts()"></select>';
    bankSelection +='</div>';
    bankSelection +='</div>';
    bankSelection +='<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 cashed">';
    bankSelection +='<span class="span_label ">Account</span>';
    bankSelection +='<div class="form-group">';
    bankSelection +='<select name="account" id="account" class="form-control"></select>';
    bankSelection +='</div>';
    bankSelection +='</div>';
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: today
    });
    var bankString = "";
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
            bankString = '<option value="">Select Bank</option>';
            $.each(data.banks, function (i, v) {
                bankString += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
        }
    });
    function getBankAccounts(){
        var string = '';
        if ($("#bank").val() != "") {
            $.ajax({
                url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
                data: {bank: $("#bank").val()},
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    string = '<option value="">Select Account</option>';
                    $.each(data.accounts, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
                    });
                    $("#account").html(string);
                }
            });
        }else{
            string = '<option value="">Select Account</option>';
            $("#account").html(string);
        }
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('dd_no'); ?></th>";
        viewdata += "<td>" + data.data.cheque_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('dd_date'); ?></th>";
        viewdata += "<td>" + convert_date(data.data.date) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('processed_date'); ?></th>";
        viewdata += "<td>" + convert_date(data.data.processed_date) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('dd_amount'); ?></th>";
        viewdata += "<td> ₹ " + data.data.amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank'); ?></th>";
        if(data.data.bank == null){
            viewdata += "<td></td>";
        }else{
            viewdata += "<td>" + data.data.bank + "</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        if(data.data.name == null){
            viewdata += "<td></td>";
        }else{
            viewdata += "<td>" + data.data.name + "</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        if(data.data.phone == null){
            viewdata += "<td></td>";
        }else{
            viewdata += "<td>" + data.data.phone + "</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('dd_status'); ?></th>";
        viewdata += "<td>" + data.data.status + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        if(data.data.section == "RECEIPT"){
            viewdata += "<th><?php echo $this->lang->line('receipt_no'); ?></th>";
            viewdata += "<td>" + data.details.receipt_no + "</td>";
            viewdata += "</tr>";
        }
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('remarks'); ?></th>";
        if(data.data.remarks == null){
            viewdata += "<td></td>";
        }else{
            viewdata += "<td>" + data.data.remarks + "</td>";
        }
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $("table tbody").on("click", "a.process", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $("#cheque_id").val(rowData[0]);
        $("#chequeNo").html("DD No : " + rowData[1]);
        $("#chequeDate").html("DD Date : " + convert_date(rowData[4]));
        $("#chequeAmount").html("Amount : (₹) " + rowData[2]);
        if(rowData[7] == null){
            $("#chequeBank").html("Bank : ");
        }else{
            $("#chequeBank").html("Bank : " + rowData[7]);
        }
        if(rowData[5] == null){
            $("#chequeName").html("Name : ");
        }else{
            $("#chequeName").html("Name : " + rowData[5]);
        }
        if(rowData[6] == null){
            $("#chequePhone").html("Phone : ");
        }else{
            $("#chequePhone").html("Phone : " + rowData[6]);
        }
        $("#processed_status").val("CASHED");
        $("#remarks").val("");
        getBankSelection();
        $('#formSessionRenewFixedDeposit').modal('show');
    });
    $("table tbody").on("click", "a.repay_bounced", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $.ajax({
            url: '<?php echo base_url() ?>service/Cheque_data/get_cheque_details',
            type: 'GET',
            data: {id:selected_id},
            success: function (data) {
                $("#parent").val(data.data.id);
                $("#amount").val(data.data.amount);
                $("#amount1").val(data.data.amount);
                $('#formSessionRePay').modal('show');
            }
        });
    });
    $("#processed_status").change(function(){
        getBankSelection();
    });
    function getBankSelection(){
        if($("#processed_status").val() == "CASHED"){
            $("#dynamic_bank_section").html(bankSelection);
            $("#bank").html(bankString);
        }else{
            $("#dynamic_bank_section").html("");
        }
    }
    $("#payment_mode").change(function(){
        var output = "";
        if($("#payment_mode").val() == "CHEQUE"){
            output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">';
            output += '<span class="span_label ">Cheque No</span>';
            output += '<div class="form-group">';
            output += '<input type="text" name="cheq_no" id="cheq_no" class="form-control"/>';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">';
            output += '<span class="span_label ">Cheque Date</span>';
            output += '<div class="form-group">';
            output += '<input type="text" readonly="" name="cheq_date" id="cheq_date" class="form-control"/>';
            output += '</div>';
            output += '</div>';
        }else if($("#payment_mode").val() == "DD"){
            output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">';
            output += '<span class="span_label ">DD No</span>';
            output += '<div class="form-group">';
            output += '<input type="text" name="cheq_no" id="cheq_no" class="form-control"/>';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">';
            output += '<span class="span_label ">DD Date</span>';
            output += '<div class="form-group">';
            output += '<input type="text" readonly="" name="cheq_date" id="cheq_date" class="form-control"/>';
            output += '</div>';
            output += '</div>';
        }else if($("#payment_mode").val() == "CARD"){
            output += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">';
            output += '<span class="span_label ">Reference No</span>';
            output += '<div class="form-group">';
            output += '<input type="text" name="cheq_no" id="cheq_no" class="form-control"/>';
            output += '</div>';
            output += '</div>';
        }
        $("#dynamic_pay_section").html(output);
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('#cheq_date').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate: today
        });
    });
    $(".saveData1").click(function () {
        var form = $(".popup-form");
        var url = "<?php echo base_url() ?>service/Cheque_data/process_cashless_payment";
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            async:false,
            success: function(data){
                data = JSON.parse(data);
                if(data.message == "error"){
                    $.toaster({priority: 'danger', title: '', message: data.viewMessage});
                }else{
                    $("#cheque_management").dataTable().fnDraw();
                    $('#formSessionRenewFixedDeposit').modal('hide');
                }
            }
        });
    });
    $(".saveData2").click(function () {
        var form = $(".popup-form1");
        var url = "<?php echo base_url() ?>service/Cheque_data/repay_payment";
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            async:false,
            success: function(data){
                data = JSON.parse(data);
                if(data.message == "error"){
                    $.toaster({priority: 'danger', title: '', message: data.viewMessage});
                }else{
                    $("#cheque_management").dataTable().fnDraw();
                    $('#formSessionRePay').modal('hide');
                }
            }
        });
    });
    function download_cashless_report(type,format){
        if(format == "Pdf"){
            window.open('<?php echo base_url() ?>service/Cheque_data/get_cashless_pdf_report?type='+type, '_blank');
        }else{
            window.open('<?php echo base_url() ?>service/Cheque_data/get_cashless_excel_report?type='+type, '_blank');
        }
    }
</script>
