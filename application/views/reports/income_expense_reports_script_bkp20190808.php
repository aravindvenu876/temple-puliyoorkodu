
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
                var reportData1 = "";
                var pooja="";
				$(".btn_print_html").show();
				$(".pdf_report").show();
				$(".pdf_report1").show();
				var j = 0;
				$.each(data.incomeReport, function (i, v) {
					j++;
					recash = 0;
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
							if(v.category == "Mattuvarumanam"){
								if(v1.category == "Mattu Varumanam"){
									recash= +recash+ + v1.amount;
									indexStoreArray.push(i);
								}
							}
						}
					});
					var cash = v.cash*v.count;
					var cash1 = +cash + +recash;
					cashIncome= +cashIncome + +cash1;
					var card = v.card*v.count;
					cardIncome= +cardIncome + +card;
					var mo = v.mo*v.count;
					moIncome= +moIncome + +mo;
					var cheque = v.cheque*v.count;
					chequeIncome= +chequeIncome + +cheque;
					var dd = v.dd*v.count;
					ddIncome= +ddIncome + +dd;
					var amount = v.amount*v.count;
					var amount1 = +amount+ + recash;
					ttIncome = +ttIncome + +amount1;
					var recash=0;
					reportData1 += "<tr>";
					reportData1 += "<td style='width:30px'>"+j+"</td>";
					reportData1 += "<td>"+v.category+"</td>";                     
					reportData1 += "<td style='text-align:right'>"+parseFloat(cash1).toFixed(2)+"</td>"; 
					reportData1 += "<td style='text-align:right'>"+parseFloat(card).toFixed(2)+"</td>"; 
					reportData1 += "<td style='text-align:right'>"+parseFloat(mo).toFixed(2)+"</td>"; 
					reportData1 += "<td style='text-align:right'>"+parseFloat(cheque).toFixed(2)+"</td>"; 
					reportData1 += "<td style='text-align:right'>"+parseFloat(dd).toFixed(2)+"</td>"; 
					reportData1 += "<td style='text-align:right'>"+parseFloat(amount1).toFixed(2)+"</td>"; 
					reportData1 += "</tr>";  
				});
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
				reportData1 += "<th style='text-align:right'>"+parseFloat(ttIncome).toFixed(2)+"</th>";
				reportData1 += "</tr>";              
                $("#report_body").html(reportData1);
                var reportData2 = "";
								var cashExpense = 0;
								var cardExpense = 0;
								var moExpense = 0;
								var chequeExpense = 0;
								var ddExpense = 0;
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
                    reportData2 += "<th style='text-align:right'>"+parseFloat(ttExpense).toFixed(2)+"</th>";
                    reportData2 += "</tr>";
                }
                $("#report_body1").html(reportData2);
                totalIncome = cashIncome + cardIncome + moIncome + chequeIncome + ddIncome;
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
                        total=total+sum;
                        total= +total + +sum;
                        reportData3 += "<tr>";
                        reportData3 += "<td style='width:30px'>"+j+"</td>";
                        reportData3 += "<td>"+v.bank_eng+"</td>";
                        reportData3 += "<td>"+v.account_no+"</td>";
                        reportData3 += "<td style='text-align:right'>"+v.opening+"</td>";
                        reportData3 += "</tr>";
                    }); 
                  //  $totalIncomeAmount = $bankWithdrawal + $totalIncome;
                }
                $("#report_body2").html(reportData3);
                    var defaultBankId = 0;
                    var defaultBank = "";
                    var totalSum = 0;
                    var bankSum = 0;
                    var reportData8 = "";
                    var i=0;
                    $.each(data.fdAccountsOpening, function (i, v) {
                        if(i != 0){
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
                        i++;
                        totalSum = +totalSum + +v.amount;
                        bankSum = +bankSum + + v.amount;
                        reportData8 += "<tr>";
                        reportData8 += "<td style='width:30px'>"+i+"</td>";
                        reportData8 += "<td>"+v.bank_eng+" FD</td>";
                        reportData8 += "<td>"+v.account_no+"</td>"; 
                        reportData8 += "<td style='text-align:right'>"+parseFloat(v.amount).toFixed(2)+"</td>"; 
                        reportData8 += "</tr>";
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
                      var i=0;
                    $.each(data.fdAccountsClosing, function (i, v) {
                        j++;
                    
                        if(i != 0){
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
                        reportData9 += "<td style='width:30px'>"+i+"</td>";
                        reportData9 += "<td>"+v.bank_eng+" FD</td>";
                        reportData9 += "<td>"+v.account_no+"</td>"; 
                        reportData9 += "<td style='text-align:right'>"+v.amount+"</td>"; 
                        reportData9 += "</tr>";
                     //  $totalIncomeAmount = $bankWithdrawal + $totalIncome;
                     // var totalIncome = +data.bankWithdrawal + +totalIncome;
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
                 var totalIncome = +cashIncome + +cardIncome + +moIncome + +chequeIncome + +ddIncome;
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

                var totalIncomeAmount = +data.bankWithdrawal + + totalIncome;
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('total');?> <?php echo $this->lang->line('Withdrawal');?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+data.bankWithdrawal+"</td>";
                reportData5 += "</tr>";
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('Income_By_Receipts'); ?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+totalIncome+"</td>";
                reportData5 += "</tr>";
                reportData5 += "<tr>";
                reportData5 += "<td><?php echo $this->lang->line('total');?></td>";
                reportData5 += "<td style='text-align:right;font-family:bold'>"+totalIncomeAmount+"</td>";
                reportData5 += "</tr>";
                
                $("#report_body4").html(reportData5);
                var reportData6 = "";
              
                $.each(data.accountReport, function (i, v) {
                  
                    reportData6 += "<tr>";
                    reportData6 += "<td><?php echo $this->lang->line('Deposit'); ?> (<?php echo $this->lang->line('temple'); ?> => "+v.bank_eng+")</td>";
                    reportData6 += "<td style='text-align:right'>"+v.totalDeposit+"</td>";
                    reportData6 += "</tr>";
                });
                reportData6 += "<tr>";
                reportData6 += "<td><?php echo $this->lang->line('total') ?> <?php echo $this->lang->line('Deposit') ?></td>";
                reportData6 += "<td style='text-align:right;font-family:bold'>"+data.bankDeposit+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr>";
                reportData6 += "<td><?php echo  $this->lang->line('Expense_Vouchers');?></td>";
                reportData6 += "<td style='text-align:right;font-family:bold'>"+data.totalVoucherExpense+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr>";
                pettyCashSpent =  +cashExpense + + moExpense; 
                reportData6 += "<td><?php echo  $this->lang->line('petty_cash_spent');?></td>";
                reportData6 += "<td style='text-align:left;font-family:bold'>"+pettyCashSpent+"</td>";
                reportData6 += "</tr>";
                reportData6 += "<tr colsspan='5'><td></td><td></td></tr>";
                reportData6 += "<tr>";
                reportData6 += "<td ><?php echo $this->lang->line('Deposit_Balance');?>-("+data.to_date+")</td>";
                balanceToDeposit = totalIncome - data.bankDeposit;
               
                reportData6 += "<td style='text-align:left ;font-family:bold''>"+parseFloat(balanceToDeposit).toFixed(2)+"</td>";
                reportData6 += "</tr>";
                totalExpenseAmount = +data.bankDeposit + +data.totalVoucherExpense;
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
