h<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [3],
            "mData": 3,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [4], "mData": 1,
            "mRender": function (data, type, row) {
                
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>";
            }
        }
    ];
    var action_url = $('#leave_schemes').attr('action_url');
    oTable = gridSFC('leave_schemes', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#leave_schemes").dataTable().fnDraw();
    }
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    detail('<?php echo base_url() ?>service/Leave_data/leave_scheme_edit', function (data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Leave_data/leave_scheme_edit', function (data) {
        detail_view(data);
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Leave_data/get_leave_scheme_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Leave Scheme</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.scheme + '</option>';
            });
            $("#leave_scheme").append(string);
        }
    });
   
    function detail_edit(data){
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_salary_scheme'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Leave_data/update_leave_scheme");
        $('#name').val(data.main.scheme);
        $('#from_date').val(convert_date(data.main.date_from));
        $('#to_date').val(convert_date(data.main.date_to));
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
        leave_head_drop_down(data.main.id);
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('salary_scheme'); ?></th>";
        viewdata += "<td>"+data.main.scheme+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('from'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.date_from)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('to'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.date_to)+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('leave_head'); ?></th><th><?php echo $this->lang->line('leave_count'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.head+"</td><td>"+v.count+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('new_leave_scheme'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Leave_data/add_leave_scheme");
        clear_form();
        $("#count").val(0);
        $("#actual").val(0);
        leave_head_drop_down(0);
    });
    function add_leave_head_dynamic(i,head_id,head_name,total_leave){
        $("#count").val(i);
        var output = "";
        output += '<tr><td><input type="hidden" name="head_id[]" id="head_id_'+i+'" value="'+head_id+'" class="asset_dyn_sec_'+i+'"/>';
        output += head_name+'</td>';
        output += '<td><input type="number" name="leave_count[]" id="leave_count_'+i+'" min="0" value="'+total_leave+'" class="form-control amount" data-required="true" autocomplete="off"></td>';
        output += '</tr>';
        $("#dynamic_asset_register").append(output);
    }
    function leave_head_drop_down(val){  
        $("#dynamic_asset_register").html("");
        $.ajax({
            url: '<?php echo base_url() ?>service/Leave_data/get_leave_head_drop_down',
            type: 'POST',
            data:{scheme_id:val},
            async: false,
            success: function (data) {
                var j = $("#count").val();
                $.each(data.leave_heads, function (i, v) {
                    j++;
                    add_leave_head_dynamic(j,v.id,v.head,v.leave);
                });
            }
        });
    }
</script>