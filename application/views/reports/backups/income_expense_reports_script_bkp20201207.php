
<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
   var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
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
    $(".pdf_report").hide();
    $(".pdf_report1").hide();

    get_reports();
    
    $("#btn_submit").click(function(){
        get_reports();
    });
    function get_reports(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_allincome_report',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
            success: function (data) {
				var indexStoreArray = [];
                var cashIncome = 0;
                var cardIncome = 0;
                var moIncome = 0;
                var chequeIncome = 0;
                var ddIncome = 0;
                var ttIncome = 0;
                var onlineIncome = 0;
                var reportData1 = "";
				var pooja="";
				var annadanamAmount = 0;
				var annedanamCash = 0;
				var annadanamCard = 0;
				var annadanamMo = 0;
				var annadanamDd = 0;
				var annadanamCheque = 0;
                var annadanamOnline = 0;
                
                var ulsavamLabel = "";
                var ulsavamAmount = 0;
                var ulsavamCash = 0;
                var ulsavamCard = 0;
                var ulsavamMo = 0;
                var ulsavamDd = 0;
                var ulsavamCheque = 0;
                var ulsavamOnline = 0;

				$(".btn_print_html").show();
				$(".pdf_report").show();
				$(".pdf_report1").show();
				var j = 0;
				$.each(data.incomeReport, function (i, v) {
					if(v.item_section_id =="report167"){
						annadanamAmount = v.amount;
						annedanamCash = v.cash;
						annadanamCard = v.card;
						annadanamMo = v.mo;
						annadanamDd = v.dd;
						annadanamCheque = v.cheque;
                        annadanamOnline = v.online;
					}else{
						recash = 0;
						recard = 0;
						recheque = 0;
						remo = 0;
						redd = 0;
                        reonline = 0;
						$.each(data.receiptBookIncome, function (i, v1) {
							if(v1.receipt_type !== null){
								if(v.receipt_type==v1.receipt_type){
									if(v.receipt_type == "Pooja"){
										if(v.pooja_category_id==v1.pooja_category_id){
											recash= +recash+ + v1.amount;
											indexStoreArray.push(i);
										}
									}
									if(v.receipt_type=="Annadhanam"){
										recash= +recash+ + v1.amount;
										indexStoreArray.push(i);
									}
									if(v.receipt_type=="Prasadam"){
										if(v.item_category_id==v1.item_category_id){
											recash= +recash+ + v1.amount;
											indexStoreArray.push(i);
										}
									}
									if(v.receipt_type=="Asset"){
										recash= +recash+ + v1.amount;
									}
									if(v.receipt_type=="Postal"){
										recash= +recash+ + v1.amount;
										indexStoreArray.push(i);
									}
									if(v.receipt_type=="Balithara"){
										recash= +recash+ + v1.amount;
									}
									if(v.receipt_type=="Hall"){
										recash= +recash+ + v1.amount;
									}
									if(v.receipt_type=="Nadavaravu"){
										recash= +recash+ + v1.amount;
									}
									if(v.receipt_type=="Donation"){
										recash= +recash+ + v1.amount;
									}
								}
								if(v.item_section_id == "report171"){
									if(v1.category == "Mattu Varumanam"){
										recash= +recash+ + v1.amount;
										indexStoreArray.push(i);
									}
								}
							}
						});
						if(v.item_section_id == "report171"){
							$.each(data.mattuvarumanam, function (i, v1) {
								recash= +recash+ + v1.cash;
								recard= +recard+ + v1.card;
								remo= +remo+ + v1.mo;
								recheque= +recheque+ + v1.cheque;
								redd= +redd+ + v1.dd;
                                reonline= +reonline+ + v1.online;
							});
						}
						if(v.receipt_type == "Annadhanam"){
							recash= +recash+ + annedanamCash;
							recard= +recard+ + annadanamCard;
							remo= +remo+ + annadanamMo;
							recheque= +recheque+ + annadanamCheque;
							redd= +redd+ + annadanamDd;
                            reonline = +reonline + + annadanamOnline
						}
						var cash = v.cash*v.count;
						var cash1 = +cash + +recash;
						cashIncome= +cashIncome + +cash1;
						var card = v.card*v.count;
						card = +card + +recard;
						cardIncome= +cardIncome + +card;
						var mo = v.mo*v.count;
						mo = +mo + +remo;
						moIncome= +moIncome + +mo;
						var cheque = v.cheque*v.count;
						cheque = +cheque + +recheque;
						chequeIncome= +chequeIncome + +cheque;
						var dd = v.dd*v.count;
						dd = +dd + +redd;
						ddIncome= +ddIncome + +dd;
                        var online = v.online*v.count;
						online = +online + +reonline;
						onlineIncome= +onlineIncome + +online;
						var amount = v.amount*v.count;
						var amount1 = +amount + +recash + +recard + +remo + +recheque + +redd + +reonline;
						ttIncome = +ttIncome + +amount1;
						var recash=0;
                        if(v.temple_id == 1){
                            pooja_category_id = 44;
                            donation_category_id = 9;
                        }else if(v.temple_id ==2){
                            pooja_category_id = 34;
                            donation_category_id = 8;
                        }else if(v.temple_id == 3){
                            pooja_category_id = 40;
                            donation_category_id = 7;
                        }
						if(amount1 != 0){
                            if(v.receipt_type == "Pooja" && v.pooja_category_id == pooja_category_id){
                                 ulsavamAmount = +ulsavamAmount + +amount1;
                                 ulsavamCash = +ulsavamCash + +cash1;
                                 ulsavamCard = +ulsavamCard + +card;
                                 ulsavamMo = +ulsavamMo + +mo;
                                 ulsavamDd = +ulsavamDd + +dd;
                                 ulsavamCheque = +ulsavamCheque + +cheque;
                                 ulsavamOnline = +ulsavamOnline + +online;
                                 ulsavamLabel = v.category;
                            }else if(v.receipt_type == "Donation" && v.donation_category_id == donation_category_id){
                                 ulsavamAmount = +ulsavamAmount + +amount1;
                                 ulsavamCash = +ulsavamCash + +cash1;
                                 ulsavamCard = +ulsavamCard + +card;
                                 ulsavamMo = +ulsavamMo + +mo;
                                 ulsavamDd = +ulsavamDd + +dd;
                                 ulsavamCheque = +ulsavamCheque + +cheque;
                                 ulsavamOnline = +ulsavamOnline + +online;
                                 ulsavamLabel = v.category;
                            }else{
							j++;
							reportData1 += "<tr>";
							reportData1 += "<td style='width:30px'>"+j+"</td>";
							reportData1 += "<td>"+v.category+"</td>";                     
							reportData1 += "<td style='text-align:right'>"+parseFloat(cash1).toFixed(2)+"</td>"; 
							reportData1 += "<td style='text-align:right'>"+parseFloat(card).toFixed(2)+"</td>"; 
							reportData1 += "<td style='text-align:right'>"+parseFloat(mo).toFixed(2)+"</td>"; 
							reportData1 += "<td style='text-align:right'>"+parseFloat(cheque).toFixed(2)+"</td>"; 
							reportData1 += "<td style='text-align:right'>"+parseFloat(dd).toFixed(2)+"</td>"; 
                            reportData1 += "<td style='text-align:right'>"+parseFloat(online).toFixed(2)+"</td>"; 
							reportData1 += "<td style='text-align:right'>"+parseFloat(amount1).toFixed(2)+"</td>"; 
							reportData1 += "</tr>";  
						}
                      }
					}
				});
				if(ulsavamAmount > 0){
                j++;
                reportData1 += "<tr>";
                reportData1 += "<td style='width:30px'>"+j+"</td>";
                reportData1 += "<td>"+ulsavamLabel+"</td>";                    
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamCash).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamCard).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamMo).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamCheque).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamDd).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamOnline).toFixed(2)+"</td>";
                reportData1 += "<td style='text-align:right'>"+parseFloat(ulsavamAmount).toFixed(2)+"</td>";
                reportData1 += "</tr>"; 
				}
				$.each(data.receiptBookIncome, function (i, v) {
					if(jQuery.inArray(i,indexStoreArray) === -1){
						j++;
						cashIncome= +cashIncome + +v.amount;
						ttIncome = +ttIncome + +v.amount;
						reportData1 += "<tr>";
						reportData1 += "<td style='width:30px'>"+j+"</td>";
						reportData1 += "<td>"+v.category+"</td>";
						reportData1 += "<td style='text-align:right'>"+v.amount+"</td>"; 
						reportData1 += "<td style='text-align:right'>0.00</td>"; 
						reportData1 += "<td style='text-align:right'>0.00</td>"; 
						reportData1 += "<td style='text-align:right'>0.00</td>"; 
						reportData1 += "<td style='text-align:right'>0.00</td>";
                        reportData1 += "<td style='text-align:right'>0.00</td>";
						reportData1 += "<td style='text-align:right'>"+v.amount+"</td>";  
						reportData1 += "</tr>";
					}
				});
				reportData1 += "<tr>";
				reportData1 += "<th colspan='2' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(cashIncome).toFixed(2)+"</th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(cardIncome).toFixed(2)+"</th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(moIncome).toFixed(2)+"</th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(chequeIncome).toFixed(2)+"</th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(ddIncome).toFixed(2)+"</th>";
                reportData1 += "<th style='text-align:right'>"+parseFloat(onlineIncome).toFixed(2)+"</th>";
				reportData1 += "<th style='text-align:right'>"+parseFloat(ttIncome).toFixed(2)+"</th>";
                
				reportData1 += "</tr>";              
                $("#report_body").html(reportData1);
                var reportData2 = "";
				var cashExpense = 0;
				var cardExpense = 0;
				var moExpense = 0;
				var chequeExpense = 0;
				var ddExpense = 0;
                var onlineExpense = 0;
				var ttExpense = 0;
                if (data.expenseReport.length === 0) {
                    reportData2 += '<tr><td colspan="20" style="text-align:center"><b>No Records Found</b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    $(".pdf_report1").show();
                    var j = 0;
                    $.each(data.expenseReport, function (i, v) {
                        j++;
                        var cash = v.cash*v.count;
                        cashExpense= +cashExpense + +cash;
                        var card = v.card*v.count;
                        cardExpense= +cardExpense + +card;
                        var mo = v.mo*v.count;
                        moExpense= +moExpense + +mo;
                        var cheque = v.cheque*v.count;
                        chequeExpense= +chequeExpense + +cheque;
                        var dd = v.dd*v.count;
                        ddExpense= +ddExpense + +dd;
                        var online = v.online*v.count;
                        onlineExpense= +onlineExpense + +online;
                        var amount = v.amount*v.count;
                        ttExpense = +ttExpense + +amount;
                        reportData2 += "<tr>";
                        reportData2 += "<td style='width:30px'>"+j+"</td>";
                        reportData2 += "<td>"+v.category+"</td>";
                        reportData2 += "<td style='text-align:right'>"+parseFloat(cash).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(card).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(mo).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(cheque).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(dd).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(online).toFixed(2)+"</td>"; 
                        reportData2 += "<td style='text-align:right'>"+parseFloat(amount).toFixed(2)+"</td>"; 
                        reportData2 += "</tr>";
                    });  
                    reportData2 += "<tr>";
                    reportData2 += "<th colspan='2' style='text-align:right'><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(cashExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(cardExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(moExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(chequeExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(ddExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(onlineExpense).toFixed(2)+"</th>";
                    reportData2 += "<th style='text-align:right'>"+parseFloat(ttExpense).toFixed(2)+"</th>";
                    reportData2 += "</tr>";
                }
                $("#report_body1").html(reportData2);
                totalIncome = cashIncome + cardIncome + moIncome + chequeIncome + ddIncome + onlineIncome;
                var reportData3 = "";
                if (data.accountReport.length === 0) {
                    reportData3 += '<tr><td colspan="20" style="text-align:center"><b>No Records Found</b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    $(".pdf_report1").show();
                    var j = 1;var sum=0;
                    var total = data.pettyCashOpen;
                    reportData3 += "<tr>";
                    reportData3 += "<td style='width:30px'>1</td>";
                    reportData3 += "<td>Petty cash</td>";
                    reportData3 += "<td style='text-align:left'></td>";
                    reportData3 += "<td style='text-align:right'>"+data.pettyCashOpen+"</td>";
                    reportData3 += "</tr>";
                    $.each(data.accountReport, function (i, v) {
                        j++;
                        sum=v.opening;
                        //total=total+sum;
                        total= +total + +sum;
                        reportData3 += "<tr>";
                        reportData3 += "<td style='width:30px'>"+j+"</td>";
                        reportData3 += "<td>"+v.bank_eng+"</td>";
                        reportData3 += "<td>"+v.account_no+"</td>";
                        reportData3 += "<td style='text-align:right'>"+v.opening+"</td>";
                        reportData3 += "</tr>";
                    }); 
                    reportData3 += "<tr>";
                    reportData3 += "<td  style='text-align:right'></td>";
                    reportData3 += "<td style='text-align:right' colspan='2'>Total</td>";
                    reportData3 += "<td style='text-align:right' ><b>"+parseFloat((+total - +data.pettyCashOpen)).toFixed(2)+"</b></td>";
                    reportData3 += "</tr>"; 
                  //  $totalIncomeAmount = $bankWithdrawal + $totalIncome;
                }
                $("#report_body2").html(reportData3);
                    var defaultBankId = 0;
                    var defaultBank = "";
                    var totalSum = 0;
                    var bankSum = 0;
                    var reportData8 = "";
                    var j=0;
                    $.each(data.fdAccountsOpening, function (i, v) {
						if(v.st == 1){
							j++;
							if(j != 0){
								if(defaultBankId != v.bank_id){ 
									reportData8 += "<tr>";
									reportData8 += "<td></td>";
									reportData8 += "<td></td>";
									reportData8 += "<td style='text-align:right'>Total"+defaultBank+" FD</td>"; 
									reportData8 += "<td style='text-align:right'>"+bankSum+"</td>"; 
									reportData8 += "</tr>";
									bankSum=0;
								}
							}
							defaultBankId = v.bank_id;
							defaultBank = v.bank_eng;
							totalSum = +totalSum + +v.amount;
							bankSum = +bankSum + + v.amount;
							reportData8 += "<tr>";
							reportData8 += "<td style='width:30px'>"+j+"</td>";
							reportData8 += "<td>"+v.bank_eng+" FD</td>";
							reportData8 += "<td>"+v.account_no+"</td>"; 
							reportData8 += "<td style='text-align:right'>"+parseFloat(v.amount).toFixed(2)+"</td>"; 
							reportData8 += "</tr>";
						}
                    });
                    reportData8 += "<tr>";
                    reportData8 += "<td  style='text-align:right'></td>";
                    reportData8 += "<td  style='text-align:right'></td>";
                    reportData8 += "<td style='text-align:right'>Total "+defaultBank+" FD</td>";
                    reportData8 += "<td style='text-align:right'>"+parseFloat(bankSum).toFixed(2)+"</td>";
                    reportData8 += "</tr>"; 
                    reportData8 += "<tr>";
                    reportData8 += "<td  style='text-align:right'></td>";
                    reportData8 += "<td style='text-align:right' colspan='2'>Total</td>";
                    reportData8 += "<td style='text-align:right' >"+parseFloat(totalSum).toFixed(2)+"</td>";
                    reportData8 += "</tr>"; 
                    reportData8 += "</table>"; 
                
                $("#report_body8").html(reportData8);
                      var defaultBankId = 0;
                      var defaultBank = "";
                      var totalSum = 0;
                      var bankSum = 0;
                      var reportData9 = "";
                      var j=0;
                    $.each(data.fdAccountsClosing, function (i, v) {
                        if(v.st == 1){
							j++;
							if(j != 0){
								if(defaultBankId != v.bank_id){ 
									reportData9 += "<tr>";
									reportData9 += "<td></td>";
									reportData9 += "<td></td>";
									reportData9 += "<td style='text-align:right'>  Total"+defaultBank+" FD</td>"; 
									reportData9 += "<td style='text-align:right'>"+bankSum+"</td>"; 
									reportData9 += "</tr>";
									bankSum=0;
								}
							}
                        
							defaultBankId = v.bank_id;
							defaultBank = v.bank_eng;
							i++;
							sum = v.amount;
							totalSum = +totalSum + +v.amount;
							bankSum = +bankSum + + v.amount;
							reportData9 += "<tr>";
							reportData9 += "<td style='width:30px'>"+j+"</td>";
							reportData9 += "<td>"+v.bank_eng+" FD</td>";
							reportData9 += "<td>"+v.account_no+"</td>"; 
							reportData9 += "<td style='text-align:right'>"+v.amount+"</td>"; 
							reportData9 += "</tr>";
						 //  $totalIncomeAmount = $bankWithdrawal + $totalIncome;
						 // var totalIncome = +data.bankWithdrawal + +totalIncome;
						 }
					});
                    reportData9 += "<tr>";
                    reportData9 += "<td  style='text-align:right'></td>";
                    reportData9 += "<td  style='text-align:right'></td>";
                    reportData9 += "<td style='text-align:right'>Total "+defaultBank+" FD</td>";
                    reportData9 += "<td style='text-align:right'>"+parseFloat(bankSum).toFixed(2)+"</td>";
                    reportData9 += "</tr>"; 
                    reportData9 += "<tr>";
                    reportData9 += "<td  style='text-align:right'></td>";
                    reportData9 += "<td style='text-align:right' colspan='2'>Total</td>";
                    reportData9 += "<td style='text-align:right;font-family:bold' >"+parseFloat(totalSum).toFixed(2)+"</td>";
                    reportData9 += "</tr>"; 
                $("#report_body9").html(reportData9);
                var reportData4 = "";
                if (data.accountReport.length === 0) {
                    reportData4 += '<tr><td colspan="20" style="text-align:center"><b>No Records Found</b></td></tr>';
                }else{
                    $(".btn_print_html").show();
                    $(".pdf_report").show();
                    $(".pdf_report1").show();
                    var j = 1;
                    var total = data.pettyCashClose;
                    reportData4 += "<tr>";
                    reportData4 += "<td style='width:30px'>1</td>";
                    reportData4 += "<td>Petty cash</td>";
                    reportData4 += "<td></td>";
                    reportData4 += "<td style='text-align:right'>"+data.pettyCashClose+"</td>";
                    reportData4 += "</tr>";
                    $.each(data.accountReport, function (i, v) {
                        j++;
                        total= +total + +v.closing;
                        reportData4 += "<tr>";
                        reportData4 += "<td style='width:30px'>"+j+"</td>";
                        reportData4 += "<td>"+v.bank_eng+"</td>";
                        reportData4 += "<td>"+v.account_no+"</td>";
                        reportData4 += "<td style='text-align:right'>"+v.closing+"</td>";
                        reportData4 += "</tr>";
                        
                    }); 
                    reportData4 += "<tr>";
                    reportData4 += "<td  style='text-align:right'></td>";
                    reportData4 += "<td style='text-align:right' colspan='2'>Total</td>";
                    reportData4 += "<td style='text-align:right' ><b>"+parseFloat((+total - +data.pettyCashClose)).toFixed(2)+"</b></td>";
                    reportData4 += "</tr>"; 

                    // $.each(data.fdAccountsClosing, function (i, v) {
                    //     j++;
                    //     total= +total + +v.amount;
                    //     reportData4 += "<tr>";
                    //     reportData4 += "<td style='width:30px'>"+j+"</td>";
                    //     reportData4 += "<td >"+v.bank_eng+" fFD</td>";
                    //     reportData4 += "<td style='text-align:right' >"+v.amount+"</td>";
                    //     reportData4 += "</tr>";
                        
                    // }); 
                    //alert(total);
                  
                }
                $("#report_body3").html(reportData4);
                var reportData5 = "";
                 var totalIncome = +cashIncome + +cardIncome + +moIncome + +chequeIncome + +ddIncome + +onlineIncome;
                 var CashIncomeWithoutBank = +cashIncome + + moIncome; 
                $.each(data.accountReport, function (i, v) {
                    reportData5 += "<tr>";
                    reportData5 += "<td><?php echo $this->lang->line('Withdrawal');?> ("+v.bank_eng+" => <?php echo $this->lang->line('temple');?>)</td>";
                    reportData5 += "<td style='text-align:right'>"+v.totalWithdrawal+"</td>";
                    reportData5 += "</tr>";
                    reportData5 += "<tr>";
                    reportData5 += "<td style='width:150px'></td>";
                    reportData5 += "<td><?php echo $this->lang->line('petty_cash_withdrawal')?>("+v.bank_eng+")   "+v.pettyCashWithdrawal+"</td>";
                    reportData5 += "</tr>";
                   
                }); 
                $.each(data.bankWithdrawalSplit, function (i, v) {
                    reportData5 += "<tr>";
                    if(v.type == "PETTY CASH WITHDRAWAL"){
                           var type = "petty_cash_withdrawal";
                         var type1= "<?php echo $this->lang->line('petty_cash_withdrawal')?>";
                    reportData5 += "<td>"+type1+"</td>";
                    reportData5 += "<td  style='text-align:right'>"+v.amount+"</td>";
                    }
                    reportData5 += "</tr>";

                });

                var totalIncomeAmount = +data.bankWithdrawal + +totalIncome + +data.total_fd_to_sb.amount;
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('total');?> <?php echo $this->lang->line('Withdrawal');?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+parseFloat(data.bankWithdrawal).toFixed(2)+"</td>";
                reportData5 += "</tr>";
				reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('total');?> FD <?php echo $this->lang->line('varavu');?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+parseFloat(data.total_fd_to_sb.amount).toFixed(2)+"</td>";
                reportData5 += "</tr>";
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('Income_By_Receipts'); ?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+parseFloat(totalIncome).toFixed(2)+"</td>";
                reportData5 += "</tr>";
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('total');?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+parseFloat(totalIncomeAmount).toFixed(2)+"</td>";
                reportData5 += "</tr>";
                
                $("#report_body4").html(reportData5);
                var reportData7 = "";
				reportData7 += '<h3 style="padding-bottom:0;"><?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('Deposit'); ?></h3>';
				reportData7 +='<div class="table-responsive" style="margin-top:15px">';                      
				reportData7 +='<table class="table table-bordered scrolling table-striped table-sm">';    
				reportData7 +='<thead>';
				reportData7 +='<tr class="bg-warning text-white text-center">';
				reportData7 +='<th style="text-align:left;"><?php echo $this->lang->line('sl'); ?></th>';
				reportData7 +='<th style="text-align:left;"><?php echo $this->lang->line('bank'); ?></th>';
				reportData7 +='<th style="text-align:right;">SB <?php echo $this->lang->line('Deposit'); ?></th>';
				reportData7 +='<th style="text-align:right;">FD <?php echo $this->lang->line('Deposit'); ?></th>';
				reportData7 +='</tr>';
				reportData7 +='</thead>';
				reportData7 +='<tbody>';
				var ik = 0;
                $.each(data.accountReport, function (i, v) {    
					ik++;         
                    reportData7 += "<tr>";
                    reportData7 += "<td>"+ik+"</td>";
                    reportData7 += "<td>"+v.bank_eng+"</td>";
                    reportData7 += "<td style='text-align:right'>"+v.totalDeposit+"</td>";
                    reportData7 += "<td style='text-align:right'>"+v.totalFDDeposit+"</td>";
                    reportData7 += "</tr>";
				});
				reportData7 += "<tr>";
				reportData7 += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total') ?> SB <?php echo $this->lang->line('Deposit'); ?></th>";
				reportData7 += "<th style='text-align:right'>"+data.bankDeposit+"</th>";
				reportData7 += "</tr>";
				reportData7 += "<tr>";
				reportData7 += "<th colspan='3' style='text-align:right'><?php echo $this->lang->line('total') ?> FD <?php echo $this->lang->line('Deposit'); ?></th>";
				reportData7 += "<th style='text-align:right'>"+data.total_sb_to_fd.amount+"</th>";
				reportData7 += "</tr>";
				reportData7 +='</tbody>';
				reportData7 +='</table>';
				reportData7 +='</div>';				
                $("#report_body50").html(reportData7);
				// reportData6 += "<tr>";
				// reportData6 += "<td><?php echo $this->lang->line('Deposit'); ?> (SB => FD)</td>";
				// reportData6 += "<td style='text-align:right'>"+data.total_sb_to_fd.amount+"</td>";
				// reportData6 += "</tr>";
				reportData6 = "";
                reportData6 += "<tr>";
                reportData6 += "<td><?php echo $this->lang->line('total') ?> <?php echo $this->lang->line('Deposit') ?></td>";
                reportData6 += "<td style='text-align:right;font-family:bold'>"+parseFloat(data.totalBankDeposit).toFixed(2)+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr>";
                reportData6 += "<td><?php echo  $this->lang->line('Expense_Vouchers');?></td>";
                reportData6 += "<td style='text-align:right;font-family:bold'>"+parseFloat(data.totalVoucherExpense).toFixed(2)+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr>";
                pettyCashSpent =  +cashExpense + + moExpense; 
                reportData6 += "<td><?php echo  $this->lang->line('petty_cash_spent');?></td>";
                reportData6 += "<td style='text-align:left;font-family:bold'>"+parseFloat(pettyCashSpent).toFixed(2)+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr colsspan='5'><td></td><td></td></tr>";
                reportData6 += "<tr>";
                reportData6 += "<td ><?php echo $this->lang->line('Deposit_Balance');?>-("+data.to_date+")</td>";
                // balanceToDeposit = totalIncome - data.bankDeposit;
               
                reportData6 += "<td style='text-align:left ;font-family:bold''>"+parseFloat(data.balanceToDeposit).toFixed(2)+"</td>";
                reportData6 += "</tr>";
                totalExpenseAmount = +data.totalBankDeposit + +data.totalVoucherExpense;
                    reportData6 += "<tr>";
                    reportData6 += "<th><?php echo $this->lang->line('total_amount'); ?></th>";
                    reportData6 += "<th style='text-align:right; font-family:bold'>"+parseFloat(totalExpenseAmount).toFixed(2)+"</th>";
                    reportData6 += "</tr>"; 
                $("#report_body5").html(reportData6);
            }
            
        });
    }

    $(".btn_print_html").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        $.ajax({
            url: '<?php echo base_url() ?>service/Reports_data/get_incomeexpensereport_print',
            type: 'POST',
            data:{from_date:from_date,to_date:to_date},
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
        get_reports();
    });
    $(".pdf_report").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_income_expense_pdf?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
    $(".pdf_report1").click(function(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        window.open('<?php echo base_url() ?>service/Reports_data/get_income_expense_pdf_new?from_date='+from_date+'&to_date='+to_date, '_blank');       
    });
</script>
