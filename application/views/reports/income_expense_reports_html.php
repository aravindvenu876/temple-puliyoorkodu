<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
             <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
             <h2 style="margin:0;font-size:22px;text-align: center;">Income and Expense Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%;margin:10px 0;"></div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:50%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;">
                    <tr>
                        <th style="width:20px;text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Item</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Cash(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Card(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">MO(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Cheque(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">DD(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total(₹)</th>
                    </tr>
                    <?php 
                    $i=0;
                    $cashIncome = 0;
                    $cardIncome = 0;
                    $moIncome = 0;
                    $chequeIncome = 0;
                    $ddIncome = 0;
                    $ttIncome = 0;
                    foreach($incomeReport as $row){
                        $i++;
                        $cash=$row->cash*$row->count;
                        $cashIncome=$cashIncome+$cash;
                        $card=$row->card*$row->count;
                        $cardIncome=$cardIncome+$card;
                        $mo=$row->mo*$row->count;
                        $moIncome=$moIncome+$mo;
                        $cheque=$row->cheque*$row->count;
                        $chequeIncome=$chequeIncome+$cheque;
                        $dd=$row->dd*$row->count;
                        $ddIncome=$ddIncome+$dd;
                        $amount = $row->amount*$row->count;
                        $ttIncome = $ttIncome + $amount;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->category</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($cash, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($card, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($mo, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($cheque, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($dd, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($ttIncome, 2, '.', '')."</td>";
                        echo "</tr>";
                    } 
                  
                    $i++;
                    $cashIncome=$cashIncome+$receiptBookIncome;
                    $ttIncome = $ttIncome + $receiptBookIncome;
                    echo "<tr>";
                    echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Receipt Book Income</td>";
                    if($receiptBookIncome == null){
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    }else{
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($receiptBookIncome, 2, '.', '')."</td>";
                    }
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    if($receiptBookIncome == null){
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>0.00</td>";
                    }else{
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($receiptBookIncome, 2, '.', '')."</td>";
                    }
                    echo "<tr>";
                    echo "<th colspan='2' style='text-align:right'>Total Amount</th>";
                    echo "<th style='text-align:right'>".number_format($cashIncome, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($cardIncome, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($moIncome, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($chequeIncome, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($ddIncome, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($ttIncome, 2, '.', '')."</th>";
                    echo "<th colspan='2'></th></tr>";
                    ?>
                </table>
            </div>
            <div style="float:right;width:49%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="width:20px;text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Item</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Cash(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Card(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">MO(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Cheque(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">DD(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total(₹)</th>

                    </tr>
                    <?php 
                    $i =0;
                    $cashExpense = 0;
                    $cardExpense = 0;
                    $moExpense = 0;
                    $chequeExpense = 0;
                    $ddExpense = 0;
                    $ttExpense = 0;
                    foreach($expenseReport as $row){
                        $i++;
                        $cash=$row->cash*$row->count;
                        $cashExpense=$cashExpense+$cash;
                        $card=$row->card*$row->count;
                        $cardExpense=$cardExpense+$card;
                        $mo=$row->mo*$row->count;
                        $moExpense=$moExpense+$mo;
                        $cheque=$row->cheque*$row->count;
                        $chequeExpense=$chequeExpense+$cheque;
                        $dd=$row->dd*$row->count;
                        $ddExpense=$ddExpense+$dd;
                        $amount = $row->amount*$row->count;
                        $ttExpense = $ttExpense + $amount;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->category</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($cash, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($card, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($mo, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($cheque, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($dd, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($amount, 2, '.', '')."</td>";
                        echo "</tr>";
                    } 
                    echo "<tr>";
                    echo "<th colspan='2' style='text-align:right'>Total Amount</th>";
                    echo "<th style='text-align:right'>".number_format($cashExpense, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($cardExpense, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($moExpense, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($chequeExpense, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($ddExpense, 2, '.', '')."</th>";
                    echo "<th style='text-align:right'>".number_format($ttExpense, 2, '.', '')."</th>";
                    echo "<th colspan='2'></th></tr>";
                    ?>
                </table>
            </div>
        </div>

        <div style="width:1000px;margin:auto;float:none;">          
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%;margin:10px 0;">
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:50%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;">
                    <?php  $totalIncome = $cashIncome + $cardIncome + $moIncome + $chequeIncome + $ddIncome;
                    foreach($accountReport as $row){
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Withdrawal ($row->bank_eng => Temple)</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->totalWithdrawal</td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total Withdrawal</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($bankWithdrawal, 2, '.', '')."</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Income By Receipts</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$totalIncome</td>";
                    echo "</tr>";
                    ?>
                </table>
            </div>
            <div style="float:right;width:49%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <?php 
                    foreach($accountReport as $row){
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Deposit (Temple => $row->bank_eng)</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->totalDeposit</td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total Deposit</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($bankDeposit, 2, '.', '')."</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Expense From Vouchers</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$totalVoucherExpense</td>";
                    echo "</tr>";
                    echo "<tr colspan='5'><td></td><td></td></tr>";
                    echo "<tr>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Balance To Deposit As on ".$to_date."</td>";
                    $balanceToDeposit = $totalReceiptIncome - $bankDeposit;
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>".number_format($balanceToDeposit, 2, '.', '')."</td>";
                    echo "</tr>";
                    ?>
                </table>
            </div>
        </div>

        <div style="width:1000px;margin:auto;float:none;">          
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%;margin:10px 0;">
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:50%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;">
                    <tr>
                        <th colspan="3" style="width:20px;text-align:centre;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Opening</th>
                    </tr>
                    <?php 
                   
                    $total=$pettyCashOpen;
                     $i = 1;
                   
                  //  $total = $accountReport->pettyCashOpen;
                    echo "<tr>";
                    echo "<td style='width:30px'>1</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Petty cash</td>";
                    echo "<td  style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$pettyCashOpen</td>";
                    echo "</tr>";
                    $sum=0;
                   
                    foreach($accountReport as $row){
                        $i++;
                        $sum=$row->opening;
                        $total=$total+$sum;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->opening</td>";
                        echo "</tr>";
                    } 
                    foreach($fdAccountsOpening as $row){
                        $i++;
                        $sum=$row->amount;
                        $total=$total+$sum;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank_eng FD</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->amount</td>";
                        echo "</tr>";
                    } 
                   // totalIncome = +data.bankWithdrawal + +totalIncome;
                    $totalIncomeAmount = $bankWithdrawal + $totalIncome;
                    echo "<tr>";
                    echo "<th colspan='2' style='text-align:right'>Total Amount</th>";
                    echo "<th style='text-align:right'>".number_format($totalIncomeAmount, 2, '.', '')."</th>";
                    echo "<th colspan='2'></th></tr>";
                    ?>
                </table>
            </div>
            <div style="float:right;width:49%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                    <th colspan="3" style="width:20px;text-align:centre;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Opening</th>


                    </tr>
                    <?php 
                    $i =1;
                    $total=$pettyCashClose;
                    echo "<tr>";
                    echo "<td style='width:30px'>1</td>";
                    echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Petty cash</td>";
                    echo "<td  style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$pettyCashClose</td>";
                    echo "</tr>";
                   
                    foreach($accountReport as $row){
                        $i++;
                        $sum=$row->closing;
                        $total=$total+$sum;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->closing</td>";
                        echo "</tr>";
                    }  
                    foreach($fdAccountsClosing as $row){
                        $i++;
                        $sum=$row->amount;
                        $total=$total+$sum;
                        echo "<tr>";
                        echo "<td style='width:20px;text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank_eng FD</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->amount</td>";
                        echo "</tr>";
                    } 
                  
                    $totalExpenseAmount = $bankDeposit + $totalVoucherExpense;
                    echo "<tr>";
                    echo "<th colspan='2' style='text-align:right'>Total Amount</th>";
                    echo "<th style='text-align:right'>".number_format($totalExpenseAmount, 2, '.', '')."</th>";
                    echo "<th colspan='2'></th></tr>";
                    ?>
                </table>
            </div>
        </div>

    </body>
</html>