<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [3],"mData": 3,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [4],"mData": 4,
        "mRender": function(data, type, row) {
            if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
            else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        }
    }, {
        "aTargets": [5],"mData": 4,
        "mRender": function(data, type, row) {
            var btn = "";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
            btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
            if (data == 0){
                btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
            }
            return btn;
        }
    }];
    var action_url = $('#auditorium_master').attr('action_url');
    oTable = gridSFC('auditorium_master', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Hall_data/hall_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Hall_data/hall_edit', function(data) {
        detail_view(data);
    });

    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_hall_types_dropdown',
        type: 'GET',
        success: function (data) {
            var string = "";
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('Update_hall_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Hall_data/hall_data_update");
        $('#name_eng').val(data.editData.name_eng);
        $('#name_alt').val(data.editData.name_alt);
        $('#type').val(data.editData.type);
        $('#hall_advance').val(data.editData.advance);
        $('#cleaning_amount').val(data.editData.cleaning_amount);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
        $('#account_name1').val(data.editData.ledger_id);
        $("#hall_slab_rate").html("");
        get_hall_defined_rate_slabs(data.editData.id);
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('hall_name_eng'); ?></th>";
        viewdata += "<td>" + data.editData.name_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('hall_name_alt'); ?></th>";
        viewdata += "<td>" + data.editData.name_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('advance_amount'); ?></th>";
        viewdata += "<td>" + data.editData.advance + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Type</th>";
        viewdata += "<td>" + data.editData.type + "</td>";
        viewdata += "</tr>";
        // viewdata += "<tr>";
        // viewdata += "<th><?php echo $this->lang->line('rent_amount'); ?></th>";
        // viewdata += "<td>" + data.editData.rent + "</td>";
        // viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('cleaning_amount'); ?></th>";
        viewdata += "<td>" + data.editData.cleaning_amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('account_ledger'); ?></th>";
        viewdata += "<td>" + data.editData.ledger_name + "</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th>Period</th><th>Rent Amount(₹)</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.rates, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+v.starting_label+" hour(s) to "+v.ending_label+" hour(s)</td><td style='text-align:right'>"+v.rate+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_hall_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Hall_data/hall_add");
        $("#hall_slab_rate").html("");
        clear_form();
        get_hall_rate_slabs();
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
    function get_hall_rate_slabs(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Hall_data/get_auditorium_rate_slabs',
            type: 'GET',
            success: function (data) {
                var output = '';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                output += '<span><b>Time Period</b></span>';
                output += '</div>';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                output += '<span><b>Rent Amount</b></span>';
                output += '</div>';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12">.</div>';
                var j = 0;
                $.each(data.slab_rates, function (i, v) {
                    j++;
                    output += '<input type="hidden" name="slab_'+j+'" id="slab_'+j+'" value="'+v.id+'">';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                    output += '<div class="form-group">';
                    output += '<input type="text" name="cost_'+j+'" id="cost_'+j+'" value="'+v.starting_label+' hour(s) to '+v.ending_label+' hour(s)" readonly="" class="form-control parsley-validated rate" data-required="true" autocomplete="off">';
                    output += '</div>';
                    output += '</div>';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                    output += '<div class="form-group">';
                    output += '<input type="number" name="rent_'+j+'" id="rent_'+j+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off" value="0.00">';
                    output += '</div>';
                    output += '</div>';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12">.</div>';
                });
                $("#hall_slab_rate").html(output);
            }
        });
    }
    function get_hall_defined_rate_slabs(id){
        $.ajax({
            url: '<?php echo base_url() ?>service/Hall_data/get_auditorium_rate_defined_slabs',
            type: 'GET',
            data:{hall_id:id},
            success: function (data) {
                var output = '';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                output += '<span><b>Time Period</b></span>';
                output += '</div>';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                output += '<span><b>Rent Amount</b></span>';
                output += '</div>';
                output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12">.</div>';
                var j = 0;
                $.each(data.slab_rates, function (i, v) {
                    if(v.status == "1"){
                        var rate = v.rate;
                    }else{
                        var rate = "0.00";
                    }
                    j++;
                    output += '<input type="hidden" name="slab_'+j+'" id="slab_'+j+'" value="'+v.id+'">';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                    output += '<div class="form-group">';
                    output += '<input type="text" name="cost_'+j+'" id="cost_'+j+'" value="'+v.starting_label+' hour(s) to '+v.ending_label+' hour(s)" readonly="" class="form-control parsley-validated rate" data-required="true" autocomplete="off">';
                    output += '</div>';
                    output += '</div>';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">';
                    output += '<div class="form-group">';
                    output += '<input type="number" name="rent_'+j+'" id="rent_'+j+'" value="'+rate+'" class="form-control parsley-validated rate" data-required="true" autocomplete="off" value="0.00">';
                    output += '</div>';
                    output += '</div>';
                    output += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12">.</div>';
                });
                $("#hall_slab_rate").html(output);
            }
        });
    }
</script>
