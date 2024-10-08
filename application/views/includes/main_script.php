<?php $this->load->view('includes/modal_forms'); ?>
<?php $this->load->view('includes/dataTable'); ?>
<script type="text/javascript">
    var modifyBit = '<?php echo $permissions["modify_status"] ?>';
    if(modifyBit == 0){
        $(".plus_btn").hide();
    }
    var breadcrumbTEXT = '';
    $(".plus_btn").click(function() {
        plusClickedAction();
    });
    var addStatus = "1";
    if (addStatus != 1) {
        $('.plus_btn').hide();
        $('.plus-pop-up').hide();
        $('span.director').hide();
        $('#role').attr('disabled', true);
    }
    function plusClickedAction() {
        if(modifyBit == 0){
            $.toaster({
                priority: 'danger',
                title: 'Access Denied',
                message: 'You dont have permission to perform this action'
            });
        }else{
            breadcrumbTEXT = $('.breadcrumb li').last().text();
			breadcrumbLink = $('.breadcrumb li a').last().attr('href');
			$('.breadcrumb li').last().remove();
            $('.breadcrumb').append('<li class="breadcrumb-item"><a href="'+breadcrumbLink+'">'+breadcrumbTEXT+'</a></li>');
            $('.breadcrumb').append('<li class="breadcrumb-item"><b><a>Add</a></b></li>');
            $(".plus_btn").hide();
            $(".add_dtl").toggleClass("show_form_add");
            $(".dtl_tbl").toggleClass("show_form_add");
        }
    }
    $('.checkDuplicate').on('focusout', function() {
        var val = $(this).val();
        if (val == '') {
            return;
        }
        var table = $(this).attr('table');
        var field = $(this).attr('field');
        var item = $(this);
        $.ajax({
            type: "post",
            url: "<?php echo base_url() ?>service/Rest_shared_admin/checkDupe",
            data: {
                val: val,
                table: table,
                field: field,
                editVal: $('#selected_id').val()
            },
            success: function(data) {
                if (data == 'exists') {
                    $.toaster({
                        priority: 'danger',
                        title: 'Duplicate',
                        message: '"' + val + '" already exists!'
                    });
                    item.val('');
                }
            }
        });
    });
    function bind_select_box_year(start_year, last_year, domElement, selected) {
        selected = typeof selected !== 'undefined' ? selected : null;
        if (domElement == "position_year") {
            var string = '<option value="Till date">Till date</option>';
        } else {
            var string = '<option value="">select an item</option>';
        }
        for (var inc = start_year; inc <= last_year; inc++) {
            string += '<option value=' + inc + '>' + inc + '</option>'
        }
        $('.' + domElement).last().empty().append(string);
    }
    $("form.add-edit").submit(function(e) {
		$(".load").show();
        e.preventDefault();
        $('#btn_submit').prop('disabled', true);
        if(modifyBit == 0){
            $.toaster({
                priority: 'danger',
                title: 'Access Denied',
                message: 'You dont have permission to perform this action'
            });
			$(".load").hide();
        }else{
            $(this).find('input,select').removeAttr('disabled');
            if ($(this).parsley('validate')) {
                $(".load").show();
                var url = $(this).attr('action');
                var data = $(this).serialize();
                $('#alert-prompt').show();
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        $(".load").hide();
                        $('#btn_submit').prop('disabled', false);
                        data = JSON.parse(data);
                        if (data.grid == 'rolesAndPermissions') {
                            location.reload();
                        }
                        if (data.message == 'redirect') {
                            location.href = '<?php echo base_url() ?>auth/login';
                        }
                        if (data.message == 'no enough privilege') {
                            $.toaster({
                                priority: 'danger',
                                title: '',
                                message: 'You dont have enough privilege to perform this action!'
                            });
							$(".load").hide();
                            return;
                        }
                        if (data.message === 'error') {
                            $.toaster({
                                priority: 'danger',
                                title: '',
                                message: data.viewMessage
                            });
							$(".load").hide();
                        }
                        if (data.message == 'success') {
                            $.toaster({
                                priority: 'success',
                                title: '',
                                message: data.viewMessage
                            });
                            showInitialState();
                            clearControls();
                            $("#" + data.grid).dataTable().fnDraw();
							$(".load").hide();
                        } else if (typeof data.errors !== 'undefined' && typeof data.errors.username !== 'undefined') {
                            $('#email').addClass('parsley-error').focus();
                            $.toaster({
                                priority: 'danger',
                                title: '',
                                message: 'Email already in use'
                            });
							$(".load").hide();
                        }
                    },
                    complete: function(jqXHR, textStatus) {
                        $('#alert-prompt').hide();
						$(".load").hide();
                    }
                });
            } else {
                console.log($(this).parsley('error'));
				$(".load").hide();
            }
        }
    });
    $("form.popup-form").submit(function(e) {
        $(".load").show();
        e.preventDefault();
        if(modifyBit == 0){
            $.toaster({
                priority: 'danger',
                title: 'Access Denied',
                message: 'YOu dont have permission to perform this action'
            });
			$(".load").hide();
        }else{
            if ($(this).parsley('validate')) {
                $(".load").show();
                var url = $(this).attr('action');
                var data = $(this).serialize();
                $('#alert-prompt').show();
                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.grid == 'rolesAndPermissions') {
                            location.reload();
                        }
                        if (data.message == 'redirect') {
                            location.href = '<?php echo base_url() ?>auth/login';
                        }
                        if (data.message == 'no enough privilege') {
                            $.toaster({
                                priority: 'danger',
                                title: '',
                                message: 'You dont have enough privilege to perform this action!'
                            });
							$(".load").hide();
                            return;
                        }
                        if (data.message === 'error') {
                            $.toaster({
                                priority: 'danger',
                                title: '',
                                message: data.viewMessage
                            });
							$(".load").hide();
                        }
                        if (data.message == 'success') {
                            $.toaster({
                                priority: 'success',
                                title: '',
                                message: 'Saved Successfully'
                            });
                            clearControls();
                            clear_form1();
                            $("#" + data.grid).modal('hide');
							$(".load").hide();
                        } 
                    },
                    complete: function(jqXHR, textStatus) {
                        $('#alert-prompt').hide();
						$(".load").hide();
                    }
                });
                $(".load").hide();
            } else {
                console.log($(this).parsley('error'));
				$(".load").hide();
            }
        }
    });
    /* $('.Bookser_no').bind('copy paste', function (e) {
        e.preventDefault();
    });
    $('.alpnum').bind('copy paste', function (e) {
        e.preventDefault();
    });
    $('.alpha').bind('copy paste', function (e) {
        e.preventDefault();
    }); */
    
    $(".form-control").attr('autocomplete', 'off');
    function clearControls() {
        $("select").prop('selectedIndex', '');
        $('input:not([type="checkbox"])').not('.exclude-item').val('');
    }
    $('#cancelEdit').on('click', function() {
        showInitialState();
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });
    $('#cancelEdit1').on('click', function() {
        showInitialState();
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });
    /* $(".alpha").on("keypress keyup blur",function (e) {   
        var regex = new RegExp("^[a-zA-Z]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (e.which == 8 || e.which == 32 || e.which == 0) {
            return true;
        } else {
            if (regex.test(str)) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        }
    });
    $(".Bookser_no").on("keypress keyup blur",function (e) { 
        var regex = new RegExp("^[a-zA-Z0-9 ,()-/]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (e.which == 8 || e.which == 32 || e.which == 0) {
            return true;
        } else {
            if (regex.test(str)) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        }
    });
    $(".Bookser_no_alt").on("keypress keyup blur",function (e) { 
        var regex = new RegExp("^[a-zA-Z0-9 ,-/]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (e.which == 8 || e.which == 32 || e.which == 0) {
            return true;
        } else {
            if (regex.test(str)) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        }
    });
    $(".alpnum").on("keypress keyup blur",function (e) {      
        var regex = new RegExp("[a-zA-Z0-9]");    
        var key = e.keyCode || e.which;    
        key = String.fromCharCode(key);    
        if (!regex.test(key)) {    
            e.returnValue = false;    
            if (e.preventDefault)
            {    
                e.preventDefault();    
            }    
        }   
    });  */
    $('.numberswithdecimal').on("keypress keyup blur",function (event) {  
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            event.preventDefault();
        }
    });
    $(".numeric").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    }); 
    function showInitialState() {
        breadcrumbTEXT = $('.breadcrumb li').last().text();
        if(breadcrumbTEXT == "Add" || breadcrumbTEXT == "Edit" || breadcrumbTEXT == "Reschedule" || breadcrumbTEXT == "Cancel"){
            $('.breadcrumb li').last().remove();
			breadcrumbTEXTEND = $('.breadcrumb li').last().text();
			breadcrumbLink = $('.breadcrumb li a').last().attr('href');
			$('.breadcrumb li').last().remove();
			$('.breadcrumb').append('<li class="breadcrumb-item"><b><a href="'+breadcrumbLink+'">'+breadcrumbTEXTEND+'</a></b></li>');
        }
        $(".plus_btn").show();
        $(".add_dtl").toggleClass("show_form_add");
        $(".dtl_tbl").toggleClass("show_form_add");
    }
    $(document).ready(function() {
        $("body").tooltip({
            selector: '[data-toggle=tooltip]'
        });
    });
    function clear_form() {
        $("form.add-edit").find("input,textarea,select").each(function(index, element) {
            if ($(element)) {
                var tag = $(element)[0].tagName;
                switch (tag) {
                    case "INPUT":
                        $(element).not(':checkbox').not('.exclude-status').val("");
                        if ($(element).not('.exclude-status').is(":checkbox")) $(element).prop('checked', false);
                        break;
                    case "TEXTAREA":
                        $(element).not('.exclude-status').val("");
                        break;
                    case "SELECT":
                        if ($(element).hasClass("exclude-status")) $(element).val("");
                        var choose_an_item = 0;
                        $(element).find('option').each(function() {
                            if ($(element).val() == "") {
                                choose_an_item = 1;
                            }
                        });
                        if (choose_an_item == 1) {
                            $(element).val("");
                        } else {
                            $(element).val("");
                        }
                        if ($(element).hasClass("exclude-this")) $(element).val("1");
                        if ($(element).hasClass("exclude-banner")) $(element).val("_blank");
                        break;
                    default:
                        return false;
                }
            }
        });
    }
    function clear_form1() {
        $("form.popup-form").find("input,textarea,select").each(function(index, element) {
            if ($(element)) {
                var tag = $(element)[0].tagName;
                switch (tag) {
                    case "INPUT":
                        $(element).not(':checkbox').not('.exclude-status').val("");
                        if ($(element).not('.exclude-status').is(":checkbox")) $(element).prop('checked', false);
                        break;
                    case "TEXTAREA":
                        $(element).not('.exclude-status').val("");
                        break;
                    case "SELECT":
                        if ($(element).hasClass("exclude-status")) $(element).val("");
                        var choose_an_item = 0;
                        $(element).find('option').each(function() {
                            if ($(element).val() == "") {
                                choose_an_item = 1;
                            }
                        });
                        if (choose_an_item == 1) {
                            $(element).val("");
                        } else {
                            $(element).val("1");
                        }
                        if ($(element).hasClass("exclude-this")) $(element).val("1");
                        if ($(element).hasClass("exclude-banner")) $(element).val("_blank");
                        break;
                    default:
                        return false;
                }
            }
        });
    }
    function getDepartments(id, url, cat_id) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                cat_id: cat_id
            },
            success: function(data) {
                $("#" + id).html(data);
            }
        });
    }
    function getSubDepartments(select_id, url, val, drp_id, cat_id) {
        cat_id = typeof(cat_id) == 'undefined' ? '' : cat_id;
        $("#" + select_id).empty();
        $.ajax({
            url: url,
            data: {
                id: val,
                drp_id: drp_id,
                cat_id: cat_id
            },
            type: 'POST',
            success: function(data) {
                $("#" + select_id).html(data);
            }
        });
    }
    function isDataTable(nTable) {
        var settings = $.fn.dataTableSettings;
        for (var i = 0, iLen = settings.length; i < iLen; i++) {
            if (settings[i].nTable == nTable) {
                return true;
            }
        }
        return false;
    }
    $('input.mobile[type="number"]').on('input', function() {
        if (this.value.length >= 10) {
            this.value = this.value.slice(0, 10);
        }
    });
    $('input.rate[type="number"]').on('input', function() {
        if (this.value.length >= 10) {
            this.value = this.value.slice(0, 10);
        }
    });
    $('input.mobile[type="text"]').on('input', function() {
        var arr = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
        var allowed = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '+', '-', ' ', '/'];
        var lengthPhone = 0;
        var final = '';
        for (var inc = 0; inc < $(this).val().length; inc++) {
            if ($.inArray($(this).val().charAt(inc), allowed) != -1) {
                final += $(this).val()[inc];
            }
        }
        $(this).val(final);
        for (var inc = 0; inc < final.length; inc++) {
            if ($.inArray(final.charAt(inc), arr) != -1) {
                lengthPhone++;
            }
        }
        console.log(lengthPhone);
        if (lengthPhone > 10) {
            $(this).val($(this).val().slice(0, -1));
        }
    });
    function ValidateEmail(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail)) {
            return (true);
        }
        return (false);
    }
    $(window).on('popstate', function(event) {
        location.reload();
    });
    function convert_date(date_string){
        if(date_string == '' || date_string === null){
            return "";
        }else{
            var comp = date_string.split('-');
            var m = parseInt(comp[1], 10);
            var d = parseInt(comp[2], 10);
            var y = parseInt(comp[0], 10);
            var date = new Date(y,m-1,d);
            if (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d) {
                var date    = new Date(date_string);
                var yr      = date.getFullYear();
                var month = +date.getMonth() + +1; 
                month = month < 10 ? '0' + month : month;
                var day     = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate();
                newDate = day + '-' + month + '-' + yr;
                return newDate;
            } else {
                return date_string;
            }
        }
    }
    /**Salary Calculation */
    function calculate_da(basic_pay){
        /**DA Calculation*/
        var da = (basic_pay*0.1);
        return roundUp(da);
    }
    function calculate_pf(basic_pay){
        var basicPayWithDa = +basic_pay + +(basic_pay*0.1);
        if(basicPayWithDa > 15000){
            var pf = 1800;
        }else{
            var pf = ((+basic_pay + +(basic_pay*0.1))*0.12);
        }
        return roundUp(pf);
    }
    function roundUp(rating) {
        var explodrat = String(rating).split('.');
        if((explodrat.length)>1) {
            var one = String(explodrat[1]).charAt(0);
            var check_num = Number(one); 
            if(check_num < 5) {
                rating = explodrat[0]; 
            }else{
                rating = +explodrat[0] + +1; 
                rating = rating; 
            }
        }else{
            rating = rating;
        }  
        return parseFloat(rating).toFixed(2);
    }
</script>
