<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [2],
        "mData": 2,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [1],
        "mData": 1,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    }, {
        "aTargets": [4],
        "mData": 1,
        "mRender": function(data, type, row) {
            // var chert = "";
            // if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
            // return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>";
        }
    }];
    var action_url = $('#balithara_special_rates_head').attr('action_url');
    oTable = gridSFC('balithara_special_rates_head', action_url, aoColumnDefs);
    
    $('#special_day').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: '+0d',
    });
    detail('<?php echo base_url() ?>service/Balithara_data/balithara_editdata', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Balithara_data/edit_balithara_special_rates', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_balithara'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/balithara_update_data");
        $('#name_eng').val(data.editData.main.name_eng);
        $('#name_alt').val(data.editData.main.name_alt);
        $('#description_eng').val(data.editData.main.description_eng);
        $('#description_alt').val(data.editData.main.description_alt);
        $('#type').val(data.editData.main.type);
        $('#monthly_rent').val(data.editData.main.monthly_rate);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.main.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('special_day'); ?></th>";
        viewdata += "<td>" + data.main.special_date + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('special_default_rate'); ?>(₹)</th>";
        viewdata += "<td> " + data.main.special_rate + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('special_description'); ?></th>";
        viewdata += "<td>" + data.main.speciality + "</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('balithara'); ?></th><th> <?php echo $this->lang->line('amount'); ?>(₹)</th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.name+"</td><td><span class='amntRight'>"+v.rate+"</span></td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_balithara'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Balithara_data/add_balithara_special_rate");
        clear_form();
        $("#dynamic_balithara_list").html("");
        get_all_balithara_for_special_rates();
    });
    $("#special_rate").keyup(function(){
        $(".special_amount").val($("#special_rate").val());
    });
    function get_all_balithara_for_special_rates(){
        $.ajax({
            url: '<?php echo base_url() ?>service/Balithara_data/get_balithara_list',
            type: 'GET',
            success: function (data) {
                var output = '';
                $.each(data.balitharas, function (i, v) {
                    output += '<div class="row">';
                    output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">';
                    output += '<input type="hidden" name="balithara[]" id="balithara[]" value="'+v.id+'">';
                    output += '<span class="span_label ">'+v.name+'</span>';
                    output += '</div>';
                    output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">';
                    output += '<span class="span_label ">Monthly Rent : ₹ '+v.monthly_rate+'</span>';
                    output += '</div>';
                    output += '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">';
                    output += '<div class="form-group">';
                    output += '<input type="number" min="0.0" step="0.1" name="rate[]" id="rate[]" class="form-control special_amount" autocomplete="off" placeholder="<?php echo $this->lang->line('special_rate'); ?>">';
                    output += '</div>';
                    output += '</div>';
                    output += '</div>';
                });
                $("#dynamic_balithara_list").html(output);
            }
        });
    }
</script>
