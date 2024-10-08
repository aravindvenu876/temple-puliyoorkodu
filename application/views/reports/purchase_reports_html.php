<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title> Purchase Details  Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
               <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
               <h2 style="margin:0;font-size:22px;text-align: center;"> Purchase Report Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Bill Number</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Asset Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Type</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Rate</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Quantity</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total Amount</th>
                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    foreach($report as $row){
                        $i++;
                        $date_ex=$row->date;
                        $total=$total+$row->net;
                        $date=date("d-m-Y", strtotime($date_ex));
                        $rate=number_format((float)$row->total_rate, 2, '.', '');
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->purchase_bill_no</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->supplier_name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->asset_name_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->type</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$rate</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->quantity</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->net</td>";
                        echo "</tr>";
                    } 

                    ?> 
                     <tr>
                        <th colspan='8' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th colspan='8' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>                   
                </table>
            </div>
        </div>
    </body>
</html>