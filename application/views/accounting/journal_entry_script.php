<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
       var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1], "mData": 1,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
        "aTargets": [5],
        "mData": 5,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [6],
        "mData": 6,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
            "aTargets": [8], "mData": 8,
            "mRender": function (data, type, row) {
                if(data == '0'){
                    return "Not Synced";
                }else{
                    return "Synced";
                }
            }
        },{
            "aTargets": [9], "mData": 9,
            "mRender": function (data, type, row) {
                if(data == ''){
                    return "Not Synced";
                }else{
                    return convert_date(data);
                }
            }
        },{
            "aTargets": [10], "mData": 0,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'><i class='fa fa-eye' aria-hidden='true'></i></a>";
				// return "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash' aria-hidden='true'></i></a>";
                            
            }
        }
    ];
    var action_url = $('#accounting_entry').attr('action_url');
    oTable = gridSFC('accounting_entry', action_url, aoColumnDefs);
    function get_accounting_map_heads(){
        $("#accounting_entry").dataTable().fnDraw();
    }
    viewData('<?php echo base_url() ?>service/Account_basic_data/get_accounting_sub_entry', function (data) {
        detail_view(data);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Head</option>';
            $.each(data.account_head, function (i, v) {
                string += '<option value="' + v.head + '">'+ v.head + '</option>';
            });
            $("#filter_account_head").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Head</option>';
            $.each(data.account_head, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.head + '</option>';
            });
            $("#account_head").html(string);
        }
    });
    headaccount_drop_down(1);
    function headaccount_drop_down(i){ 
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Head</option>';
            $.each(data.account_head, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.head + '</option>';
            });
            $("#subaccount_head_"+i).html(string);
        }
    });
    }
   
    $("#map_category").change(function(){
        if($("#map_category").val() != ""){
            $.ajax({
                url: '<?php echo base_url() ?>service/Account_basic_data/get_map_item_drop_down',
                type: 'POST',
                data:{category:$("#map_category").val()},
                success: function (data) {
                    var string = '<option value="">Select Map Item</option>';
                    $.each(data.map_item, function (i, v) {
                        // if(v.mapped_status == 0){
                            string += '<option value="' + v.id + '">'+ v.item + '</option>';
                        // }
                    });
                    $("#map_item").html(string);
                }
            });
        }
    });

    viewData('<?php echo base_url() ?>service/Account_basic_data/get_accounting_sub_entry', function (data) {
        detail_view(data);
    });
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
      
    })
          var maxDate = new Date();
        $('#date').datepicker('setEndDate', maxDate);
    
function add_asset_dynamic(){
            var j = +$("#actual").val() + +1;
            $("#actual").val(j);
            var i = +$("#count").val() + +1;
            $("#count").val(i);
            var output = "";
            output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<select name="subaccount_head_'+i+'" id="subaccount_head_'+i+'" class="form-control parsley-validated asset" data-required="true"></select>';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<select name="type_'+i+'" id="type_'+i+'" class="form-control parsley-validated asset" data-required="true"> <option value="To">To</option> <option value="By">By</option></select>';
            output += '</div>';
            output += '</div>';
            output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">'; 
            output += '<input type="number" name="amount_'+i+'" id="amount_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off">'; 
            output += '</div>'; 
            output += '</div>'; 
            output += '<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12 asset_dyn asset_dyn_sec_'+i+'">';
            output += '<div class="form-group">';
            output += '<button type="button" class="btn btn-danger" onclick="remove_asset_dynamic('+i+')"><i class="fa fa-times"></i></button>';
            output += '</div>';
            output += '</div>';
            $("#dynamic_asset_register").append(output);
            headaccount_drop_down(i);
    }
    function remove_asset_dynamic(i){
        var j = +$("#actual").val() - +1;
        $("#actual").val(j);
        $(".asset_dyn_sec_"+i).remove();
    }

    function detail_view(data){
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead>";
		viewData1 += "<tr class='bg-warning text-white'>";
		viewData1 += "<th>Sl#</th>";
		viewData1 += "<th>Particular</th>";
		viewData1 += "<th style='text-align:right'><?php echo $this->lang->line('debit'); ?></th>";
		viewData1 += "<th style='text-align:right'><?php echo $this->lang->line('credit'); ?></th>";
		viewData1 += "</tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.subEntries, function (i, v) {
            j++;
            viewData1 += "<tr>";
            viewData1 += "<td>"+j+"</td>";
            viewData1 += "<td>"+v.type+" "+v.head+"</td>";
            viewData1 += "<td><span class='amntRight'>"+v.debit+"</span></td>";
            viewData1 += "<td><span class='amntRight'>"+v.credit+"</span></td>";
            viewData1 += "</tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("Add Journal Entry");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/add_joureal_entry");      
        clear_form();
        headaccount_drop_down(1);
        $(".asset_dyn").remove();
        $("#count").val(1);
        $("#actual").val(1);
       
    });

</script>




