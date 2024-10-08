<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 'parent',
            "mRender": function (data, type, row) {
                return data;
            }
        // },{
        //     "aTargets": [5], "mData": 4,
        //     "mRender": function (data, type, row) {
        //         if (data == 1)
        //             return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
        //         else if (data != '')
        //             return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
        //     }
        },{
            "aTargets": [4], "mData": 5,
            "mRender": function (data, type, row) {
                return data;
            }
        }
    ];
    var action_url = $('#temple_master').attr('action_url');
    oTable = gridSFC('temple_master', action_url, aoColumnDefs);
</script>




