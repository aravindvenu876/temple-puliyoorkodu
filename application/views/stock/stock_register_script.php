<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2], "mData": 2,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [3], "mData": 2,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'>"+
                        "<i class='fa fa-eye' aria-hidden='true'></i>" +
                        "</a>";
            }
        }
    ];
    var action_url = $('#stock_register').attr('action_url');
    oTable = gridSFC('stock_register', action_url, aoColumnDefs);

    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
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
    viewData('<?php echo base_url() ?>service/Stock_data/stock_register_view', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('entry_date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.entry_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>"+data.main.process_type+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('item'); ?></th><th><?php echo $this->lang->line('cost'); ?>(₹)</th><th><?php echo $this->lang->line('quantity'); ?></th><th><?php echo $this->lang->line('net'); ?>(₹)</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.name+"</td><td>"+v.rate+"</td><td>"+v.quantity+"</td><td> "+v.total_rate+"</td></tr>";
        });
       viewData1 += "</tbody>";
       viewData += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_asset_prasadam_stock'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Stock_data/stock_register_add");
        clear_form();
        $("#count").val(1);
        item_type_drop_down(1);
    });
    function add_item_dynamic(){
        var i = +$("#count").val() + +1;
        $("#count").val(i);
        var output = "";
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="stock_type_'+i+'" id="stock_type_'+i+'" class="form-control parsley-validated item" data-required="true" onchange="item_drop_down('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="category_'+i+'" id="category_'+i+'" class="form-control parsley-validated item" data-required="true" onchange="get_item_rent('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="number" name="cost_'+i+'" id="cost_'+i+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off">';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="number" name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off">';
        output += '</div>';
        output += '</div>'; 
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';  
        output += '<input type="text" name="unit_'+i+'" id="unit_'+i+'" class="form-control" readonly autocomplete="off">';  
        output += '</div>';  
        output += '</div>';  
        output += '<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12 item_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_item_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        $("#dynamic_item_register").append(output);
        item_type_drop_down(i);
    }
    function remove_item_dynamic(i){
        $(".item_dyn_sec_"+i).remove();
    }
    function item_type_drop_down(i){
        $.ajax({
            url: '<?php echo base_url() ?>service/Rest_shared/get_stock_item_drop_down',
            type: 'GET',
            async: false,
            success: function (data) {
                var string = '';
                $.each(data.data, function (i, v) {
                    string += '<option value="' + v.id + '">'+ v.name + '</option>';
                });
                $("#stock_type_"+i).html(string);
            }
        });
        item_drop_down(i);
    }
    function item_drop_down(i){ 
        if($("#stock_type_"+i).val() == "Prasadam"){
            $.ajax({
                url: '<?php echo base_url() ?>service/Item_register_data/get_item_drop_down',
                type: 'GET',
                async: false,
                success: function (data) {
                    var string = '';
                    $.each(data.item, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.name + '</option>';
                    });
                    $("#category_"+i).html(string);
                }
            });
        }else if($("#stock_type_"+i).val() == "Asset"){
            $.ajax({
                url: '<?php echo base_url() ?>service/Asset_data/get_asset_drop_down',
                type: 'GET',
                async: false,
                success: function (data) {
                    var string = '';
                    $.each(data.assets, function (i, v) {
                        string += '<option value="' + v.id + '">'+ v.asset_name + '</option>';
                    });
                    $("#category_"+i).html(string);
                }
            });
        }
        get_item_rent(i);
    }
    function get_item_rent(val){
        if($("#stock_type_"+val).val() == "Prasadam"){
            $.ajax({
                url: '<?php echo base_url() ?>service/Item_register_data/item_edit',
                type: 'GET',
                async: false,
                data:{id:$("#category_"+val).val()},
                success: function (data) {
                    $("#unit_"+val).val(data.editData.unit_eng+"("+data.editData.notation+")");
                    $("#cost_"+val).val(data.editData.cost);
                }
            });
        }else if($("#stock_type_"+val).val() == "Asset"){
            $.ajax({
                url: '<?php echo base_url() ?>service/Asset_data/assets_edit',
                type: 'GET',
                async: false,
                data:{id:$("#category_"+val).val()},
                success: function (data) {
                    $("#unit_"+val).val(data.editData.unit_eng+"("+data.editData.notation+")");
                    $("#cost_"+val).val(data.editData.price);
                }
            });
        }
    }
</script>




