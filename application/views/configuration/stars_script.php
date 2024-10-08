<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        // {
        //     "aTargets": [2], "mData": 2,
        //     "mRender": function (data, type, row) {
        //         if (data == 1)
        //             return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
        //         else if (data != '')
        //             return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        //     }
        // }
    ];
    var action_url = $('#master_stars').attr('action_url');
    oTable = gridSFC('master_stars', action_url, aoColumnDefs);
</script>




