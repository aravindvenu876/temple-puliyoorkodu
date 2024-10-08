<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Salary Process Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
               <h3 style="margin:0;font-size:22px;text-align: center;"><?php  $nmonth = date('M',strtotime($month)); echo $temple ?></h3>
               <h2 style="margin:0;font-size:22px;text-align: center;">Salary Process Excel Report From 
                <?php  
                     if($month == ""){
                        echo date('F');
                     }else{
                        echo date('F',strtotime("01-".$month."-1970"));
                     }
                   if($year == ""){
                        echo date('Y');
                     }else{
                        echo " ".$year;
                     }?>
                </h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Staff</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Salary Payable(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Bank</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Account Number</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">IFSC Code</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Process Date</th>
                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    foreach($report as $row){
                        $i++;
                        $total=$total+$row->payable_salary;
                        $date=date("d-m-Y", strtotime($row->date));
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->payable_salary</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->account_no</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->ifsc_code</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                        echo "</tr>";
                    } 
                    ?>  
                     <tr>
                        <th colspan='2' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th colspan='4' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>                 
                </table>
            </div>
        </div>
    </body>
</html>