<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [1],
        "mData": 1,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    },{
        "aTargets": [2],
        "mData": 2,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    // },{
    //     "aTargets": [5],
    //     "mData": 5,
    //     "mRender": function(data, type, row) {
    //         return "<span class='amntRight'>"+data+"</span>";
    //     }
    // },{
    //     "aTargets": [6],
    //     "mData": 'total_amount',
    //     "mRender": function(data, type, row) {
    //         return "<span class='amntRight'>"+data+"</span>";
    //     }
    // },{
    //     "aTargets": [7],
    //     "mData": 'balance_amount',
    //     "mRender": function(data, type, row) {
    //         return "<span class='amntRight'>"+data+"</span>";
    //     }
    // },{
    //     "aTargets": [8],
    //     "mData": 'total_spent',
    //     "mRender": function(data, type, row) {
    //         return "<span class='amntRight'>"+data+"</span>";
    //     }
    // },{
    //     "aTargets": [9],
    //     "mData": 0,
    //     "mRender": function(data, type, row) {
    //         return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
    //     }
    }];
    var action_url = $('#petty_cash_management').attr('action_url');
    oTable = gridSFC('petty_cash_management', action_url, aoColumnDefs);
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Bank</option>';
            $.each(data.banks, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
            $("#bank").append(string);
        }
    });
    $('#bank').on('change', function() {
        if ($("#bank").val() != "") {
            $.ajax({
                url: '<?php echo base_url() ?>service/Bank_data/get_bank_accnt_drop_down',
                data: {bank: $("#bank").val()},
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    var string = '<option value="">Select Account</option>';
                    $.each(data.accounts, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.account_no + '</option>';
                    });
                    $("#account").html(string);
                }
            });
        }
    });
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_petty_cash'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Petty_cash_data/petty_cash_add");
        clear_form();
        $("#date").val("<?php echo date('d-m-Y') ?>");
    });
    viewData('<?php echo base_url() ?>service/Petty_cash_data/petty_cash_view', function(data) {
        detail_view(data);
    });
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('petty_cash_date'); ?></th>";
        viewdata += "<td>" + convert_date(data.data.opened_date) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('petty_cash'); ?></th>";
        viewdata += "<td> ₹ " + data.data.petty_cash + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('previous_balance'); ?></th>";
        viewdata += "<td> ₹ " + data.data.prev_balance + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('total_amount'); ?></th>";
        viewdata += "<td> ₹ " + data.data.total_amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('total_amount_spent'); ?></th>";
        viewdata += "<td> ₹ " + data.data.total_spent + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('balance_amount'); ?></th>";
        viewdata += "<td> ₹ " + data.data.balance_amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank'); ?></th>";
        viewdata += "<td>" + data.data.bank_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account'); ?></th>";
        viewdata += "<td>" + data.data.account_no + "</td>";
        viewdata += "</tr>";
        if(data.data.status == "CLOSED"){
            viewdata += "<th><?php echo $this->lang->line('renewed_on'); ?></th>";
            viewdata += "<td>" + convert_date(data.data.modified_on) + "</td>";
            viewdata += "</tr>";
        }
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('date'); ?></th><th><?php echo $this->lang->line('amount'); ?></th><th><?php echo $this->lang->line('transaction_head'); ?></th><th><?php echo $this->lang->line('description'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+convert_date(v.date)+"</td><td><span class='amntRight'>₹ "+v.amount+"</span></td><td>"+v.head_eng+"</td><td>"+v.description+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
</script>
