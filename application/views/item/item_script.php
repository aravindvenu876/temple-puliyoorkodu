<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var selectedAssetIds = {};
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [5],"mData": 5,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>" + data + ' ' + row[12] + "</span>";
        }
    },{
        "aTargets": [6],"mData": 6,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>INR "+data+"/-</span>";
        }
    },{
        "aTargets": [7],"mData": 7,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>INR "+data+"/-</span>";
        }
    },{
        "aTargets": [8],"mData": 8,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>" + data + ' ' + row[12] + "</span>";
        }
    },{
        "aTargets": [9],"mData": 9,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>" + data + ' ' + row[12] + "</span>";
        }
    },{
        "aTargets": [10],"mData": 10,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>" + data + ' ' + row[12] + "</span>";
        }
    },{
        "aTargets": [11],"mData": 11, 
        "mRender": function (data, type, row) {
            if (data == 1) 
                return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
            else if (data != '') 
                return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        }
    },{
        "aTargets": [12], "mData": 11, 
        "mRender": function (data, type, row) {
            var btn = "";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
            if (data == 0){
                btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
            }
            return btn;
        }
    }];
    var action_url = $('#item_master').attr('action_url');
    oTable = gridSFC('item_master', action_url, aoColumnDefs);
    function get_fixed(){
        $("#item_master").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_counter_prasadam_avialability_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#counter_sale").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Item_category_data/get_item_category_drop_down'
        , type: 'GET'
        , success: function (data) {
            var string = '<option value="">Select Category</option>';
            $.each(data.item_category, function (i, v) {
                string += '<option value="' + v.id + '">' + v.category + '</option>';
            });
            $("#category").append(string);
            $("#filter_category").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Unit_data/get_unit_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Unit</option>';
            $.each(data.units, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.unit +'('+ v.notation +')' + '</option>';
            });
            $("#unit").append(string);
        }
    });
    detail('<?php echo base_url() ?>service/Item_data/Item_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Item_data/Item_edit', function (data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_prasadam'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Item_data/item_update");
        $('#category').val(data.editData.item_category_id);
        $('#quantity').val(data.editData.defined_quantity);
        $('#cost').val(data.editData.cost);
        $('#price').val(data.editData.price);
        $('#quantity_available').val(data.editData.quantity_available);
        $('#quantity_used').val(data.editData.quantity_used);
        $('#item_eng').val(data.editData.item_eng);
        $('#item_alt').val(data.editData.item_alt);
        $('#description_eng').val(data.editData.description_eng);
        $('#description_alt').val(data.editData.description_alt);
        $('#counter_sale').val(data.master.counter_sale);
        var j=0;
        $.each(data.assets, function (i, v) {
            j++;
            add_asset_dynamic(j);
            assets_drop_down(j,v.asset_id);
            get_asset_rent(j);
            $('#quantity_'+j).val(v.quantity);
        });
        $('#count').val(j);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
        $('#account_name1').val(data.editData.ledger_id);
    }

    function detail_view(data) {
        var viewdata = "";
        var currency = '<?php echo CURRENCY ?>';
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_category_eng'); ?></th>";
        viewdata += "<td>" + data.editData.category_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_category_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.category_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_english'); ?></th>";
        viewdata += "<td>" + data.editData.item_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_alternate'); ?></th>";
        viewdata += "<td>" + data.editData.item_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('prasadam_quantity'); ?></th>";
        viewdata += "<td>" + data.editData.defined_quantity + " " + data.editData.unit_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Counter Sale Availability</th>";
        if(data.master.counter_sale == "1"){
            viewdata += "<td>Available</td>";
        }else{
            viewdata += "<td>Not Available</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('cost'); ?></td>";
        viewdata += "<td>" + data.editData.cost + "</th>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('price'); ?></td>";
        viewdata += "<td>" + data.editData.price + "</th>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('quantity_available'); ?></th>";
        viewdata += "<td>" + data.editData.quantity_available + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('quantity_used'); ?></th>";
        viewdata += "<td>" + data.editData.quantity_used + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_ledger'); ?></th>";
        viewdata += "<td>" + data.editData.ledger_name + "</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.assets, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td>"+v.quantity+" "+v.notation+"</td></tr>";
        });
        if(j == 0){
            viewData1 += "<tr><td colspan='3' style='text-align:center'><b><i>No Assets Mapped</i></b></td></tr>";
        }
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_prasadam'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Item_data/item_add");
        clear_form();
        $(".asset_dyn").remove();
        $("#count").val(0);
        $("#actual").val(0);
        selectedAssetIds = {};
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
    function add_asset_dynamic(){
        var j = +$("#actual").val() + +1;
        $("#actual").val(j);
        var i = +$("#count").val() + +1;
        $("#count").val(i);
        var output = "";
        output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="asset_'+i+'" id="asset_'+i+'" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="number" name="quantity_'+i+'" id="quantity_'+i+'" min=".1" class="form-control parsley-validated" data-required="true" autocomplete="off">';
        output += '</div>';
        output += '</div>'; 
        output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';  
        output += '<input type="text" name="unit_'+i+'" id="unit_'+i+'" class="form-control" readonly autocomplete="off">';  
        output += '</div>';  
        output += '</div>';  
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_asset_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        $("#dynamic_asset_register").append(output);
        $('form').parsley().isValid();
        assets_drop_down(i);
        selectedAssetIds[i] = "0";
    }
    function remove_asset_dynamic(i){
        var j = +$("#actual").val() - +1;
        $("#actual").val(j);
        $(".asset_dyn_sec_"+i).remove();
        selectedAssetIds[i] = "0";
    }
    function assets_drop_down(i,val){       
        $.ajax({
            url: '<?php echo base_url() ?>service/Asset_data/get_asset_drop_down',
            type: 'GET',
            async:false,
            success: function (data) {
                var string = '<option value="">Select</option>';
                $.each(data.assets, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.asset_name + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.asset_name + '</option>';
                    }
                });
                $("#asset_"+i).html(string);
            }
        });
    }
    function get_asset_rent(val){
        var assetId = $("#asset_"+val).val();
        var checkFlag = '0';
        $.each(selectedAssetIds, function (i, v) {
            if(v == assetId){
                checkFlag = '1';
                $("#asset_"+val).val("");
                $("#quantity_"+val).html("");
                $("#unit_"+val).val("");
                bootbox.alert("This item is already selected in this form");
                return false; 
            }
        });
        if(checkFlag == '0'){
            selectedAssetIds[val] = assetId;
            $.ajax({
                url: '<?php echo base_url() ?>service/Asset_data/assets_edit',
                type: 'GET',
                data:{id:$("#asset_"+val).val()},
                async:false,
                success: function (data) {
                    $("#unit_"+val).val(data.editData.unit_eng+"("+data.editData.notation+")");
                }
            });
        }
    }
</script>