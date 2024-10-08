<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1],
            "mData": 'staff',
            "mRender": function(data, type, row) {
                return data; 
            }
        },{
            "aTargets": [2],
            "mData": 'salary_for',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [3],
            "mData": 6,
            "mRender": function(data, type, row) {
return "<span class='amntRight'>"+data+"</span>";
			}
        },{
            "aTargets": [4],
            "mData": 'add_on',
            "mRender": function(data, type, row) {
return "<span class='amntRight'>"+data+"</span>";
			}
        },{
            "aTargets": [5],
            "mData": 'deduct',
            "mRender": function(data, type, row) {
return "<span class='amntRight'>"+data+"</span>";
			}
        },{
            "aTargets": [6],
            "mData": 12,
            "mRender": function(data, type, row) {
return "<span class='amntRight'>"+data+"</span>";
			}
        },{
            "aTargets": [7],
            "mData": 13,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [8],
            "mData": 3,
            "visible":false,
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [9], "mData": 1,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='pdf_payslip' data-placement='right' data-original-title = '<?php echo $this->lang->line('pdf_payslip'); ?>'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>"+
                    "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_payslip'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#salary_processing').attr('action_url');
    oTable = gridSFC('salary_processing', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#salary_processing").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_year_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Year</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#year").append(string);
            $("#filter_year").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#staff").append(string);
            $("#filter_staff").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_month_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Month</option>';
            var j = 0;
            $.each(data.data, function (i, v) {
                j++;
                string += '<option value="' + j + '">'+ v.name + '</option>';
            });
            $("#month").append(string);
            $("#filter_month").append(string);
        }
    });

    $("#year").change(function(){
        salary_staff_drop_down();
    });
    $("#month").change(function(){
        salary_staff_drop_down();
    });
    viewData('<?php echo base_url() ?>service/Salary_data/salary_processing_view', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var head = "PAYSLIP FOR "+data.salary.processing_time;
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><b><?php echo $this->lang->line('staff_name'); ?></b></th>";
        viewdata += "<td>"+data.staff.staff.name+"</td>";
        viewdata += "<th><b><?php echo $this->lang->line('staff_id'); ?></b></th>";
        viewdata += "<td>"+data.staff.staff.staff_id+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><b><?php echo $this->lang->line('designation'); ?></b></th>";
        viewdata += "<td>"+data.staff.staff.designation_eng+"</td>";
        viewdata += "<th><b><?php echo $this->lang->line('salary_scheme'); ?></b></th>";
        viewdata += "<td>"+data.staff.staff.salary_scheme+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<h4><?php echo $this->lang->line('allowances'); ?></h4>";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<tr class=' text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('allowance'); ?></th><th><?php echo $this->lang->line('amount'); ?></th></tr>";
        var j = 0;
        var total_allowance = 0;
        $.each(data.salary_scheme, function (i, v) {
            if(v.type == "ADD"){
                j++;
                total_allowance = +total_allowance + +v.amount;
                viewData1 += "<tr><td>"+j+"</td><td>"+v.head+"</td><td style='text-align:right'>"+v.amount+"</td></tr>";
            }
        });
        $.each(data.salary_addons, function (i, v) {
            if(v.type == "ADD"){
                j++;
                total_allowance = +total_allowance + +v.amount;
                // viewData1 += "<tr><td>"+j+"</td><td>"+v.description+"</td><td>"+v.amount+"</td></tr>";
                viewData1 += "<tr><td>"+j+"</td><td>Salary Advance</td><td style='text-align:right'>"+v.amount+"</td></tr>";
            }
        });
        j++;
        total_allowance = +total_allowance + +data.salary.extra_allowance
        viewData1 += "<tr><td>"+j+"</td><td><?php echo $this->lang->line('extra_allowance'); ?></td><td  style='text-align:right'>"+data.salary.extra_allowance+"</td></tr>";
        viewData1 += "<tr class='TblEarnings text-white'><th colspan='2'><?php echo $this->lang->line('total_earnings'); ?></th><th style='text-align:right'>"+parseFloat(total_allowance).toFixed(2)+"</th></tr>";
        viewData1 += "</table>";
        viewData1 += "<h4><?php echo $this->lang->line('deductions'); ?></h4>";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('deduction'); ?></th><th><?php echo $this->lang->line('amount'); ?></th></tr>";
        var j = 0;
        var total_allowance = 0;
        $.each(data.salary_scheme, function (i, v) {
            if(v.type == "DEDUCT"){
                j++;
                total_allowance = +total_allowance + +v.amount;
                viewData1 += "<tr><td>"+j+"</td><td>"+v.head+"</td><td  style='text-align:right'>"+v.amount+"</td></tr>";
            }
        });
        $.each(data.salary_addons, function (i, v) {
            if(v.type == "DEDUCT"){
                j++;
                total_allowance = +total_allowance + +v.amount;
                viewData1 += "<tr><td>"+j+"</td><td>Salary Advance</td><td style='text-align:right'>"+v.amount+"</td></tr>";
                // viewData1 += "<tr><td>"+j+"</td><td>"+v.description+"</td><td>"+v.amount+"</td></tr>";
            }
        });
        j++;
        total_allowance = +total_allowance + +data.salary.salary_reduction
        viewData1 += "<tr><td>"+j+"</td><td><?php echo $this->lang->line('lop'); ?></td><td  style='text-align:right'>"+data.salary.salary_reduction+"</td></tr>";
        j++;
        total_allowance = +total_allowance + +data.salary.extra_deduction
        viewData1 += "<tr><td>"+j+"</td><td><?php echo $this->lang->line('extra_deduction'); ?></td><td  style='text-align:right'>"+data.salary.extra_deduction+"</td></tr>";
        viewData1 += "<tr class='TblEarnings text-white'><th colspan='2'><?php echo $this->lang->line('total_deduction'); ?></th><th  style='text-align:right'>"+parseFloat(total_allowance).toFixed(2)+"</th></tr>";
        viewData1 += "<tr><th colspan='3'></td></tr>";
        viewData1 += "<tr class='TblEarnings text-white'><th colspan='2'><?php echo $this->lang->line('total_payable'); ?></th><th  style='text-align:right'>"+data.salary.payable_salary+"</th></tr>";
        viewData1 += "<tr><th colspan='3'></td></tr>";
        viewData1 += "<tr><th colspan='3'></td></tr>";
        viewData1 += "<tr class='TblEarnings text-white'><th colspan='2'><?php echo $this->lang->line('total_pf_taken_till_this_salary'); ?></th><th  style='text-align:right'>"+data.total_pf_taken+"</th></tr>";
        viewData1 += "</table>";
        $("#viewModalTitle").html(head);
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('new_salary_scheme'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/add_salary_processing");
        clear_form();
        $("#dynamic_asset_register").html("");
        $("#count").val(0);
    });
    function add_salary_processing_dynamic(i,staff_id,staff_name,salary,leave_amount,advance_amount,advance_deduction,scheme){
        $("#count").val(i);
        var output = "";
        var total_amount = +salary - +leave_amount + +advance_deduction - +advance_amount;
        output += '<tr>';
        output += '<input type="hidden" name="staff_'+i+'" id="staff_'+i+'" value="'+staff_id+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="scheme_'+i+'" id="scheme_'+i+'" value="'+scheme+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="salary_'+i+'" id="salary_'+i+'" value="'+roundUp(salary)+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="advance_amount_'+i+'" id="advance_amount_'+i+'" value="'+roundUp(advance_amount)+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="prev_balance_'+i+'" id="prev_balance_'+i+'" value="'+roundUp(advance_deduction)+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="leave_amount_'+i+'" id="leave_amount_'+i+'" value="'+roundUp(leave_amount)+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="total_amount_'+i+'" id="total_amount_'+i+'" value="'+roundUp(total_amount)+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<td><input type="checkbox" name="staff_select_'+i+'" id="staff_select_'+i+'" checked=""/></td>';
        output += '<td><label for="staff_select_'+i+'">'+staff_name+'</label></td>';
        output += '<td  style="text-align:right">'+roundUp(salary)+'</td>';
        output += '<td  style="text-align:right">'+roundUp(advance_amount)+'</td>';
        output += '<td style="text-align:right">'+roundUp(advance_deduction)+'</td>';
        output += '<td style="text-align:right">'+roundUp(leave_amount)+'</td>';
        output += '<td><input type="number" name="allowance_'+i+'" id="allowance_'+i+'" min="0" value="0" class="form-control amount" data-required="true" onkeyup="calculate_payable('+i+')" autocomplete="off"></td>';
        output += '<td><input type="number" name="deduction_'+i+'" id="deduction_'+i+'" min="0" value="0" class="form-control amount" data-required="true" onkeyup="calculate_payable('+i+')" autocomplete="off"></td>';
        output += '<td  style="text-align:right" id="payable_salary_'+i+'">'+roundUp(total_amount)+'</td>';
        output += '</tr>';
        $("#dynamic_asset_register").append(output);
        calculate_payable(i);
        // calculate_total_amount();
    }
    function salary_staff_drop_down(){ 
        $("#count").val(0); 
        var month = $("#month").val();
        var year  = $("#year").val();
        if(month != "" && year != ""){
            $("#dynamic_asset_register").html("");
            $.ajax({
                url: '<?php echo base_url() ?>service/Salary_data/get_staff_salary_drop_down',
                type: 'POST',
                data:{month:month,year:year},
                success: function (data) {
                    var j = 0;
                    var k = 0;
                    $.each(data, function (i, v) {
                        k++;
                        if(v.salary_status == 0){
                            j++;
                            add_salary_processing_dynamic(j,v.id,v.name,v.amount,v.leaveDeductableAmount,v.advancePaid,v.advanceDeduction,v.salary_scheme);
                        }
                    });
                    if(k == 0){
                        $("#dynamic_asset_register").html("<tr><th colspan='15'>No staff available</th></tr>");
                    }else if($j == 0){
                        $("#dynamic_asset_register").html("<tr><th colspan='15'>All staff processed for this month</th></tr>");
                    }
                }
            });
        }
    }
    function calculate_payable(val){
        var total_amount = +$("#total_amount_"+val).val() + +$("#allowance_"+val).val() - +$("#deduction_"+val).val();
        total_amount = roundUp(total_amount);
        $("#payable_salary_"+val).html(total_amount);
    }
    $("table tbody").on("click", "a.pdf_payslip", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        window.open('<?php echo base_url() ?>service/Salary_data/print_salary_invoice_in_pdf?salary_process_id='+selected_id, '_blank');       
    });
</script>