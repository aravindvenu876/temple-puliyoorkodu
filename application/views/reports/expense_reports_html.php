<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Expense Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
            <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
            <h2 style="margin:0;font-size:22px;text-align: center;"> Expense Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Voucher Number</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Expense Type</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Transaction Type</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Expense Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Mode Of Tranfer</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Description</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Name & Address</th>
                    </tr>
                    <?php 
                        $i =0;
                        $total = 0;
                        foreach($report as $row){
                            $i++;
                            $total = $total + $row->amount;
                            $date_ex=$row->date;
                            $date=date("d-m-Y", strtotime($date_ex));
                            if($row->voucher_id=="0"){
                                $reportData= "No Voucher Generated";
                            }else if($row->voucher_id=="-1"){
                                $reportData = "No Voucher";
                            }else{
                                $reportData = $row->voucher_id;
                            }
                            echo "<tr>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$reportData</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->head_eng</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->transaction_type</td>";
                            echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->amount</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->payment_type</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->description</td>";     
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name,$row->address</td>";                     
                            echo "</tr>";
                        } 
                        echo "<tr>";
                        echo "<th colspan='5' style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total</td>";
                        echo "<th style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".number_format($total, 2, '.', '')."</td>";
                        echo "<th colspan='3' style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'></td>";
                        echo "</tr>";
                    ?>                   
                </table>
            </div>
        </div>
    </body>
</html>