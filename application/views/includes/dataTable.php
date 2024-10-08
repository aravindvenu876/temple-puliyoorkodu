<script>
    var modifyBit = '<?php echo $permissions["modify_status"] ?>';
    var columnDefinitionS = [ 
        {
            "aTargets": [0], "mData": 0,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' " +
                        "data-placement='right' data-original-title = 'Click for details'> " + data + " </a>";
            },
            "bVisible": false
        }
    ];
    function gridSFC(tableId, ajaxUrl, aoColumnDefs) {
        $(".load").show();
        columnDefinitionSFC = columnDefinitionS;
        var oTable = $("#" + tableId).dataTable({
            processing: true,
            serverSide: true,
            bDeferRender: true,
            sServerMethod: "GET",
            sAjaxSource: ajaxUrl,
            fnServerParams: function (aoData) {
                var dataItem =[];
                if(tableId == "scheduled_poojas"){
                    dataItem = [
                        {"name": "fromDate", "value": $('#from_date').val()},
                        {"name": "toDate", "value": $('#to_date').val()},
                        {"name": "poojaStatus", "value": $('#pooja_status').val()}
                    ];
                }else if(tableId == "aavaahanam_poojas"){
                    dataItem = [
                        {"name": "fromDate", "value": $('#from_date').val()},
                        {"name": "toDate", "value": $('#to_date').val()},
                        {"name": "name", "value": $('#name').val()},
                        {"name": "phone", "value": $('#phone').val()}
                    ];
                }else if(tableId == "auditorium_booking_details"){
                    dataItem = [
                        {"name": "hallName", "value": $('#filter_hall').val()},
                        {"name": "hallBookedDate", "value": $('#filter_booked_date').val()},
                        {"name": "hallBookedPhone", "value": $('#filter_phone').val()},
                        {"name": "hallBookedStatus", "value": $('#filter_status').val()}
                    ];
                }else if(tableId == "assets"){
                    dataItem = [
                        {"name": "assetCategory", "value": $('#filter_asset_category').val()},
                        {"name": "assetName", "value": $('#filter_asset').val()},
                        {"name": "assetType", "value": $('#filter_asset_type').val()},
                    ];
                }else if(tableId == "receipt"){
                    dataItem = [
                        {"name": "receiptDate", "value": $('#filter_receipt_date').val()},
                        {"name": "receiptNo", "value": $('#filter_receipt_no').val()},
                        {"name": "receiptCounter", "value": $('#filter_receipt_counter').val()},
                        {"name": "receiptType", "value": $('#filter_receipt_type').val()},
                        {"name": "receiptStatus", "value": $('#filter_receipt_status').val()},
                    ];
                }else if(tableId == "staff"){
                    dataItem = [
                        {"name": "staffId", "value": $('#filter_staff_id').val()},
                        {"name": "staffName", "value": $('#filter_staff_name').val()},
                        {"name": "staffPhone", "value": $('#filter_staff_phone').val()},
                        {"name": "staffDesignation", "value": $('#filter_staff_designation').val()},
                        {"name": "staffType", "value": $('#filter_staff_type').val()},
                    ];
                }else if(tableId == "balithara_auction_master"){
                    dataItem = [
                        {"name": "balitharaId", "value": $('#filter_balithara').val()},
                        // {"name": "balitharafromDate", "value": $('#filter_from_date').val()},
                        // {"name": "balitharaToDate", "value": $('#filter_to_date').val()},
                        {"name": "balitharaName", "value": $('#filter_name').val()},
                        {"name": "balitharaPhone", "value": $('#filter_phone').val()},
                    ];
                }else if(tableId == "daily_transactions"){
                    dataItem = [
                        {"name": "dailyDate", "value": $('#filter_transaction_date').val()},
                        {"name": "dailyId", "value": $('#filter_transaction_id').val()},
                        {"name": "dailyType", "value": $('#filter_transaction_type').val()},
                        {"name": "dailyTransactionHead", "value": $('#filter_transaction_head').val()},
                    ];
                }else if(tableId == "bank_transaction"){
                    dataItem = [
                        {"name": "bankDate", "value": $('#filter_bank_date').val()},
                        {"name": "bankType", "value": $('#filter_bank_type').val()},
                        {"name": "bankId", "value": $('#filter_bank_bank').val()},
                        {"name": "bankAccount", "value": $('#filter_bank_account').val()},
                    ];
                }else if(tableId == "view_sb_to_fd"){
                    dataItem = [
                        {"name": "bankDate", "value": $('#filter_bank_date').val()},
                        {"name": "FDBankID", "value": $('#filter_fd_bank').val()},
                        {"name": "FDBankAccount", "value": $('#filter_fd_account').val()},
                        {"name": "SBBankID", "value": $('#filter_sb_bank').val()},
                        {"name": "SBBankAccount", "value": $('#filter_sb_account').val()},
                    ];
                }else if(tableId == "view_fd_to_sb"){
                    dataItem = [
                        {"name": "bankDate", "value": $('#filter_bank_date').val()},
                        {"name": "FDBankID", "value": $('#filter_fd_bank').val()},
                        {"name": "FDBankAccount", "value": $('#filter_fd_account').val()},
                        {"name": "SBBankID", "value": $('#filter_sb_bank').val()},
                        {"name": "SBBankAccount", "value": $('#filter_sb_account').val()},
                    ];
                }else if(tableId == "view_sb_to_sb"){
                    dataItem = [
                        {"name": "bankDate", "value": $('#filter_bank_date').val()},
                        {"name": "fromBankID", "value": $('#filter_from_bank').val()},
                        {"name": "toBankID", "value": $('#filter_to_bank').val()},
                    ];
                }else if(tableId == "pooja"){
                    dataItem = [
                        {"name": "poojaCategory", "value": $('#filter_pooja_category').val()},
                        {"name": "poojaName", "value": $('#filter_pooja_name').val()},
                        {"name": "poojaDaily", "value": $('#filter_pooja_type').val()},
                    ];
                }else if(tableId == "pos_receipt_book_items"){
                    dataItem = [
                        {"name": "receiptBookCategory", "value": $('#filter_receiptbook_category').val()},
                        {"name": "receiptBookName", "value": $('#filter_receiptbook_name').val()},
                    ];
                }else if(tableId == "leave_entry"){
                    dataItem = [
                        {"name": "leaveEntryStaff", "value": $('#filter_staff').val()},
                        {"name": "filterfrom_date", "value": $('#filterfrom_date').val()},
                        {"name": "filterto_date", "value": $('#filterto_date').val()},

                    ];
                }else if(tableId == "today_poojas"){
                    dataItem = [
                        {"name": "poojaName", "value": $('#filter_pooja_name').val()},
                        {"name": "receiptNumber", "value": $('#filter_receipt_no').val()},
                        {"name": "D_Name", "value": $('#filter_name').val()},
                        {"name": "D_Phone", "value": $('#filter_phone').val()},
                    ];
                }else if(tableId == "salary_advance"){
                    dataItem = [
                        {"name": "salaryYear", "value": $('#filter_year').val()},
                        {"name": "salaryMonth", "value": $('#filter_month').val()},
                        {"name": "staff", "value": $('#filter_staff').val()},
                    ];
                }else if(tableId == "accounting_map_heads"){
                    dataItem = [
                        {"name": "map_head", "value": $('#filter_head').val()},
                        {"name": "map_table", "value": $('#filter_table').val()},
                    ];
                }else if(tableId == "account_groups"){
                    dataItem = [
                        {"name": "head", "value": $('#filter_group').val()},
						{"name": "parent_head", "value": $('#filter_parent_group').val()},
                    ];
                }else if(tableId == "accounting_head"){
                    dataItem = [
                        {"name": "head", "value": $('#filter_account_head').val()},
                        {"name": "map_head", "value": $('#filter_map_category').val()},
                    ];
                }else if(tableId == "accounting_entry"){
                    dataItem = [
                        {"name": "date", "value": $('#filter_booked_date').val()},
                        {"name": "map_head", "value": $('#filter_account_head').val()},
                    ];
                }else if(tableId == "pos_receipt_book_used"){
                    dataItem = [
                        {"name": "receiptBookCategory", "value": $('#filter_receiptbook_category').val()},
                        {"name": "receiptBookName", "value": $('#filter_receiptbook_name').val()},
                    ];
                }else if(tableId == "salary_schemes"){
                    dataItem = [
                        {"name": "scheme", "value": $('#filter_salary_scheme').val()},
                    ];
                }else if(tableId == "salary_processing"){
                    dataItem = [
                        {"name": "staff_id", "value": $('#filter_staff').val()},
                        {"name": "year", "value": $('#filter_year').val()},
                        {"name": "month", "value": $('#filter_month').val()},
                    ];
                }else if(tableId == "leave_schemes"){
                    dataItem = [
                        {"name": "id", "value": $('#leave_scheme').val()},
                    ];
                }else if(tableId == "leave_status"){
                    dataItem = [
                        {"name": "leave_scheme_id", "value": $('#leave_scheme').val()},
                        {"name": "id", "value": $('#filter_staff').val()},
                    ];
                }else if(tableId == "bank_fixed_deposits"){
                    dataItem = [
                        {"name": "bank_id", "value": $('#filter_bank').val()},
                        {"name": "maturity_date", "value": $('#filter_transaction_date').val()},
                    ];
                }else if(tableId == "item_master"){
                    dataItem = [
                        {"name": "item_category_id", "value": $('#filter_category').val()},
                    ];
                }else if(tableId == "web_receipt_main"){
                    dataItem = [
                        {"name": "receipt_date",    "value": $('#filter_receipt_date').val()},
                        {"name": "receipt_no",      "value": $('#filter_receipt_no').val()},
                        {"name": "receipt_status",  "value": $('#filter_receipt_status').val()},
                    ];
                }
                $.each(dataItem, function(i, row) {
                    aoData.push(row);
                });
            },
            dom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            pagingType: "simple_numbers",
            initComplete: function () {
                $("#" + tableId).dataTable().fnSetFilteringDelay(600);
            },
            pageLength: 10,
            bFilter:false,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [],
            aoColumnDefs: columnDefinitionSFC.concat(aoColumnDefs),
            oLanguage: {
                "sProcessing": "<img src=" + "<?php echo base_url() ?>" + "assets/images/loading.gif" + ">",
                "sEmptyTable": "<span>Sorry No Data found !</span>"
            },
            fnPreDrawCallback: function () {
                //alert('begin');
                return true;
            },
            fnDrawCallback: function () {
                //alert('end');
            }
        });
        $(".load").hide();
        return oTable;
    }
    jQuery.fn.dataTableExt.oApi.fnSetFilteringDelay = function (oSettings, iDelay) {
        var _that = this;
        if (iDelay === undefined) {
            iDelay = 250;
        }
        this.each(function (i) {
            if (typeof _that.fnSettings().aanFeatures.f !== 'undefined')
            {
                $.fn.dataTableExt.iApiIndex = i;
                var
                        oTimerId = null,
                        sPreviousSearch = null,
                        anControl = $('input', _that.fnSettings().aanFeatures.f);

                anControl.unbind('keyup search input').bind('keyup search input', function () {

                    if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
                        window.clearTimeout(oTimerId);
                        sPreviousSearch = anControl.val();
                        oTimerId = window.setTimeout(function () {
                            $.fn.dataTableExt.iApiIndex = i;
                            _that.fnFilter(anControl.val());
                        }, iDelay);
                    }
                });
                return this;
            }
        });
        return this;
    };
    function detail(sAjaxSource, callback) {
        $('.dataTable tbody').on('click', 'a.edit_btn_datatable', function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                $(".load").show();
                $('#alert-prompt').show();
                $(this).closest('tr').addClass('selected_tr');
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var id = rowData[0];
                $.ajax({
                    type: "GET",
                    url: sAjaxSource + "/id/" + id,
                    success: function (data) {
                        $('#alert-prompt').hide();
                        setTimeout(function () {
                            var breadcrumbTEXT = $('.breadcrumb li').last().text();
                            if (breadcrumbTEXT.indexOf('Add') != -1 || breadcrumbTEXT.indexOf('Reschedule') != -1 || breadcrumbTEXT.indexOf('Cancel') != -1) {
                                $('.breadcrumb li').last().remove();
                            } 
							breadcrumbTEXT = $('.breadcrumb li').last().text();
							breadcrumbLink = $('.breadcrumb li a').last().attr('href');
							$('.breadcrumb li').last().remove();
							$('.breadcrumb').append('<li class="breadcrumb-item"><a href="'+breadcrumbLink+'">'+breadcrumbTEXT+'</a></li>');
            				$('.breadcrumb').append('<li class="breadcrumb-item"><b><a>Edit</a></b></li>');
                        }, 50);
                        callback(data);
                		$(".load").hide();
                    }
                });
            }
        });
    }
    function reschedule_booking(sAjaxSource, callback) {
        $('.dataTable tbody').on('click', 'a.reschedule_btn_datatable', function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                $(".load").show();
                $('#alert-prompt').show();
                $(this).closest('tr').addClass('selected_tr');
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var id = rowData[0];
                $.ajax({
                    type: "GET",
                    url: sAjaxSource + "/id/" + id,
                    success: function (data) {
                        $('#alert-prompt').hide();
                        setTimeout(function () {
                            var breadcrumbTEXT = $('.breadcrumb li').last().text();
                            if (breadcrumbTEXT.indexOf('Add') != -1 || breadcrumbTEXT.indexOf('Edit') != -1 || breadcrumbTEXT.indexOf('Cancel') != -1) {
                                $('.breadcrumb li').last().remove();
                            }
                            $('.breadcrumb').append('<li class="breadcrumb-item"><a>Reschedule</a></li>');
                        }, 50);
                        callback(data);
                		$(".load").hide();
                    }
                });
            }
        });
    }
    function cancel_booking(sAjaxSource, callback) {
        $('.dataTable tbody').on('click', 'a.cancel_btn_datatable', function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                $(".load").show();
                $('#alert-prompt').show();
                $(this).closest('tr').addClass('selected_tr');
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var id = rowData[0];
                $.ajax({
                    type: "GET",
                    url: sAjaxSource + "/id/" + id,
                    success: function (data) {
                        $('#alert-prompt').hide();
                        setTimeout(function () {
                            var breadcrumbTEXT = $('.breadcrumb li').last().text();
                            if (breadcrumbTEXT.indexOf('Add') != -1 || breadcrumbTEXT.indexOf('Reschedule') != -1 || breadcrumbTEXT.indexOf('Edit') != -1) {
                                $('.breadcrumb li').last().remove();
                            }
                            $('.breadcrumb').append('<li class="breadcrumb-item"><a>Cancel</a></li>');
                        }, 50);
                        callback(data);
                		$(".load").hide();
                    }
                });
            }
        });
    }
    function viewData(sAjaxSource, callback) {
        $('.dataTable tbody').on('click', 'a.view_btn_datatable', function () {
            $(".load").show();
            $("#viewModalTitle").html();
            $('#alert-prompt').show();
            $(this).closest('tr').addClass('selected_tr');
            var grid = $(this).closest("table");
            var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
            var id = rowData[0];
            $.ajax({
                type: "GET",
                url: sAjaxSource + "/id/" + id,
                success: function (data) {
                    callback(data);
                	$(".load").hide();
                }
            });
        });
    }
    $(document).ready(function () {
        var options = {
            beforeSubmit: beforeSubmit, // pre-submit callback 
            success: afterSuccess, // post-submit callback 
            resetForm: true        // reset the form after successful submit 
        };
        $('.image-upload-form').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });
        function afterSuccess(data) {
            $('#alert-prompt').hide();
            if (typeof data.error != 'undefined') {
                $.toaster({priority: 'danger', title: '', message: data.error});
                return;
            }
            $('.selected_tr_add').removeClass('selected_tr_add DTTT_selected ');
            $('.DTTT_selected .selected').removeClass('selected_tr_add DTTT_selected');
            $('.DTTT_selected.selected').removeClass("DTTT_selected selected");
            $('.selected_tr_add').removeClass('selected_tr_add DTTT_selected selected');
            $('.DTTT_selected .selected').removeClass('selected_tr_add DTTT_selected selected');
            $('#submit-btn').show(); //hide submit button
            $('#loading-img').hide(); //hide submit button
            $('.image-upload-form').parsley().reset();
            $.toaster({priority: 'success', title: '', message: 'Image Uploaded Successfully'});
            if(data.grid == "staff_profile_pic"){
                loadPhoto();
            }else{
                $("#" + data.grid).dataTable().fnDraw();
            }
            $('.close-modal').trigger('click');
        }
        function beforeSubmit() {
            //check whether browser fully supports all File API
            if (window.File && window.FileReader && window.FileList && window.Blob) 
            {
                if (!$('#FileInput').val()) //check empty input filed
                {
                    $.toaster({priority: 'danger', title: '', message: 'Select an image file'});
                    return false;
                }
                var fsize = $('#FileInput')[0].files[0].size; //get file size
                var ftype = $('#FileInput')[0].files[0].type; // get file type
                if (fsize > 1048576)
                {
                    $.toaster({priority: 'danger', title: '', message: 'File is too large, it should be less than 1 MB'});
                    return false
                }
                $('#alert-prompt').show();
                $('#submit-btn').hide(); //hide submit button
                $('#loading-img').show(); //hide submit button
                $("#output").html("");
            } else {
                $.toaster({priority: 'danger', title: '', message: 'Please upgrade your browser, because your current browser lacks some new features we need!'});
                return false;
            }
        }
    });
    $(document).ready(function () {
        $("table tbody").on("click", "a.cancel_session", function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var selected_id = rowData[0];
                var status = 1;
                var TABLE_NAME = grid.attr('table');
                var item = $(this);
                var msg = '';
                if ($(this).hasClass('btn-warning')) {
                    status = "Cancelled";
                    msg = 'Are you sure you want to cancel this session?';
                }
                bootbox.confirm(msg, function (result) {
                    if (result) {
                        $.ajax({
                            url: "<?php echo base_url() ?>" + "service/Rest_shared/changeStatus/selected_id/" + selected_id + "/status/" + status + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                            success: function (data) {
                                if (data.message == 'no enough privilege') {
                                    $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                                    return;
                                }
                                if (data.message) {
                                    if (data.status == "Cancelled") {
                                        $.toaster({priority: 'success', title: '', message: 'Session cancelled successfully!'});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    } else {
                                        $.toaster({priority: 'success', title: '', message: 'Session Activated!'});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    }
                                } else {
                                    $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                                    $("#" + data.grid).dataTable().fnDraw();
                                }
                            }
                        });
                    }
                }).find(".modal-dialog").css("width", "30%");
            }
        });
        $("table tbody").on("click", "a.delete", function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var selected_id = rowData[0];
                var status = 1;
                var TABLE_NAME = grid.attr('table');
                var item = $(this);
                var msg = '';
                if ($(this).hasClass('btn-warning')) {
                    if(TABLE_NAME == "users"){
                        status = 1;
                        msg = 'Are you sure you want to ban this user?';
                    }else if(TABLE_NAME == "pos_receipt_book_used"){
                        status = 0;
                        msg = 'Are you sure you want to cancel?';
                    }else if(TABLE_NAME == "leave_entry_log"){
                        status = 0;
                        msg = 'Are you sure you want to cancel?';
                    }else{
                        status = 0;
                        msg = 'Are you sure you want to deactivate?';
                    }
                } else {
                    if(TABLE_NAME == "users"){
                        status = 0;
                        msg = 'Are you sure you want to activate this user?';
                    } else if(TABLE_NAME == "leave_entry_log"){
                        status = 1;
                        msg = 'Are you sure you want to apply?';
                    }else{
                        status = 1;
                        msg = 'Are you sure you want to activate?';
                    }
                }
                bootbox.confirm(msg, function (result) {
                    if (result) {
                        $.ajax({
                            url: "<?php echo base_url() ?>" + "service/Rest_shared/changeStatus/selected_id/" + selected_id + "/status/" + status + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                            success: function (data) {
                                if (data.message == 'no enough privilege') {
                                    $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                                    return;
                                }
                                if (data.message) {
                                    if (data.status == 0) {
                                        if(data.table == 'users'){
                                            $.toaster({priority: 'success', title: '', message: 'User Activated successfully!'});
                                        }else if(data.table == "pos_receipt_book_used"){
                                            $.toaster({priority: 'success', title: '', message: 'Cancelled successfully!'});
                                        }else if(data.table == "leave_entry_log"){
                                            $.toaster({priority: 'success', title: '', message: 'Cancelled successfully!'});
                                        }else{
                                            $.toaster({priority: 'success', title: '', message: 'Deactivated successfully!'});
                                        }
                                        $("#" + data.grid).dataTable().fnDraw();
                                    } else {
                                        if(data.table == 'users'){
                                            $.toaster({priority: 'success', title: '', message: 'User Banned successfully!'});
                                        }else if(data.table == "leave_entry_log"){
                                            $.toaster({priority: 'success', title: '', message: 'Applied successfully!'});
                                       }else{
                                            $.toaster({priority: 'success', title: '', message: 'Activated successfully!'});
                                        }
                                        $("#" + data.grid).dataTable().fnDraw();
                                    }
                                } else {
                                    $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                                    $("#" + data.grid).dataTable().fnDraw();
                                }
                            }
                        });
                    }
                }).find(".modal-dialog").css("width", "30%");
            }
        });
        $("table tbody").on("click", "a.del_btn_datatable", function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var selected_id = rowData[0];
                var status = 1;
                var TABLE_NAME = grid.attr('table');
                var item = $(this);
                var msg = '';
                status = 2;
                msg = 'Are you sure you want to remove this entry?';
                bootbox.confirm(msg, function (result) {
                    if (result) {
                        $.ajax({
                            url: "<?php echo base_url() ?>" + "service/Rest_shared/deleteEntry/selected_id/" + selected_id + "/status/" + status + "/table_name/" + TABLE_NAME + "/grid/" + grid.attr("id"),
                            success: function (data) {
                                if (data.message == 'no enough privilege') {
                                    $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                                    return;
                                }
                                if (data.message) {
                                    if (data.status == 1) {
                                        var msg = 'Deleted Successfully!';
                                        $.toaster({priority: 'success', title: '', message: msg});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    } else {
                                        $.toaster({priority: 'danger', title: '', message: data.viewMessage});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    }
                                } else {
                                    $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                                    $("#" + data.grid).dataTable().fnDraw();
                                }
                            }
                        });
                    }
                }).find(".modal-dialog").css("width", "30%");
            }
        });
        $("table tbody").on("click", "a.add_to_stock", function () {
            if(modifyBit == 0){
                $.toaster({
                    priority: 'danger',
                    title: 'Access Denied',
                    message: 'You dont have permission to perform this action'
                });
            }else{
                var grid = $(this).closest("table");
                var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
                var selected_id = rowData[0];
                var status = 1;
                var TABLE_NAME = grid.attr('table');
                var item = $(this);
                var msg = '';
                status = 2;
                msg = 'Are you sure you want to add this entry to asset stock?';
                bootbox.confirm(msg, function (result) {
                    if (result) {
                        $.ajax({
                            url: "<?php echo base_url() ?>" + "service/Asset_data/add_to_stock_from_nadavaravu/selected_id/" + selected_id + "/grid/" + grid.attr("id"),
                            success: function (data) {
                                if (data.message == 'no enough privilege') {
                                    $.toaster({priority: 'danger', title: '', message: 'You don\'t have enough privilege to perform this action!'});
                                    return;
                                }
                                if (data.message) {
                                    if (data.status == 1) {
                                        var msg = 'Added to stock Successfully!';
                                        $.toaster({priority: 'success', title: '', message: msg});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    } else {
                                        $.toaster({priority: 'danger', title: '', message: 'Failed!'});
                                        $("#" + data.grid).dataTable().fnDraw();
                                    }
                                } else {
                                    $.toaster({priority: 'danger', title: '', message: 'Something went wrong. Try again!'});
                                    $("#" + data.grid).dataTable().fnDraw();
                                }
                            }
                        });
                    }
                }).find(".modal-dialog").css("width", "30%");
            }
        });
    });
    $.ajax({
        complete: function (data) {
            //console.log(data);
        }
    });
</script>
