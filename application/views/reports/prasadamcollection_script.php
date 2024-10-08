<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
   var date = new Date();
   var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
   var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
   $("#tableid").show();
   $('#from_date').datepicker({
        format: "dd-mm-yyyy",
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    $(".btn_print_html").hide();
    $(".pdf").hide();
    $("#variableInputType").hide();
    $("#variableInputType1").hide();
    get_reports();
    <?php if($this->session->userdata('temple')==1){?>
             $(".poojaid_sub1").show();
             $(".poojaid_sub2").show();
    <?php }
     else { ?>
             $(".poojaid_sub1").hide();
             $(".poojaid_sub2").hide();
    <?php } ?>
     $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_type_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+v.name+'</option>';
            });
            $("#type").append(string);
        }
    });
    $("#type").change(function(){
        get_item_drop_down(0);
    });
    function get_item_drop_down(val){
        if($("#type").val() == "Pooja"){
            $("#variableInputType").show();
			$("#variableInputTypeSpan1").html("<?php echo $this->lang->line('pooja'); ?>");
            $("#variableInputType1").show();
            $.ajax({
                url: '<?php echo base_url() ?>service/Pooja_category_data/get_pooja_category_drop_down1',
                type: 'GET',
                success: function (data) {
                    var string = '<option value="">Select Pooja</option>';
                    $.each(data.pooja_category, function (i, v) {
                        
                         if(v.temple_id==2){
                                var temple='('+v.temple+')';
                            }
                            else if(v.temple_id==3){
                                var temple='('+v.temple+')';
                            }else{ var temple="";}

                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.category+temple+'</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.category+temple+'</option>';
                        }
                    });
                    $("#item").html(string);
                }
            });
            $.ajax({
                url: '<?php echo base_url() ?>service/Pooja_data/get_pooja_list1',
                type: 'GET',
                success: function (data) {
                    var string = '<option value="">Select Pooja</option>';
                    $.each(data.pooja, function (i, v) {
                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.pooja_name_eng+'</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.pooja_name_eng+'</option>';
                        }
                    });
                    $("#pooja").html(string);
                }
            });
        }else if($("#type").val() == "Prasadam"){
            $("#variableInputType").show();
			$("#variableInputTypeSpan1").html("<?php echo $this->lang->line('prasadam'); ?>");
            $("#variableInputType1").show();
            $.ajax({
                url: '<?php echo base_url() ?>service/Item_category_data/get_item_category_drop_down', 
                type: 'GET', 
                success: function (data) {
                    var string = '<option value="">Select Prasadam </option>';
                    $.each(data.item_category, function (i, v) {
                        if(v.temple_id==2){
                                var temple='('+v.temple+')';
                            }
                            else if(v.temple_id==3){
                                var temple='('+v.temple+')';
                            }else{ var temple="";}

                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.category +temple +'</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.category +temple+'</option>';
                        }
                    });
                    $("#item").html(string);
                }
            });
            $.ajax({
                url: '<?php echo base_url() ?>service/Item_data/get_prasadam_drop_down',
                type: 'GET',
                success: function (data) {
                    var string = '<option value="">Select Prasadam</option>';
                    $.each(data.prasadam, function (i, v) {
                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.name+'</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.name+'</option>';
                        }
                    });
                    $("#pooja").html(string);
                }
            });
        }else if($("#type").val() == "Asset"){
            $("#variableInputType").show();
            $("#variableInputType1").hide();

            $.ajax({
                url: '<?php echo base_url() ?>service/Asset_category_data/get_asset_category_drop_down1', 
                type: 'GET', 
                success: function (data) {
                    var string = '<option value="">Select Asset</option>';
                    $.each(data.asset_category, function (i, v) {
                        if(v.temple_id==2){
                                var temple='('+v.temple+')';
                            }
                            else if(v.temple_id==3){
                                var temple='('+v.temple+')';
                            }else{ var temple="";}
                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.category + temple+'</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.category +temple+'</option>';
                        }
                    });
                    $("#item").html(string);
                }
            });
        }
        else if($("#type").val() == "Mattu Varumanam"){
            $("#variableInputType").show();
                $("#variableInputType1").hide();

            $.ajax({
                url: '<?php echo base_url() ?>service/Transaction_head_data/get_transaction_head_drop_down1', 
                type: 'get', 
                success: function (data) {
                    var string = '<option value="">Select Mattu Varumanam</option>';
                    $.each(data.transaction_head, function (i, v) {
                        if(val == v.id){
                            string += '<option value="' + v.id + '" selected>'+ v.head_eng + '</option>';
                        }else{
                            string += '<option value="' + v.id + '">'+ v.head_eng + '</option>';
                        }
                    });
                    $("#item").html(string);
                }
            });
        }
        else if($("#type").val() == ""){
           // $("#variableInputType").hide();
            var string = '<option value=""></option>';
             $("#item").html(string);
             $("#item").val();
        }
        else{
            $("#variableInputType").hide();
            $("#variableInputType1").hide();
            $("#item").val();
            // var string = '<option value=""></option>';
            // $("#variableInputType").val(string);
        }
    }
    $("#btn_submit").click(function(){
        get_reports();
    });
   
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var item = $("#item").val();
        var pooja = $("#pooja").val();
            if(type=='Pooja')
            {
         
                $("#tableid").show();
               $("#poojaid").show();$("#itemid").hide();$("#assetid").hide();$("#postalid").hide(); $("#hallid").hide(); $("#donid").hide();$("#annid").hide(); $("#baliid").hide();$("#report_body_income").hide();
             }
             else  if(type=='Prasadam'){
                $(".poojaid_sub1").hide();
               $(".poojaid_sub2").hide();
                $("#tableid").show();
               $("#poojaid").hide(); $("#itemid").show(); $("#assetid").hide();$("#postalid").hide();$("#hallid").hide(); $("#donid").hide();$("#annid").hide();$("#baliid").hide();$("#report_body_income").hide();}
             else  if(type=='Asset'){
                $(".poojaid_sub1").hide();
                $(".poojaid_sub2").hide();
                $("#tableid").show();
                $("#poojaid").hide();  $("#itemid").hide();  $("#assetid").show(); $("#postalid").hide(); $("#hallid").hide(); $("#donid").hide(); $("#annid").hide(); $("#baliid").hide(); $("#report_body_income").hide();}
             else  if(type=='Postal'){
                $(".poojaid_sub1").hide();
              $(".poojaid_sub2").hide();
                $("#tableid").show();
                $("#poojaid").hide();  $("#itemid").hide(); $("#assetid").hide(); $("#postalid").show();$("#hallid").hide();  $("#donid").hide();  $("#annid").hide(); $("#baliid").hide(); $("#report_body_income").hide();}
             else  if(type=='Hall'){
                $(".poojaid_sub1").hide();
                $(".poojaid_sub2").hide();
                $("#poojaid").hide();  $("#itemid").hide(); $("#assetid").hide(); $("#postalid").hide();$("#hallid").show();  $("#donid").hide(); $("#annid").hide(); $("#baliid").hide(); $("#report_body_income").hide();}
             else  if(type=='Donation'){
                $("#poojaid").hide();  $("#itemid").hide();$("#assetid").hide(); $("#postalid").hide();  $("#hallid").hide();  $("#donid").show();$("#annid").hide();$("#baliid").hide();$("#report_body_income").hide();}
             else  if(type=='Annadhanam'){
                $(".poojaid_sub1").hide();
             $(".poojaid_sub2").hide();
                $("#poojaid").hide(); $("#itemid").hide();$("#assetid").hide();$("#postalid").hide();$("#hallid").hide(); $("#donid").hide();$("#annid").show();$("#baliid").hide();$("#report_body_income").hide();}
             else  if(type=='Balithara'){
                $(".poojaid_sub1").hide();
              $(".poojaid_sub2").hide();
                $("#poojaid").hide(); $("#itemid").hide();$("#assetid").hide();$("#postalid").hide();$("#hallid").hide(); $("#donid").hide();$("#annid").hide();$("#baliid").show();$("#report_body_income").hide();}
             else  if(type=='Mattu Varumanam'){
                $(".poojaid_sub1").hide();
                $(".poojaid_sub2").hide();
                $("#poojaid").hide(); $("#itemid").hide();$("#assetid").hide();$("#postalid").hide();$("#hallid").hide(); $("#donid").hide();$("#annid").hide();$("#baliid").hide();$("#report_body_income").show();}
             else {
                $("#poojaid").show(); $("#itemid").show();$("#assetid").show();$("#postalid").show();$("#hallid").show(); $("#donid").show();$("#annid").show();$("#baliid").show();$("#report_body_income").show();
             }
        //var pooja = $("#pooja").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_prasadamwise_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,item:item,pooja:pooja},
            success: function (data) {
				//Prasadam
                reportData = "";
                if (data.report.length === 0) {                   
                    reportData += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var  total_amount = 0.00;
                    var last_id = "";
                    var last_category = "";
                    var total_category_amount = 0.00;
                    $.each(data.report, function (i, v) {
                        j++;                        
                        if(j == 1){
                            last_id = v.item_category_id;
                            last_category = v.category;
                        }
                        total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                        if(last_id != v.item_category_id){
                            reportData += "<tr>";
                            reportData += "<th colspan='5' style='text-align:right'>Total</th>";                           
                            reportData += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                            reportData += "</tr>";
                            total_category_amount = "0.00";
                        }
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        if(v.count == "0"){
                            totalr = v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData += "<tr>";
                            reportData += "<td>"+j+"</td>";
                            reportData += "<td>"+v.category+"</td>";
                            reportData += "<td>"+v.name+"(<?php echo $this->lang->line('receipt_book'); ?>)</td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'></span></td>";
                            reportData += "<td></td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData += "<tr>";
                            reportData += "<td>"+j+"</td>";
                            reportData += "<td>"+v.category+"</td>";
                            reportData += "<td>"+v.name_eng+"</td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+v.count+"</span></td>";
                            reportData += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
                            reportData += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                        total_category_amount = +total_category_amount + +totalr;
                        last_id = v.item_category_id;
                    });   
                    total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total'); ?></th>";                           
                    reportData += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                    reportData += "</tr>";
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData += "<tr>";
                    reportData += "<th colspan='5' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                }              
				$("#report_body").html(reportData);
				//Asset
                reportData1 = "";
                if (data.report1.length === 0) {               
                	reportData1 += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var total_amount = 0.00;
                    $.each(data.report1, function (i, v) {
                        j++;
						reportData1 += "<tr>";
						reportData1 += "<td>"+j+"</td>";
						reportData1 += "<td>"+v.category+"</td>";
						reportData1 += "<td class='amntWidth'><span class='amntRight'>"+v.total_quantity+"</span></td>";
						reportData1 += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
						reportData1 += "</tr>";                       
                        total_amount = +total_amount + +v.amount;
                    });  
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData1 += "<tr>";
                    reportData1 += "<th colspan='3' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData1 += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData1 += "</tr>";
                }
                $("#report_body2").html(reportData1);
				//Postal
				reportData2 = "";
                if (data.report2.length === 0) {
                  	reportData2 += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var total_amount =0.00;
                    $.each(data.report2, function (i, v) {
                        j++; 
						reportData2 += "<tr>";
						reportData2 += "<td>"+j+"</td>";
						reportData2 += "<td>"+v.category+"</td>";
						reportData2 += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
						reportData2 += "<td class='amntWidth'><span class='amntRight'>"+v.count+"</span></td>";
						reportData2 += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
						reportData2 += "</tr>";
                        total_amount = +total_amount + +v.amount;
                    });
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData2 += "<tr>";
                    reportData2 += "<th colspan='4' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData2 += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                }
				$("#report_body3").html(reportData2);				
                //Hall
                $("#report_body").html(reportData);
                reportData_hall = "";
                if (data.report_hall.length === 0) {
                    reportData_hall += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf").show();
                    var j = 0;
                    var total_amount = 0.00;
                    $.each(data.report_hall, function (i, v) {
					j++;
						totalr = +v.amount;
						var total= parseFloat(totalr,10).toFixed(2);
						reportData_hall += "<tr>";
						reportData_hall += "<td>"+j+"</td>";
						reportData_hall += "<td></td>";
						reportData_hall += "<td>"+v.category+"</td>";
						reportData_hall += "<td></td>";
						reportData_hall += "<td></td>";
						reportData_hall += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
						reportData_hall += "</tr>";
                        total_amount = +total_amount + +v.amount;
                    });   
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_hall += "<tr>";
                    reportData_hall += "<th colspan='5' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_hall += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                }
                $("#report_body_hall").html(reportData_hall);
                //Donation
                reportData_D = "";
                if (data.report_donation.length === 0) {                  
                    reportData_D += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf").show();
                    var j = 0;
                    var total_amount =0.00;
                    $.each(data.report_donation, function (i, v) {
                        j++;
                        if(v.count == "0"){
                            totalr = v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData_D += "<tr>";
                            reportData_D += "<td>"+j+"</td>";
                            reportData_D += "<td>"+v.category_eng+"</td>";
                            reportData_D += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData_D += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData_D += "<tr>";
                            reportData_D += "<td>"+j+"</td>";
                            reportData_D += "<td>"+v.category_eng+"</td>";
                            reportData_D += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
                            reportData_D += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                    });   
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_D += "<tr>";
                    reportData_D += "<th colspan='2' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_D += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData_D += "</tr>";
                }
                $("#report_body_donation").html(reportData_D);
                //Annandanam
                reportData_A = "";
                if (data.report_ann.length === 0) {                  
                    reportData_A += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var total_amount = 0.00;
                    $.each(data.report_ann, function (i, v) {
                        j++; 
                        if(v.count == "1"){
                            var total= parseFloat(v.amount,10).toFixed(2);
                            reportData_A += "<tr>";
                            reportData_A += "<td>"+j+"</td>";
                            reportData_A += "<td><?php echo $this->lang->line('annadhanam') ?>(<?php echo $this->lang->line('receipt_book'); ?>)</td>";
                            reportData_A += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData_A += "</tr>";
                        }else{
                            var total= parseFloat(v.amount,10).toFixed(2);
                            reportData_A += "<tr>";
                            reportData_A += "<td>"+j+"</td>";
                            if(v.booked_type=='ANNADHANAM'){
                                var book= '<?php echo $this->lang->line('fixed_annadhanam') ?>';
                            }
                            else{
                                var book= '<?php echo $this->lang->line('normal_annadhanam') ?>';
                            }
                            reportData_A += "<td>"+book+"</td>";
                            reportData_A += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData_A += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                    }); 
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_A += "<tr>";
                    reportData_A += "<th colspan='2' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_A += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData_A += "</tr>";
                }
                $("#report_body_ann").html(reportData_A);          
            	//Balithara
				reportData_bal="";
				if(data.report_bali.length === 0) {
                  	reportData_bal += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var total_amount =0.00;
                    $.each(data.report_bali, function (i, v) {
                        j++; 
						var total= parseFloat(v.amount,10).toFixed(2);
						reportData_bal += "<tr>";
						reportData_bal += "<td>"+j+"</td>";
						reportData_bal += "<td>"+v.name+"</td>";
						reportData_bal += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
						reportData_bal += "</tr>";                        
                        total_amount = +total_amount + +total;
                    });
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_bal += "<tr>";
                    reportData_bal += "<th colspan='2' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_bal += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData_bal += "</tr>";
                }
				$("#report_body_bali").html(reportData_bal);
				//Mattuvarumanam
                reportData_In = "";
                if (data.mattu_in.length === 0) {
					reportData_In = '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var  total_amount =0.00;
                    $.each(data.mattu_in, function (i, v) {
						if(v.amount != 0){
							j++;                           
							reportData_In += "<tr>";
							reportData_In += "<td>"+j+"</td>";
							reportData_In += "<td>"+v.category+"</td>";
							reportData_In += "<td class='amntWidth'><span class='amntRight'>"+parseFloat(v.amount,10).toFixed(2)+"</span></td>";
							reportData_In += "</tr>";
							total_amount = +total_amount + +v.amount;
						}
                    });  
					reportData_In += "<tr>";
					reportData_In += "<th colspan='2' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";                           
					reportData_In += "<th class='amntWidth' style='font-size: 12px'><span class='amntRight'>"+parseFloat(total_amount,10).toFixed(2)+"</span></th>";
					reportData_In += "</tr>";
					reportData_In +="</tbody>";
					reportData_In +="</table>";
                }
				$("#report_body_mattu").html(reportData_In); 
            }
		});
		//Main Temple Pooja
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,item:item,pooja:pooja},
            success: function (data) {
                reportDataa = "";
                if (data.report.length === 0) {
                	reportDataa += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{
                    $(".pdf").show();
                    var j = 0;
                    var total_amount =0.00;
                    var last_id = "";
                    var last_category = "";
                    var total_category_amount = 0.00;
                    $.each(data.report, function (i, v) {
                        j++; 
                        if(j == 1){
                            last_id = v.pooja_category_id;
                            last_category = v.category_alt;
                        }
                        total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                        if(last_id != v.pooja_category_id){
                            reportDataa += "<tr>";
                            reportDataa += "<th colspan='5' style='text-align:right'>Total</th>";                           
                            reportDataa += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                            reportDataa += "</tr>";
                            total_category_amount = "0.00";
                        }
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        if(v.count == "0"){
                            totalr = v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportDataa += "<tr>";
                            reportDataa += "<td>"+j+"</td>";
                            reportDataa += "<td>"+v.category+"</td>";
                            reportDataa += "<td>"+v.pooja_name+"(<?php echo $this->lang->line('receipt_book'); ?>)</td>";
                            reportDataa += "<td class='amntWidth'><span class='amntRight'></span></td>";
                            reportDataa += "<td></td>";
                            reportDataa += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportDataa += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            var temple="";
                            if(v.temple_id==2){
                                var temple="(ചൊവ്വാഴ്‌ചക്കാവ്)" ;
                            }else if(v.temple_id==3){
                                var temple="(മാതംപിള്ളി)";
                            }else{
								var temple="";
							}
							reportDataa += "<tr>";
							reportDataa += "<td>"+j+"</td>";
							reportDataa += "<td>"+v.category+"</td>";
							reportDataa += "<td>"+v.name+"</td>";
							reportDataa += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
							reportDataa += "<td class='amntWidth'><span class='amntRight'>"+v.count+"</span></td>";
							reportDataa += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
							reportDataa += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                        total_category_amount = +total_category_amount + +totalr;
                        last_id = v.pooja_category_id;
                    });  
                    total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                    reportDataa += "<tr>";
                    reportDataa += "<th colspan='5' style='text-align:right'>Total</th>";                           
                    reportDataa += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                    reportDataa += "</tr>";
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportDataa += "<tr>";
                    reportDataa += "<th colspan='5' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportDataa += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportDataa += "</tr>";
                }
                $("#report_bodyy").html(reportDataa);
            }
        });
        //Sub Temple 1 Pooja  
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_subreport',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,item:item,pooja:pooja},
            success: function (data) {
                reportData_sub = "";
                if (data.report_1.length === 0) {
                    reportData_sub += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{                   
                    $(".pdf").show();
                    var j = 0;
                    var  total_amount =0.00;
                    var last_id = "";
                    var last_category = "";
                    var total_category_amount = 0.00;
                    $.each(data.report_1, function (i, v) {
                        j++; 
                        if(j == 1){
                            last_id = v.pooja_category_id;
                            last_category = v.category_alt;
                        }
                        total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                        if(last_id != v.pooja_category_id){
                            reportData_sub += "<tr>";
                            reportData_sub += "<th colspan='5' style='text-align:right;font-size: 15px'>Total</th>";                           
                            reportData_sub += "<th class='amntWidth' style='font-size: 13px'><span class='amntRight'>"+total_category_amount+"</span></th>";
                            reportData_sub += "</tr>";
                            total_category_amount = "0.00";
                        }
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        if(v.count == "0"){
                            totalr = v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData_sub += "<tr>";
                            reportData_sub += "<td>"+j+"</td>";
                            reportData_sub += "<td>"+v.category+"</td>";
                            reportData_sub += "<td>"+v.pooja_name+"(<?php echo $this->lang->line('receipt_book'); ?>)</td>";
                            reportData_sub += "<td class='amntWidth'><span class='amntRight'></span></td>";
                            reportData_sub += "<td></td>";
                            reportData_sub += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData_sub += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            var temple="";
                            if(v.temple_id==2){
                                	var temple="(ചൊവ്വാഴ്‌ചക്കാവ്)" ;
                            }else if(v.temple_id==3){
                                var temple="(മാതംപിള്ളി)";
                            }else{
								var temple="";
							}
							reportData_sub += "<tr>";
							reportData_sub += "<td>"+j+"</td>";
							reportData_sub += "<td>"+v.category+"</td>";
							reportData_sub += "<td>"+v.name+"</td>";
							reportData_sub += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
							reportData_sub += "<td class='amntWidth'><span class='amntRight'>"+v.count+"</span></td>";
							reportData_sub += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
							reportData_sub += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                        total_category_amount = +total_category_amount + +totalr;
                        last_id = v.pooja_category_id;
                    });  
                    total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                    reportData_sub += "<tr>";
                    reportData_sub += "<th colspan='5' style='text-align:right'>Total</th>";                           
                    reportData_sub += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                    reportData_sub += "</tr>";
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_sub += "<tr>";
                    reportData_sub += "<th colspan='5' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_sub += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData_sub += "</tr>";
                }
                $("#report_bodyy_sub").html(reportData_sub);
            }
        });
        //Sub Temple 2 Pooja  
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_subreport1',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,item:item,pooja:pooja},
            success: function (data) {
                reportData_sub1 = "";
                if (data.report_1.length === 0) {
                    reportData_sub1 += '<tr><td colspan="20" style="text-align:center"><b><?php echo $this->lang->line('no_records_found'); ?></b></td></tr>';
                }else{                   
                    $(".pdf").show();
                    var j = 0;
                    var  total_amount =0.00;
                    var last_id = "";
                    var last_category = "";
                    var total_category_amount = 0.00;
                    $.each(data.report_1, function (i, v) {
                        j++; 
                        if(j == 1){
                            last_id = v.pooja_category_id;
                            last_category = v.category_alt;
                        }
                        total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                        if(last_id != v.pooja_category_id){
                            reportData_sub1 += "<tr>";
                            reportData_sub1 += "<th colspan='5' style='text-align:right'>Total</th>";                           
                            reportData_sub1 += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                            reportData_sub1 += "</tr>";
                            total_category_amount = "0.00";
                        }
                        var date = "<?php echo date('d-m-Y',strtotime("+v.date+")) ?>";
                        if(v.count == "0"){
                            totalr = v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            reportData_sub1 += "<tr>";
                            reportData_sub1 += "<td>"+j+"</td>";
                            reportData_sub1 += "<td>"+v.category+"</td>";
                            reportData_sub1 += "<td>"+v.pooja_name+"(<?php echo $this->lang->line('receipt_book'); ?>)</td>";
                            reportData_sub1 += "<td class='amntWidth'><span class='amntRight'></span></td>";
                            reportData_sub1 += "<td></td>";
                            reportData_sub1 += "<td class='amntWidth'><span class='amntRight'>"+total+"</span></td>";
                            reportData_sub1  += "</tr>";
                        }else{
                            totalr = +v.amount;
                            var total= parseFloat(totalr,10).toFixed(2);
                            var temple="";
                            if(v.temple_id==2){
                                var temple="(ചൊവ്വാഴ്‌ചക്കാവ്)";
                            }else if(v.temple_id==3){
                                var temple="(മാതംപിള്ളി)";
                            }else{
								var temple="";
							}
							reportData_sub1 += "<tr>";
							reportData_sub1 += "<td>"+j+"</td>";
							reportData_sub1 += "<td>"+v.category+"</td>";
							reportData_sub1 += "<td>"+v.name+"</td>";
							reportData_sub1 += "<td class='amntWidth'><span class='amntRight'>"+v.rate+"</span></td>";
							reportData_sub1 += "<td class='amntWidth'><span class='amntRight'>"+v.count+"</span></td>";
							reportData_sub1 += "<td class='amntWidth'><span class='amntRight'>"+v.amount+"</span></td>";
							reportData_sub1 += "</tr>";
                        }
                        total_amount = +total_amount + +total;
                        total_category_amount = +total_category_amount + +totalr;
                        last_id = v.pooja_category_id;
                    });  
                    total_category_amount= parseFloat(total_category_amount,10).toFixed(2);
                    reportData_sub1 += "<tr>";
                    reportData_sub1 += "<th colspan='5' style='text-align:right'><?php echo $this->lang->line('total'); ?></th>";                           
                    reportData_sub1 += "<th class='amntWidth'><span class='amntRight'>"+total_category_amount+"</span></th>";
                    reportData_sub1 += "</tr>";
                    var total_rate= parseFloat(total_amount,10).toFixed(2); 
                    reportData_sub1 += "<tr>";
                    reportData_sub1 += "<th colspan='5' style='text-align:right;font-size: 12px'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData_sub1 += "<th style='text-align:right;font-size: 13px'>"+total_rate+"</th>";
                    reportData_sub1 += "</tr>";
                }
                $("#report_bodyy_sub1").html(reportData_sub1);
            }
        });
    }
    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var type = $("#type").val();
        var pooja = $("#pooja").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_poojawise_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date,type:type,pooja:pooja},
            success: function (data) {
                var w = window.open('report:blank');
                w.document.open();
                w.document.write(data.page);
                w.document.close();
            }
        });
    });
    $(".btn_clear").click(function(){
        $("#from_date").val("<?php echo date('d-m-Y') ?>");
        $("#to_date").val("<?php echo date('d-m-Y') ?>");
        $("#item").val("");
        $("#type").val("");
        $("#pooja").val("");
        get_reports();
    });
    $(".pdf").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var item = $("#item").val();
        var type = $("#type").val();
        var pooja = $("#pooja").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_all_pdf?from_date='+from_date+'&to_date='+to_date+'&type='+type+'&item='+item+'&pooja='+pooja, '_blank');       
    });
</script>
