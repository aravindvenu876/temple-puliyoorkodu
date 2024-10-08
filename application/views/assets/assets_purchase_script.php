<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
        "aTargets": [1],
        "mData": 1,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    },{
        "aTargets": [3],
        "mData": 3,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [4],
        "mData": 4,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [5],
        "mData": 5,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },
        {
            "aTargets": [6], "mData": 5,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#asset_purchase').attr('action_url');
    oTable = gridSFC('asset_purchase', action_url, aoColumnDefs);
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
            url: '<?php echo base_url() ?>service/Purchase_data/get_name_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Supplier</option>';
                $.each(data.name, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.name + '</option>';
                });
                $("#name").html(string);
            }
        });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_stock_register_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    viewData('<?php echo base_url() ?>service/Purchase_data/asset_purchase_edit', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.purchase_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<td><?php echo $this->lang->line('purchase_bill_no'); ?></td>";
        viewdata += "<td>"+data.main.purchase_bill_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('purchased_by'); ?></th>";
        viewdata += "<td>"+data.main.purchased_by+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td>"+data.main.amount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('discount'); ?></th>";
        viewdata += "<td>"+data.main.discount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('net_amount'); ?></th>";
        viewdata += "<td>"+data.main.net+"</td>";
        viewdata += "</tr>";
    
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('unit'); ?>(₹) </th><th><?php echo $this->lang->line('quantity'); ?></th><th><?php echo $this->lang->line('total'); ?>(₹)</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td> "+v.unit_rate+"</td><td>"+v.quantity+"</td><td> "+v.total_rate+"</td></tr>";
        });
        viewData1 += "</tbody>";
       viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('purchase_form'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Purchase_data/asset_purchase_add");
        clear_form();
        $(".asset_dyn").remove();
        $("#count").val(1);
        $("#actual").val(1);
        assets_drop_down(1);
    });
    $(".saveData1").click(function () {
        $(".popup-form").attr('action', "<?php echo base_url() ?>service/Purchase_data/supplier_add");
        $(".popup-form").submit();
        get_added_suppliers();
    });
    function get_added_suppliers(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Purchase_data/get_name_drop_down',
            type: 'GET',
            async:false,
            success: function (data) {
                var string = '<option value="">Select Supplier</option>';
                $.each(data.name, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.name + '</option>';
                });
                $("#name").html(string);
            }
        });
    }
    function add_asset_dynamic(){
        var j = +$("#actual").val() + +1;
            //$("#actual").val(j);
            var i = +$("#count").val() + +1;
            $("#count").val(i);
            var output = "";
            output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<select name="asset_'+i+'" id="asset_'+i+'" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent('+i+')"></select>';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<input type="number" name="rate_'+i+'" id="rate_'+i+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off" onkeyup="calculate_total_rate('+i+')">';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<input type="number" name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off" onkeyup="calculate_total_rate('+i+')">';
            output += '</div>';
            output += '</div>'; 
            output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';  
            output += '<input type="text" name="unit_'+i+'" id="unit_'+i+'" class="form-control" readonly autocomplete="off">';  
            output += '</div>';  
            output += '</div>';  
            output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">'; 
            output += '<input type="number" name="total_rate_'+i+'" id="total_rate_'+i+'" min="1" class="form-control" readonly autocomplete="off">'; 
            output += '</div>'; 
            output += '</div>'; 
            output += '<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<button type="button" class="btn btn-danger" onclick="remove_asset_dynamic('+i+')"><i class="fa fa-times"></i></button>';
            output += '</div>';
            output += '</div>';
            $("#dynamic_asset_register").append(output);
            assets_drop_down(i);
        
    }
    function remove_asset_dynamic(i){
        var j = +$("#actual").val() - +1;
        $("#actual").val(j);
        $(".asset_dyn_sec_"+i).remove();
        calculate_total_amount();
    }
          
    
    function assets_drop_down(i){       
        $.ajax({
            url: '<?php echo base_url() ?>service/Asset_data/get_asset_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select</option>';
                $.each(data.assets, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.asset_name + '</option>';
                });
                $("#asset_"+i).html(string);
            }
        });
    }
    function get_asset_rent(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/Asset_data/assets_edit',
            type: 'GET',
            data:{id:$("#asset_"+val).val()},
            success: function (data) {
                $("#unit_"+val).val(data.editData.unit_eng+"("+data.editData.notation+")");
                calculate_total_rate(val);
            }
        });
    }
    function calculate_total_rate(val){
        if($("#rate_"+val).val() == "" && $("#quantity_"+val).val() == ""){
            $("#total_rate_"+val).val('0');
        }else{
            var total = $("#rate_"+val).val() * $("#quantity_"+val).val();
            $("#total_rate_"+val).val(total);
        }
        calculate_total_amount();
    }
    function calculate_total_amount(){
        var count = $("#count").val();
        var total = 0.00;
        for(var i=1;i<=count;i++){
            if($("#total_rate_" + i).length != 0) {
                total = +total + +$("#total_rate_"+i).val();
            }
        }
        var dec = parseFloat(total,10).toFixed(2);
        $("#total_amount").val(dec);
        var net_amount=+total - +$("#discount").val();
        var dec_net = parseFloat(net_amount,10).toFixed(2);
        $("#net_amount").val(dec_net);

    }
    function calculate_net_rate(){
        var net_amount=+$("#total_amount").val() - +$("#discount").val();
        if(net_amount < 0){
            $("#discount").val("0");
            var dec_net = parseFloat($("#total_amount").val(),10).toFixed(2);
            $("#net_amount").val(dec_net);
        }else{
            var dec_net = parseFloat(net_amount,10).toFixed(2);
            $("#net_amount").val(dec_net);
        }
    }
    
</script>




