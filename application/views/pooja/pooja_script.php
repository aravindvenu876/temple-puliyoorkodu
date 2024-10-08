<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var selectedAssetIds = {};
    var selectedPrasadamIds = {};
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
               return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [5], "mData": 5,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "Yes";
                else if (data != '')
                    return "No";
            }
        },{
            "aTargets": [7], "mData": 7,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [8], "mData": 7,
            "mRender": function (data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
                if (data == 0){
                    btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
                }
                return btn;
            }
        }
    ];
    var action_url = $('#pooja').attr('action_url');
    oTable = gridSFC('pooja', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#pooja").dataTable().fnDraw();
    }

    $.ajax({
        url: '<?php echo base_url() ?>service/Pooja_category_data/get_pooja_category_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Category</option>';
			var currentTemple = '<?php echo $this->session->userdata('temple') ?>';
            $.each(data.pooja_category, function (i, v) {
				if(currentTemple == 1){
					var templeName = "";
					if(v.temple_id != 1){
						templeName = "("+ v.temple +")";
					}
                	string += '<option value="' + v.id + '">'+ v.category + templeName +'</option>';
				}else{
					string += '<option value="' + v.id + '">'+ v.category + '</option>';
				}
            });
            $("#category").html(string);
            $("#filter_pooja_category").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_pooja_types_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_pooja_prasadam_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#prasadam_check").append(string);
        }
    });
    $("#prasadam_check" ).change(function() {
        var prasadam_check=$("#prasadam_check" ).val();
        if(prasadam_check==0){
            $("#prasadam").prop('disabled', 'disabled');
        }
        if(prasadam_check==1){
            $('#prasadam').prop('disabled', false);
        }
    });
    detail('<?php echo base_url() ?>service/Pooja_data/pooja_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Pooja_data/pooja_edit', function (data) {
        detail_view(data);
    });
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_pooja'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_data/pooja_update");
        $('#category').val(data.editData.pooja_category_id);
        $('#type').val(data.editData.type);
        $('#daily_pooja').val(data.editData.daily_pooja);
        $('#kudumba_pooja').val(data.editData.kudumba_pooja);
        $('#endowment_pooja').val(data.editData.endowment_pooja);
        $('#quantity_pooja').val(data.editData.quantity_pooja);
        $('#advance_pooja').val(data.editData.advance_pooja);
        $('#rate').val(data.editData.rate);
        $('#pooja_eng').val(data.editData.pooja_name_eng);
        $('#pooja_alt').val(data.editData.pooja_name_alt);
        $('#description_eng').val(data.editData.pooja_description_eng);
        $('#description_alt').val(data.editData.pooja_description_alt);
        $('#account_name1').val(data.editData.ledger_id);
        $('#vavu_pooja').val(data.editData.vavu_pooja);
        $('#ayilya_pooja').val(data.editData.ayilya_pooja);
        $('#two_devotee_pooja').val(data.editData.two_devotee_pooja);
        $('#death_person_pooja').val(data.editData.death_person_pooja);
        $('#house_name_pooja').val(data.editData.house_name_pooja);
        $('#alive_person_pooja').val(data.editData.alive_person_pooja);
        $('#thiruvonam_pooja').val(data.editData.thiruvonam_pooja);
        $('#sunday_pooja').val(data.editData.sunday_pooja);
        $('#monday_pooja').val(data.editData.monday_pooja);
        $('#tuesday_pooja').val(data.editData.tuesday_pooja);
        $('#wednesday_pooja').val(data.editData.wednesday_pooja);
        $('#thursday_pooja').val(data.editData.thursday_pooja);
        $('#friday_pooja').val(data.editData.friday_pooja);
        $('#saturday_pooja').val(data.editData.saturday_pooja);
        $('#website_pooja').val(data.editData.website_pooja);
        var j=0;
        $.each(data.assets, function (i, v) {
            j++;
            add_asset_dynamic(j);
            assets_drop_down(j,v.asset_id);
            get_asset_rent(j);
            $('#quantity_'+j).val(v.quantity);
        });
        $('#count').val(j);
        if(data.editData.prasadam_check == 1){
            var j=0;
            $.each(data.prasadam, function (i, v) {
                j++;
                add_prasadam_dynamic(j);
                prasadam_drop_down(j,v.item_id);
                check_selected_prasadam(j);
            });
            $('#prasadam_count').val(j);
        }
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    function detail_view(data){
        var viewdata = "";
        var currency = '<?php echo CURRENCY ?>';
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_category_english'); ?></th>";
        viewdata += "<td>"+data.editData.category_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_category_alternate'); ?></th>";
        viewdata += "<td>"+data.editData.category_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('pooja_english'); ?></th>";
        viewdata += "<td>"+data.editData.pooja_name_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('pooja_alternate'); ?></th>";
        viewdata += "<td>"+data.editData.pooja_name_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('type'); ?></th>";
        viewdata += "<td>"+data.editData.type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('rate'); ?></th>";
        viewdata += "<td> ₹"+ data.editData.rate+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('daily_pooja'); ?></th>";
        if(data.editData.daily_pooja == '0'){
            viewdata += "<td>No</td>";
        }else{
            viewdata += "<td>Yes</td>";
        }
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('prasadam'); ?></th>";
        if(data.editData.prasadam_check == '0'){
            viewdata += "<td>No Prasadam</td>";
        }else{
            viewdata += "<td>Prasadam available for this pooja</td>";
        }
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th>Sl#</th><th>Prasadam</th></tr></thead>";
        viewData1 += "<tbody>";
        if(data.editData.prasadam_check == '0'){
            viewData1 += "<tr><td colspan='2' style='text-align:center'><b><i>No Prasadam</i></b></td></tr>";
        }else{
            var j = 0;
            $.each(data.prasadam, function (i, v) {
                j++;
                viewData1 += "<tr><td>"+j+"</td><td>"+v.name+"</td></tr>";
            });
        }
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        var viewData2 = "";
        viewData2 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData2 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th></tr></thead>";
        viewData2 += "<tbody>";
        var j = 0;
        $.each(data.assets, function (i, v) {
            j++;
            viewData2 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td>"+v.quantity+" "+v.notation+"</td></tr>";
        });
        if(j == 0){
            viewData2 += "<tr><td colspan='3' style='text-align:center'><b><i>No Assets Mapped</i></b></td></tr>";
        }
        viewData2 += "</tbody>";
        viewData2 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $("#other_details1").html(viewData2);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_pooja'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Pooja_data/pooja_add");
        clear_form();
        $(".asset_dyn").remove();
        $("#count").val(0);
        $("#actual").val(0);
        selectedAssetIds = {};
        $("#prasadam_count").val(0);
        $("#prasadam_actual").val(0);
        selectedPrasadamIds = {};
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
        output += '<input type="number" name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off">';
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
                var string = '<option value="">Select Item</option>';
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
                bootbox.alert("This item is already selected for this pooja");
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
    function add_prasadam_dynamic(){
        var j = +$("#prasadam_actual").val() + +1;
        $("#prasadam_actual").val(j);
        var i = +$("#prasadam_count").val() + +1;
        $("#prasadam_count").val(i);
        var output = "";
        output += '<div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="prasadam_'+i+'" id="prasadam_'+i+'" class="form-control parsley-validated asset" data-required="true" onchange="check_selected_prasadam('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_prasadam_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        $("#dynamic_prasadam_register").append(output);
        $('form').parsley().isValid();
        prasadam_drop_down(i);
        selectedPrasadamIds[i] = "0";
    }
    function remove_prasadam_dynamic(i){
        var j = +$("#prasadam_actual").val() - +1;
        $("#prasadam_actual").val(j);
        $(".prasadam_dyn_sec_"+i).remove();
        selectedPrasadamIds[i] = "0";
    }
    function prasadam_drop_down(i,val){       
        $.ajax({
            url: '<?php echo base_url() ?>service/Item_data/get_prasadam_drop_down',
            type: 'GET',
            async: false,
            success: function (data) {
                var string = '<option value="">Select Prasadam</option>';
                $.each(data.prasadam, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.name + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.name + '</option>';
                    }
                });
                $("#prasadam_"+i).html(string);
            }
        });
    }
    function check_selected_prasadam(val){
        var assetId = $("#prasadam_"+val).val();
        var checkFlag = '0';
        $.each(selectedPrasadamIds, function (i, v) {
            if(v == assetId){
                checkFlag = '1';
                $("#prasadam_"+val).val("");
                bootbox.alert("This prasadm is already selected for this pooja");
                return false; 
            }
        });
        if(checkFlag == '0'){
            selectedPrasadamIds[val] = assetId;
        }
    }
    function valNames(e) {
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }
</script>




