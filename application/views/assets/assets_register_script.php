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
            "aTargets": [4], "mData": 1,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'>"+
                        "<i class='fa fa-eye' aria-hidden='true'></i>" +
                        "</a>";
            }
        }
    ];
    var action_url = $('#asset_register').attr('action_url');
    oTable = gridSFC('asset_register', action_url, aoColumnDefs);
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
    viewData('<?php echo base_url() ?>service/Asset_register_data/asset_register_edit', function (data) {
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
        if(data.main.description == null){
            var nil="";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('description'); ?></th>";
        viewdata += "<td>"+nil+"</td>";
        viewdata += "</tr>";
        }
        else{
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('description'); ?></th>";
        viewdata += "<td>"+data.main.description+"</td>";
        viewdata += "</tr>";
        }
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('cost'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th><th><?php echo $this->lang->line('net'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td> ₹ "+v.rate+"</td><td>"+v.quantity+"</td><td>INR "+v.total_rate+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('asset_stock'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Asset_register_data/asset_register_add");
        clear_form();
        $("#count").val(1);
        assets_drop_down(1);
    });
    function add_asset_dynamic(){
        var i = +$("#count").val() + +1;
        $("#count").val(i);
        var output = "";
        output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="asset_'+i+'" id="asset_'+i+'" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="number" min="0" name="cost_'+i+'" id="cost_'+i+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off">';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="number" name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off">';
        output += '</div>';
        output += '</div>'; 
        output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';  
        output += '<input type="text" name="unit_'+i+'" id="unit_'+i+'" class="form-control" readonly autocomplete="off">';  
        output += '</div>';  
        output += '</div>';  
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_asset_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        $("#dynamic_asset_register").append(output);
        assets_drop_down(i);
    }
    function remove_asset_dynamic(i){
        $(".asset_dyn_sec_"+i).remove();
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
                $("#cost_"+val).val(data.editData.price);
            }
        });
    }
</script>




