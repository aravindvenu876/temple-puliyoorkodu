<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
		{
            "aTargets": [4],"mData": 4,"mRender": function(data, type, row) {
                return "<span class='amntRight'>"+data+"</span>";
            }
        }, {
            "aTargets": [5],"mData": 5,"mRender": function(data, type, row) {
                return "<span class='amntRight'>"+data+"%</span>";
            }
        }, {
            "aTargets": [6],"mData": 6,"mRender": function(data, type, row) {
                return convert_date(data);
            }
        }, {
            "aTargets": [7],"mData": 7,"mRender": function(data, type, row) {
                return "<span class='amntRight' style='text-align: left;'>"+data+"</span>";
            }
        }, {
            "aTargets": [8],"mData": 8,"mRender": function(data, type, row) {
                return convert_date(data);
            }
        }, {
            "aTargets": [9],"mData": 'maturity_status',"mRender": function(data, type, row) {
                if (data == 1) 
                    return "<a class='btn btn-warning btn-sm renew btn_active'>Renew</a>";
                else if (data == 0) 
                    return "<b>Not Matured</b>";
                else if (data == 2) 
                    return "<b>Renewed</b>";
                else if (data == 4) 
                    return "<b>FB Break</b>";
            }
        }, {
            "aTargets": [11],"mData": 9,"mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title='View Data'><i class='fa fa-eye'></i></a>";
                // btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='remv_btn_datatable' data-placement='right' data-original-title='Delete Data'><i class='fa fa-window-close'></i></a>";
                return btn;
            }
        }
    ];
    var action_url = $('#bank_fixed_deposits').attr('action_url');
    oTable = gridSFC('bank_fixed_deposits', action_url, aoColumnDefs);
    function get_fixed(){
        $("#bank_fixed_deposits").dataTable().fnDraw();
    }
     var date = new Date();
     var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
     var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#account_created_on').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        endDate: today,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#maturity_date').datepicker('setStartDate', minDate);
    });
    $('#maturity_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#account_created_on').datepicker('setEndDate', maxDate);
    });
    // var date = new Date();
    // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    // var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#renew_account_created_on').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        // startDate: today
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#renew_maturity_date').datepicker('setStartDate', minDate);
    });
    $('#renew_maturity_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#renew_account_created_on').datepicker('setEndDate', maxDate);
    });
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
    $('#filter_transaction_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_account_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#account_type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Bank_data/get_bank_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Bank</option>';
            $.each(data.banks, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.bank + '</option>';
            });
            $("#filter_bank").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Bank_data/fixed_deposit_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Bank_data/fixed_deposit_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_fixed_deposit_detail'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/fixed_deposit_update");
        $('#bank').val(data.editData.bank_id);
        $('#deposit').val(data.editData.amount);
        $('#account_no').val(data.editData.account_no);
        $('#interest').val(data.editData.interest);
        $('#account_created_on').val(convert_date(data.editData.account_created_on));
        $('#maturity_date').val(convert_date(data.editData.maturity_date));
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank_english'); ?></th>";
        viewdata += "<td>" + data.editData.bank_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('bank_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.bank_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_no'); ?></th>";
        viewdata += "<td>" + data.editData.account_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('deposit_status'); ?></th>";
        viewdata += "<td>" + data.editData.deposit_status + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('deposit'); ?></th>";
        viewdata += "<td> ₹ " + data.editData.amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('interest'); ?></th>";
        viewdata += "<td>" + data.editData.interest + "%</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('maturity_date'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.maturity_date) + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('deposited_on'); ?></th>";
        viewdata += "<td>" + convert_date(data.editData.account_created_on) + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_fixed_deposit_detail'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Bank_data/fixed_deposit_add");
        clear_form();
        $.ajax({
			url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
			type: 'GET',
			async: false,
			success: function(data) {
				var string = '<option value="">Select Account Head</option>';
				$.each(data.account_head, function(i, v) {
					string += '<option value="' + v.id + '">' + v.head + '</option>';
				});
				$("#account_name1").html(string);
			}
		});
    });
    $("table tbody").on("click", "a.renew", function () {
        var grid        = $(this).closest("table");
        var rowData     = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        $("#deposit_id").val(rowData[0]);
        $("#renew_bank").val(rowData[2]);
        $("#renew_acc_no").val(rowData[4]);
        $.ajax({
			url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
			type: 'GET',
			async: false,
			success: function(data) {
				var string = '<option value="">Select Account Head</option>';
				$.each(data.account_head, function(i, v) {
					string += '<option value="' + v.id + '">' + v.head + '</option>';
				});
				$("#renew_account_name1").html(string);
			}
		});
        $('#formSessionRenewFixedDeposit').modal('show');
    });
    $("form.popup-form1").submit(function(e) {
		$(".load").show();
        e.preventDefault();
        var form = $(".popup-form1");
        var url = "<?php echo base_url() ?>service/Bank_data/renew_fixed_deposit";
        if ($(this).parsley('validate')) {
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data){
                    data = JSON.parse(data);
                    if(data.message == "error"){
                        $.toaster({priority: 'danger',title: '',message: data.viewMessage});
						$(".load").hide();
                    }else{
                        $("#bank_fixed_deposits").dataTable().fnDraw();
                        $('#formSessionRenewFixedDeposit').modal('hide');
						$(".load").hide();
                    }
                }
            });
        } else {
            console.log($(this).parsley('error'));
			$(".load").hide();
        }
    });
    $(".saveData1").click(function () {
        $(".popup-form1").submit();
    });
</script>
