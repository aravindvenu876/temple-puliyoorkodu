<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [4],
        "mData": 4,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },
        {
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [3],
            "mData": 3,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [5], "mData": 1,
            "mRender": function (data, type, row) {
                return  "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>";
            }
        }
    ];
    var action_url = $('#salary_schemes').attr('action_url');
    oTable = gridSFC('salary_schemes', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#salary_schemes").dataTable().fnDraw();
    }
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Salary_data/get_salary_scheme_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Salary Scheme</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.scheme + '</option>';
            });
            $("#filter_salary_scheme").append(string);
        }
    });
    $('#to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    detail('<?php echo base_url() ?>service/Salary_data/salary_scheme_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Salary_data/salary_scheme_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data){
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_salary_scheme'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/update_salary_scheme");
        $('#name').val(data.main.scheme);
        $('#from_date').val(convert_date(data.main.date_from));
        $('#to_date').val(convert_date(data.main.date_to));
        $('#total_amount').val(data.main.amount);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
        // $("#dynamic_asset_register").html("");
        salary_head_drop_down(data.main.id);
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('salary_scheme'); ?></th>";
        viewdata += "<td>"+data.main.scheme+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('from'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.date_from)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('to'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.date_to)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td>₹ "+data.main.amount+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('salary_head'); ?></th><th><?php echo $this->lang->line('type'); ?></th><th><?php echo $this->lang->line('amount(₹)'); ?>(₹)</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.head+"</td><td>"+v.type+"</td><td>"+v.amount+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('new_salary_scheme'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/add_salary_scheme");
        clear_form();
        $("#count").val(0);
        $("#actual").val(0);
        salary_head_drop_down(0);
    });
    function add_salary_head_dynamic(i,head_id,head_name,head_type,amount){
        $("#count").val(i);
        var output = "";
        output += '<tr><td><input type="hidden" name="type[]"  id="type_'+i+'" value="'+head_type+'" class="asset_dyn_sec_'+i+'"/>';
        output += '<input type="hidden" name="head_id[]" id="head_id_'+i+'" value="'+head_id+'" class="asset_dyn_sec_'+i+'"/>';
        output += head_name+'<span class="asterisk">*</span></td>';
        output += '<td>'+head_type+'</td>';
        if(head_id == 2 || head_id == 3){
            output += '<td><input type="number" name="amount[]" id="amount_'+i+'" min="0.0" step="0.01" value="'+amount+'" class="form-control amount" data-required="true" onkeyup="calculate_total_amount()" autocomplete="off" readonly=""></td>';
        }else{
            output += '<td><input type="number" name="amount[]" id="amount_'+i+'" min="0.0" step="0.01" value="'+amount+'" class="form-control amount" data-required="true" onkeyup="calculate_total_amount()" autocomplete="off"></td>';
        }
        output += '</tr>';
        $("#dynamic_asset_register").append(output);
        calculate_total_amount();
    }
    function salary_head_drop_down(val){  
        $("#dynamic_asset_register").html("");
        $.ajax({
            url: '<?php echo base_url() ?>service/Salary_data/get_salary_head_drop_down',
            type: 'POST',
            data:{scheme_id:val},
            async: false,
            success: function (data) {
                var j = 0;
                $.each(data.salary_heads, function (i, v) {
                    j++;
                    add_salary_head_dynamic(j,v.id,v.head,v.type,v.amount);
                });
            }
        });
    }
    function calculate_total_amount(){
        var count = $("#count").val();
        // alert(count);
        var total = 0;
        calculate_salary();
        for(var i=1;i<=count;i++){
            if($("#amount_"+i).val()){
                if($("#type_"+i).val() == "ADD"){
                    total = +total + +$("#amount_"+i).val();
                }else{
                    total = +total - +$("#amount_"+i).val();
                }
            }
        }
        $("#total_amount").val(total);
    }
    /**Static Code */
    function calculate_salary(){
        var da = calculate_da($("#amount_1").val());
        $("#amount_2").val(da);
        var pf = calculate_pf($("#amount_1").val());
        $("#amount_3").val(pf);
    }
</script>