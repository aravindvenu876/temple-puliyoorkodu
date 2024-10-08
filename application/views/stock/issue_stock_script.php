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
            "aTargets": [2], "mData": 2,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [4], "mData": 1,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
                        // return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_details'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>"+
                        // "<a class='btn btn-warning btn-sm btn_active btn_print_html'>PRINT</a>";
            }
        }
    ];
    var action_url = $('#stock_issue_master').attr('action_url');
    oTable = gridSFC('stock_issue_master', action_url, aoColumnDefs);
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        startDate: today,
        autoclose: true
    });
    viewData('<?php echo base_url() ?>service/Stock_data/view_issued_stock_details', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('date');?></th>";
        viewdata += "<td>"+convert_date(data.issue.date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('issue_on');?></th>";
        viewdata += "<td>"+convert_date(data.issue.created_on)+" "+data.issue.time+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th>Quantity</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.issueDetails, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.asset_name+"</td><td>"+v.quantity+" "+v.unit+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('issue_stock');?>");
        $(".saveButton").text("<?php echo $this->lang->line('save');?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Stock_data/issue_stock_new");
        clear_form();
        $(".asset_dyn").remove();
        $("#count").val(1);
        $("#actual").val(1);
        assets_drop_down(1);
    });
    
    function add_asset_dynamic(){
        var j = +$("#actual").val() + +1;
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
        output += '<select name="quantity_'+i+'" id="quantity_'+i+'" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off"></select>';
        output += '</div>';
        output += '</div>'; 
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';  
        output += '<input type="text" name="unit_'+i+'" id="unit_'+i+'" class="form-control" readonly autocomplete="off">';  
        output += '</div>';  
        output += '</div>';  
        output += '<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12 asset_dyn asset_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_asset_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12 asset_dyn asset_dyn_sec_'+i+'">.</div>';
        $("#dynamic_asset_register").append(output);
        assets_drop_down(i);
    }
    function remove_asset_dynamic(i){
        var j = +$("#actual").val() - +1;
        $("#actual").val(j);
        $(".asset_dyn_sec_"+i).remove();
    }
    function assets_drop_down(i){       
        $.ajax({
            url: '<?php echo base_url() ?>service/Asset_data/get_perishable_asset_drop_down',
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
            }
        });
    }
    $("table tbody").on("click", "a.btn_print_html", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var TABLE_NAME = grid.attr('table');
        var item = $(this);
        $.ajax({
            url: "<?php echo base_url() ?>" + "service/Stock_data/generate_stock_issue_print/selected_id/" + selected_id + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
            success: function (data) {
                if (data.message == 'no enough privilege') {
                    $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                    return;
                }
                if (data.message == 'success') {
                    $.toaster({priority: 'success', title: '', message: "Print Generated"});
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
    });
</script>




