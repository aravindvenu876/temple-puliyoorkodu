<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Bank Balance Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
            <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
            <h2 style="margin:0;font-size:22px;text-align: center;"> Bank Balance From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Bank</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Account</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Open Balance(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Closing Balance(₹)</th>
                    </tr>
                    <?php 
                    $i =0;
                    $totalOpeningAmount = 0.00;
                    $totalClosingAmount = 0.00;
                    foreach($accountReport as $row){
                        $i++;
                        $totalOpeningAmount = $totalOpeningAmount + $row->opening;
                        $totalClosingAmount = $totalClosingAmount + $row->closing;
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->bank_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->account_no</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>$row->opening</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>$row->closing</td>";
                        echo "</tr>";
                    } 
                    ?>     
                    <tr>
                        <td colspan='3' style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total</td>
                        <td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'><?php echo number_format($totalOpeningAmount, 2, '.', '') ?></td>
                        <td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'><?php echo number_format($totalClosingAmount, 2, '.', '') ?></td>
                    </tr>              
                </table>
            </div>
        </div>
    </body>
</html>