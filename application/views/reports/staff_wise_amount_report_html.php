<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Counter Closing Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
                <h2 style="margin:0;font-size:22px;text-align: center;"> Counter Closing Report From <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">User</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Counter</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Session</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Closing Amount(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Actual Amount(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Excess Amount(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Shortage Amount(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Reason For Difference</th>
                    </tr>
                    <?php 
                    $i =0;
                    $totalExcessAmount = 0.00;
                    $totalShortageAmount = 0.00;
                    $excessAmount = "0.00";
                    $shortageAmount = "0.00";
                    $difference = "0.00";
                    foreach($report as $row){
                        $i++;
                        $session_date = date('d-m-Y',strtotime($row->session_date));
                        $excessAmount = "0.00";
                        $shortageAmount = "0.00";
                        if($row->actual_closing_amount < $row->closing_amount){
                            $shortageAmount = $row->closing_amount - $row->actual_closing_amount;
                            $totalShortageAmount = $totalShortageAmount + $shortageAmount;
                        }else{
                            $excessAmount = $row->actual_closing_amount - $row->closing_amount;
                            $totalExcessAmount = $totalExcessAmount + $excessAmount;
                        }
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$session_date</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->counter_no</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->id</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>$row->closing_amount</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>$row->actual_closing_amount</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>".number_format($excessAmount, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'>".number_format($shortageAmount, 2, '.', '')."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->description</td>";
                        echo "</tr>";
                    }  
                    if($totalExcessAmount < $totalShortageAmount){
                        $difference = $totalShortageAmount - $totalExcessAmount;
                        $difference = "-".$difference;
                    }else{
                        $difference = $totalExcessAmount - $totalShortageAmount;
                        $difference = "+".$difference;
                    }
                    ?>     
                    <tr>
                        <td colspan='7' style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total</td>
                        <td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'><?php echo number_format($totalExcessAmount, 2, '.', '') ?></td>
                        <td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'><?php echo number_format($totalShortageAmount, 2, '.', '') ?></td>
                        <td colspan='5'></td> 
                    </tr><tr>
                        <td colspan='7' style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Difference</td>
                        <td colspan='2' style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;;text-align:right'><?php echo number_format($difference, 2, '.', '') ?></td>
                        <td colspan='5'></td> 
                    </tr>              
                </table>
            </div>
        </div>
    </body>
</html>