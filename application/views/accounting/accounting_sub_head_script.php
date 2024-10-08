<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
                if (data == 1)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [5], "mData": 4,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#accounting_head').attr('action_url');
    oTable = gridSFC('accounting_head', action_url, aoColumnDefs);

    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_account_main_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="0">Common Sub Head</option>';
            $.each(data.account_main_head, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.head + '</option>';
            });
            $("#account_head").html(string);
        }
    });

    viewData('<?php echo base_url() ?>service/Account_basic_data/edit_accounting_sub_head', function (data) {
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
        if(data.mapHead.parent_head != null){
            viewdata += "<tr>";
            viewdata += "<th>Account Parent Head</th>";
            viewdata += "<td>"+data.mapHead.parent_head+"</td>";
            viewdata += "</tr>";
        }
        viewdata += "<tr>";
		viewdata += "<th>Account Sub Head</th>";
        viewdata += "<td>"+data.mapHead.head+"</td>";
        viewdata += "</tr>";
        if(data.mapHead.map_head != null){
            viewdata += "<tr>";
            viewdata += "<th>Mapped Category</th>";
            viewdata += "<td>"+data.mapHead.map_head+"</td>";
            viewdata += "</tr>";
        }
        if(data.mapHead.st == 1){
            viewdata += "<tr>";
            viewdata += "<th>Mapped Items</th>";
            viewdata += "<td>"+mappedItems+"</td>";
            viewdata += "</tr>";
        }
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("Add Account Sub Head");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/add_accounting_sub_head");
        clear_form();
    });

</script>




