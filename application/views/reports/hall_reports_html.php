<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Hall Booking Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
             <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
             <h2 style="margin:0;font-size:22px;text-align: center;"> Hall Booking Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Booked On</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">From Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">To Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Hall Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">phone</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Advance Paid</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total Paid</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total Rent</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Payment status</th>
                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    $totalPaid = 0;
                    foreach($report as $row){
                        $i++;
                        $date_ex=$row->date;
                        $date=date("d-m-Y", strtotime($date_ex));
                        $date_ex1=$row->from_date;
                        $fdate=date("d-m-Y", strtotime($date_ex1));
                        $date_ex2=$row->to_date;
                        $tdate=date("d-m-Y", strtotime($date_ex2));
                        $total_amt = $row->advance_paid +$row->balance_paid + $row->balance_to_be_paid;
                        $paid_amt = $row->advance_paid + $row->balance_paid;
                        $total_amt1=number_format((float)$total_amt, 2, '.', '');
                        $paid_amt1=number_format((float)$paid_amt, 2, '.', '');
                        $total = $total + $total_amt;
                        $totalPaid = $totalPaid + $paid_amt;
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$fdate</td>";

                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$tdate</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->hall_name_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->devotee_name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->phone</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->advance_paid</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$paid_amt1</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$total_amt1</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->payment_status</td>";
                        echo "</tr>";
                    } 
                    ?> 
                     <tr>
                        <th colspan='9' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$totalPaid, 2, '.', ''); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th colspan='2' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>                  
                </table>
            </div>
        </div>
    </body>
</html>