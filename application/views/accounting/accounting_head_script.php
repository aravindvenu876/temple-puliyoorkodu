<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2], "mData": 2,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [3], "mData": 2,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#accounting_head').attr('action_url');
    oTable = gridSFC('accounting_head', action_url, aoColumnDefs);
    function get_accounting_map_heads(){
        $("#accounting_head").dataTable().fnDraw();
    }
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
        url: '<?php echo base_url() ?>service/Account_basic_data/get_map_head_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Map Category</option>';
            $.each(data.map_head, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.map_head + '</option>';
            });
            $("#map_category").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_map_head_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Map Category</option>';
            $.each(data.map_head, function (i, v) {
                string += '<option value="' + v.map_head + '">'+ v.map_head + '</option>';
            });
            $("#filter_map_category").html(string);
        }
    });
    $("#map_category").change(function(){
        if($("#map_category").val() != ""){
            $.ajax({
                url: '<?php echo base_url() ?>service/Account_basic_data/get_map_item_drop_down',
                type: 'POST',
                data:{category:$("#map_category").val()},
                success: function (data) {
                    var string = '<option value="">Select Map Item</option>';
                    $.each(data.map_item, function (i, v) {
                        if(v.st == "1"){
                            string += '<option value="' + v.id + '">'+ v.item + '</option>';
                        }
                    });
                    $("#map_item").html(string);
                }
            });
        }
    });

    viewData('<?php echo base_url() ?>service/Account_basic_data/edit_accounting_head', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var mappedItems = "";
        if(data.mapHead.st == 1){
            var j = 0;
            $.each(data.mapHead.details, function (i, v) {
                j++;
                mappedItems += j + ". " + v.item + "<br>";
            });
        }
        var viewdata = "";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('account_head'); ?></th>";
        viewdata += "<td>"+data.mapHead.head+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('mapping_cat'); ?></th>";
        viewdata += "<th><?php echo $this->lang->line('mapping_item'); ?></th>";
        $.each(data.mappedItems, function (i, v) {
            viewdata += "<tr>";
            viewdata += "<td>"+v.mapped_category+"</td>";
            viewdata += "<td>"+v.mapped_item+"</td>";
            viewdata += "</tr>";
          });
      
        // viewdata += "<tr>";
        // viewdata += "<th><?php echo $this->lang->line('mapping_item'); ?></th>";
        // viewdata += "<td></td>";
        // viewdata += "</tr>";
        // if(data.mapHead.st == 1){
        //     viewdata += "<tr>";
        //     viewdata += "<th><?php echo $this->lang->line('mapping_item'); ?></th>";
        //     viewdata += "<td>"+mappedItems+"</td>";
        //     viewdata += "</tr>";
        // }
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_acounthead'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/add_accounting_head");
        clear_form();
    });

</script>




