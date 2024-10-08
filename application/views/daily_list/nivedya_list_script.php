<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var nivedyamCount = "";
    for(var k=0;k<=30;k++){
        nivedyamCount += "<option value='"+k+"'>"+k+"</option>";
    }
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    get_list();
    $("#date").change(function(){
        get_list();
    });
    var print_btn ='<button name="print_list" id="print_list" onclick="printPageArea()" class="btn btn-primary pull-right ">PRINT LIST</button>';
    var dynmic_nivedya_btn = '<button name="add_dynamic_entry" id="add_dynamic_entry" onclick="add_dynamic_entry()" class="btn btn-primary pull-right ">ADD MORE NIVEDYAMS</button>';
    function get_list(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Daily_list_data/get_nivedya_list',
            type: 'POST',
            data: {date:$("#date").val()},
            success: function (data) {
                $("#list").html(data.list);
                if(data.date_check == 1){
                    $("#print_btn_div").html(print_btn+dynmic_nivedya_btn);
                }else{
                    $("#print_btn_div").html(print_btn);
                }
            }
        });
    }
    function printPageArea(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Daily_list_data/get_nivedya_list_print',
            type: 'POST',
            data: {date:$("#date").val()},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.list);
                w.document.close();
            }
        });
    }
    function add_dynamic_entry(){
        var count = "7";
        $("#count").val(7);
        for(var i=1;i<=count;i++){
            prasadam_drop_down(i);
            $("#count_"+i).html(nivedyamCount);
        }
        // prasadam_drop_down(1);
        // $("#count_1").html(nivedyamCount);
        // prasadam_drop_down(2);
        // $("#count_2").html(nivedyamCount);
        // prasadam_drop_down(3);
        // $("#count_3").html(nivedyamCount);
        // prasadam_drop_down(4);
        // $("#count_4").html(nivedyamCount);
        // prasadam_drop_down(5);
        // $("#count_5").html(nivedyamCount);
        // prasadam_drop_down(6);
        // $("#count_6").html(nivedyamCount);
        // prasadam_drop_down(7);
        // $("#count_7").html(nivedyamCount);
        // prasadam_drop_down(2);
        // $("#count_2").html(nivedyamCount);
        $("#additional_date").val($("#date").val());
        $("#additionalPrasadamTitle").html("Additional Nivedyams for "+convert_date($("#date").val()));
        $('#formSessionRenewFixedDeposit').modal('show');
    }
    function add_nivedyam_dynamic(){
        var i = +$("#count").val() + +1;
        $("#count").val(i);
        var output = "";
        output += '<input type="hidden" name="actual_quantity_'+i+'" id="actual_quantity_'+i+'"/>';
        output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="pooja_'+i+'" id="pooja_'+i+'" class="form-control parsley-validated" data-required="true"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="prasadam_'+i+'" id="prasadam_'+i+'" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam('+i+')"></select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<select name="count_'+i+'" id="count_'+i+'" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam('+i+')">'+nivedyamCount+'</select>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<input type="text" readonly="" id="quantity_'+i+'" name="quantity_'+i+'" class="form-control"/>';
        output += '</div>';
        output += '</div>';
        output += '<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12 asset_dyn prasadam_dyn_sec_'+i+'">';
        output += '<div class="form-group">';
        output += '<button type="button" class="btn btn-danger" onclick="remove_prasadam_dynamic('+i+')"><i class="fa fa-times"></i></button>';
        output += '</div>';
        output += '</div>';
        prasadam_drop_down(i);
        pooja_drop_down(i);
        $("#dynamic_prasadam_register").append(output);
    }
    function remove_prasadam_dynamic(i){
        $(".prasadam_dyn_sec_"+i).remove();
    }
    function pooja_drop_down(i,val){
        $.ajax({
            url: '<?php echo base_url() ?>service/Pooja_data/get_pooja_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Pooja</option>';
                $.each(data.pooja, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.pooja_name + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
                    }
                });
                $("#pooja_"+i).html(string);
            }
        });
    }
    function prasadam_drop_down(i,val){       
        $.ajax({
            url: '<?php echo base_url() ?>service/Item_data/get_prasadam_drop_down',
            type: 'GET',
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
        if($("#prasadam_"+val).val() != ""){
            $.ajax({
                url: '<?php echo base_url() ?>service/Item_data/get_item_info',
                type: 'POST',
                data: {item_id:$("#prasadam_"+val).val()},
                async: false,
                success: function (data) {
                    var totalQuantity =  (+$("#count_"+val).val() * +data.editData.defined_quantity);
                    $("#actual_quantity_"+val).val(totalQuantity);
                    $("#quantity_"+val).val(totalQuantity + " " + data.editData.notation);
                    // $("#quantity_"+val).html($("#count_"+val).val());
                    // $("#quantity_"+val).html(editData.defined_quantity);
                }
            });
        }
    }
    $(".saveData1").click(function () {
        var form = $(".popup-form");
        var url = "<?php echo base_url() ?>service/Daily_list_data/add_additional_prasadams";
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            async:false,
            success: function(data){
                get_list();
                $('#formSessionRenewFixedDeposit').modal('hide');
            }
        });
    });
</script>