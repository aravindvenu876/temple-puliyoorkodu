<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [1],
        "mData": 1,
        "mRender": function(data, type, row) {
            return convert_date(data);
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
    },{
        "aTargets": [6],
        "mData": 6,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },
        {
            "aTargets": [7], "mData": 7,
            "mRender": function (data, type, row) {
                if(data=="Rented"){
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='btn btn-warning btn-sm btn_active edit_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('return_rented_items'); ?>'><?php echo $this->lang->line('return'); ?></a>";     
                }
                else{
                    return "<span>"+data+"</span>";
                }  
            }
        }
    ];
    var action_url = $('#asset_rent').attr('action_url');
    oTable = gridSFC('asset_rent', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Asset_rent_data/asset_return_edit', function (data) {
        detail_edit(data);
    });
    
    function detail_edit(data) {    //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#dynamic_asset_register").html("");
        $("#form_title_h2").html("<?php echo $this->lang->line('return_rented_asset'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('return'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Asset_rent_data/return_rented_asset");
        $('#date').html(' : '+convert_date(data.main.date));
        $('#name').html(' : '+data.main.rented_by);
        $('#phone').html(' : '+data.main.phone);
        $('#address').html(' : '+data.main.address);
        var j=0;
        $.each(data.details, function (i, v) {
            j++;
            add_asset_dynamic(j);
            $('#rent_detail_id_'+j).val(v.id);
            $('#asset_id_'+j).val(v.asset_id);
            $('#quantity_'+j).val(v.quantity);
            $('#asset_price_'+j).val(v.price);
            $('#asset_rent_price_'+j).val(v.rent_price);
            $('#asset_'+j).html(v.asset_name);
            $('#rentedquantity_'+j).html(v.quantity);
            $("#unit_"+j).html(v.unit+"("+v.notation+")");
            $('#return_'+j).val(v.quantity);
            $('#unit_rent_'+j).html(v.rent_price);
            $('#asset_'+j).html(v.asset_name);
            $('#returned_rate_'+j).html(v.cost);
            $('#snap_'+j).val('0');
            $('#snap_unit_'+j).val('0');
            $('#scrapped_rate_'+j).html('0.0');
            $('#total_rate_'+j).html(v.cost);
        });
        $('#count').val(j);
        $('#total_amount').val(data.main.total);
        $('#discount').val(data.main.discount);
        $('#net_amount').val(data.main.net);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
    }
    
    $(".plus_btn").click(function () {
        clear_form();
    });
    function add_asset_dynamic(i){
            var output = "";
            output += '<tr>';
            output += '<td>'+i;
            output += '<input type="hidden" name="asset_id_'+i+'" readonly id="asset_id_'+i+'">';
            output += '<input type="hidden" name="rent_detail_id_'+i+'" id="rent_detail_id_'+i+'">';
            output += '<input type="hidden" name="quantity_'+i+'" id="quantity_'+i+'">';
            output += '<input type="hidden" name="asset_price_'+i+'" readonly id="asset_price_'+i+'">';
            output += '<input type="hidden" name="asset_rent_price_'+i+'" readonly id="asset_rent_price_'+i+'">';
            output += '</td><td>';
            output += '<span class="amntRight"  id="asset_'+i+'"></span>';
            output += '</td><td>';
            output += '<span class="amntRight"  id="rentedquantity_'+i+'"></span>';
            output += '</td><td>';
            output += '<span class="amntRight"  id="unit_'+i+'"></span>';
            output += '</td><td>';
            output += '<input type="text" name="return_'+i+'" id="return_'+i+'" onkeyup="check_quantity1('+i+')" class="form-control" required="" autocomplete="off">'; 
            output += '</td><td>';
            output += '<span class="amntRight"  id="unit_rent_'+i+'"></span>';
            output += '</td><td>';
            output += '<span class="amntRight"  id="returned_rate_'+i+'"></span>';
            output += '</td><td>';
            output += '<input type="text" name="snap_'+i+'" id="snap_'+i+'" class="form-control" onkeyup="check_quantity2('+i+')" required="" autocomplete="off">';  
            output += '</td><td>';
            output += '<input type="text" name="snap_unit_'+i+'" id="snap_unit_'+i+'" class="form-control" onkeyup="check_quantity2('+i+')" required="" autocomplete="off" value="0">';  
            // output += '<span class="amntRight"  id="unit_price_'+i+'"></span>';
            output += '</td><td>';
            output += '<span class="amntRight"  id="scrapped_rate_'+i+'"></span>';
            output += '</td><td>';
            output += '<span class="amntRight"  id="total_rate_'+i+'"></span>';
            output += '</td></tr>';
            $("#dynamic_asset_register").append(output);
    }
    function check_quantity1(i){
        var total_quantity = $("#quantity_"+i).val();
        var rented_quantity = $("#return_"+i).val();
        var scrapped_quantity = $("#snap_"+i).val();
        var price = $("#snap_unit_"+i).val();
        var rent_price = $("#asset_rent_price_"+i).val();
        var total_price = +scrapped_quantity * +price;
        // var total_rent_price = +rented_quantity * +rent_price;
        var total_rent_price = +rented_quantity * +total_quantity;
        var total_amount = "";
        if(rented_quantity != ""){
            if(Math.floor(rented_quantity) == rented_quantity && $.isNumeric(rented_quantity)) {
                var check = +total_quantity - +rented_quantity;
                if(isNaN(check) || check < 0) {
                    bootbox.alert("Please enter a value that is not greater than rented quantity");
                    $("#return_"+i).val(parseFloat(total_quantity));
                    $("#snap_"+i).val(parseFloat('0'));
                    total_price = '0';
                    total_rent_price = +total_quantity * +rent_price;
                }else{
                    scrapped_quantity = +total_quantity - +rented_quantity;
                    $("#snap_"+i).val(scrapped_quantity);
                    total_price = +scrapped_quantity * +price;
                    // total_rent_price = +rented_quantity * +rent_price;
                    total_rent_price = +total_quantity * +rent_price;
                }
            }else{
                bootbox.alert("Please enetr a valid number");
                // $("#return_"+i).val(parseFloat(total_quantity));
                $("#snap_"+i).val(parseFloat('0'));
                total_price = '0.00';
                total_rent_price = +total_quantity * +rent_price;
            }
            // $("#returned_rate_"+i).html(parseFloat(total_rent_price));
            $("#scrapped_rate_"+i).html(parseFloat(total_price));
            total_amount = +total_rent_price + +total_price;
            $("#total_rate_"+i).html(parseFloat(total_amount));
        }
        calculate_total_amount();
    }
    function check_quantity2(i){
        var total_quantity = $("#quantity_"+i).val();
        var rented_quantity = $("#return_"+i).val();
        var scrapped_quantity = $("#snap_"+i).val();
        var price = $("#snap_unit_"+i).val();
        var rent_price = $("#asset_rent_price_"+i).val();
        var total_price = +scrapped_quantity * +price;
        // var total_rent_price = +rented_quantity * +rent_price;
        var total_rent_price = +total_quantity * +rent_price;
        if(scrapped_quantity != ""){
            if(Math.floor(scrapped_quantity) == scrapped_quantity && $.isNumeric(scrapped_quantity)) {
                var check = +total_quantity - +scrapped_quantity;
                if(isNaN(check) || check < 0) {
                    bootbox.alert("Please enter a value that is not greater than rented quantity");
                    $("#return_"+i).val(parseFloat(total_quantity));
                    $("#snap_"+i).val(parseFloat('0'));
                    total_price = '0';
                    total_rent_price = +total_quantity * +rent_price;
                }else{
                    rented_quantity = +total_quantity - +scrapped_quantity;
                    $("#return_"+i).val(parseFloat(rented_quantity));
                    total_price = +scrapped_quantity * +price;
                    // total_rent_price = +rented_quantity * +rent_price;
                    total_rent_price = +total_quantity * +rent_price;
                }
            }else{
                bootbox.alert("Please enetr a valid number");
                $("#return_"+i).val(parseFloat(total_quantity));
                $("#snap_"+i).val(parseFloat('0'));
                total_price = '0';
                total_rent_price = +total_quantity * +rent_price;
            }
            // $("#returned_rate_"+i).html(parseFloat(total_rent_price));
            $("#scrapped_rate_"+i).html(parseFloat(total_price));
            total_amount = +total_rent_price + +total_price;
            $("#total_rate_"+i).html(parseFloat(total_amount));
        }
        calculate_total_amount();
    }
    function calculate_total_amount(){
        var count = $('#count').val()
        var total = 0;
        var rent_amount = 0;
        var scrap_amount = 0;
        for(var i=1;i<=count;i++){
            // rent_amount = +rent_amount + (+$("#return_"+i).val() * +$("#asset_rent_price_"+i).val());
            rent_amount = +rent_amount + (+$("#quantity_"+i).val() * +$("#asset_rent_price_"+i).val());
            scrap_amount = +scrap_amount + (+$("#snap_"+i).val() * +$("#snap_unit_"+i).val());
        }
        total = +rent_amount + +scrap_amount;
        var dec = parseFloat(total,10).toFixed(2);
        $("#total_amount").val(dec);
        var net_amount=+total - +$("#discount").val();
        var dec_net = parseFloat(net_amount,10).toFixed(2);
        $("#net_amount").val(dec_net);
    }
</script>




