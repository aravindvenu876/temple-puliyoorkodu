<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Donation Reports Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
             <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
             <h2 style="margin:0;font-size:22px;text-align: center;"> Donation Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>

                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Donation category</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Devotee name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Phone Number</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Donation Amount</th>
                        
                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    foreach($report as $row){
                        $i++;
                        $date_ex=$row->receipt_date;
                        $total=$total+$row->receipt_amount;
                        $date=date("d-m-Y", strtotime($date_ex));
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->category_eng</td>";

                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->phone</td>";
                        echo "<td style='text-align:right;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->receipt_amount</td>";
                        echo "</tr>";
                    } 
                    ?>   
                     <tr>
                        <th colspan='5' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th colspan='1' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>                
                </table>
            </div>
        </div>
    </body>
</html>