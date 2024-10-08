<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
		{
        "aTargets": [3],
        "mData": 'total',
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [4],
        "mData": 'discount',
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [5],
        "mData": 'net',
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [6],
        "mData": 6,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    },{
        "aTargets": [8], "mData": 8,
        "mRender": function (data, type, row) {
            if(data == '0'){
                return "<a class='btn btn-warning btn-sm btn_active btn_print_html'><?php echo $this->lang->line('print_outpass'); ?></a>";
            }else{
                return "<a class='btn btn-warning btn-sm btn_active btn_duplicate_html'><?php echo $this->lang->line('duplicate_outpass'); ?></a>";
            }
        },
    },{
        "aTargets": [9], "mData": 1,
        "mRender": function (data, type, row) {
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
        }
    }
    ];
    var action_url = $('#asset_rent').attr('action_url');
    oTable = gridSFC('asset_rent', action_url, aoColumnDefs);
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        startDate: today,
        autoclose: true
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
    viewData('<?php echo base_url() ?>service/Asset_rent_data/asset_rent_edit', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('rented_date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('status'); ?></th>";
        viewdata += "<td>"+data.main.rent_status+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('rented_by'); ?></th>";
        viewdata += "<td>"+data.main.rented_by+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>"+data.main.phone+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>"+data.main.address+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('total_amount'); ?></th>";
        if(data.main.rent_status == "Returned"){
            viewdata += "<td>"+data.main.actual_total+"</td>";
        }else{
            viewdata += "<td>"+data.main.total+"</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('discount'); ?></th>";
        if(data.main.rent_status == "Returned"){
            viewdata += "<td>"+data.main.actual_discount+"</td>";
        }else{
            viewdata += "<td>"+data.main.discount+"</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('net_amount'); ?></th>";
        if(data.main.rent_status == "Returned"){
            viewdata += "<td>"+data.main.actual_net+"</td>";
        }else{
            viewdata += "<td>"+data.main.net+"</td>";
        }
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        if(data.main.rent_status == "Returned"){
            viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('rented_Quantity'); ?></th><th><?php echo $this->lang->line('Returned_Quantity'); ?></th><th> <?php echo $this->lang->line('rent_rate'); ?>(₹)</th><th> <?php echo $this->lang->line('damaged_quantity'); ?></th><th> <?php echo $this->lang->line('damaged_rate'); ?>(₹)</th><th> <?php echo $this->lang->line('total'); ?>(₹)</th></tr></thead>";
            viewData1 += "<tbody>";
            var j = 0;
            $.each(data.details, function (i, v) {
                j++;
                viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td>"+v.quantity+"</td><td>"+v.returned_quantity+"</td><td>"+v.returned_rate+"</td><td>"+v.scrapped_quantity+"</td><td>"+v.scrapped_rate+"</td><td>"+v.total_cost+"</td></tr>";
            });
        }else{
            viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th> <?php echo $this->lang->line('rent_rate'); ?>(₹)</th><th><?php echo $this->lang->line('quantity'); ?></th><th><?php echo $this->lang->line('total'); ?>(₹)</th></tr></thead>";
            viewData1 += "<tbody>";
            var j = 0;
            $.each(data.details, function (i, v) {
                j++;
                viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td>"+v.rate+"</td><td>"+v.quantity+"</td><td>"+v.cost+"</td></tr>";
            });
        }
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('new_rent_form');?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Asset_rent_data/asset_rent_add");
        clear_form();
        $(".asset_dyn").remove();
        $("#count").val(1);
        $("#discount").val(0.00);
        $("#actual").val(1);
        assets_drop_down(1);
    });
    
    function add_asset_dynamic(){
        var j = +$("#actual").val() + +1;
        if(j>5){
            bootbox.alert("Sorry maximum of 5 assets can only be rented at a time");
        }else{
            $("#actual").val(j);
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
            output += '<input type="number" name="cost_'+i+'" id="cost_'+i+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off">';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<select name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off" onchange="calculate_total_rate('+i+')"></select>';
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
                $("#cost_"+val).val(data.editData.rent_price);
                $("#unit_"+val).val(data.editData.unit_eng+"("+data.editData.notation+")");
                    if (data.editData.quantity_available == "") {
                        var string = '<option value="">No Quantity</option>';
                    
                    }
                   else {
                    var string = '<option value="">Select Quantity</option>';
                    for (i = 1; i <= data.editData.quantity_available; i++) {
                        string += '<option value="' + i + '">' + i + '</option>';
                    }
                }
                $("#quantity_"+val).html(string);        
                calculate_total_rate(val);
            }
        });
    }
    function calculate_total_rate(val){
        if($("#cost_"+val).val() == "" && $("#quantity_"+val).val() == ""){
            $("#total_rate_"+val).val('0');
        }else{
            var total = $("#cost_"+val).val() * $("#quantity_"+val).val();
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
                var dec = parseFloat(total,10).toFixed(2);
            }
        }
        $("#total_amount").val(dec);
        var net= +total - +$("#discount").val();
        var dec_net = parseFloat(net,10).toFixed(2);
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
    $("table tbody").on("click", "a.btn_print_html", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        var msg = 'Are you sure you want to generate outpass for this entry?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Voucher_data/generate_outpass/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                    success: function (data) {
                        if (data.message == 'no enough privilege') {
                            $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                            return;
                        }
                        if (data.message == 'success') {
                            $.toaster({priority: 'success', title: '', message: "Outpass Generated"});
                            $("#" + data.grid).dataTable().fnDraw();
                            var w = window.open('report:blank');
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                        } else {
                            $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                            $("#" + data.grid).dataTable().fnDraw();
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
    $("table tbody").on("click", "a.btn_duplicate_html", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        var msg = 'Are you sure you want to generate duplicate outpass for this entry?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Voucher_data/generate_duplicate_outpass/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                    success: function (data) {
                        if (data.message == 'no enough privilege') {
                            $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                            return;
                        }
                        if (data.message == 'success') {
                            $.toaster({priority: 'success', title: '', message: "Outpass Generated"});
                            $("#" + data.grid).dataTable().fnDraw();
                            var w = window.open('report:blank');
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                        } else {
                            $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                            $("#" + data.grid).dataTable().fnDraw();
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });

    $("table tbody").on("click", "a.outpass", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        var msg = 'Are you sure you want to generate Outpass for this entry?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Asset_rent_data/generate_outpass/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                    success: function (data) {
                          
                        if (data.message == 'success') {
                            alert(3);
                            $.toaster({priority: 'success', title: '', message: "Outpass Generated"});
                            $("#" + data.grid).dataTable().fnDraw();
                            var w = window.open('report:blank');
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                        } else {
                            alert(12);
                            $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                            $("#" + data.grid).dataTable().fnDraw();
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
</script>




