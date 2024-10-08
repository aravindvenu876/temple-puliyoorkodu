<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja Wise Collection Comparison Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
               <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
               <h2 style="margin:0;font-size:22px;text-align: center;">Pooja Wise Collection Comparison Reports For <?php echo $current.", ".$previous.", ".$prevYear."," ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja Code</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $current ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $previous ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"><?php echo $prevYear ?></th>
                    </tr>
                    <?php 
                    $i =0;$total=0;$total1=0;$total2=0;
                    foreach($poojas as $row){
                        $i++;
                        $rate1 = "0.00";
                        if(!empty($reports1)){
                            foreach($reports1 as $row1){
                                if($row->id == $row1->pooja_master_id){
                                    $rate1 = $row1->total_amount;
                                }
                            }
                        }
                        $rate2 = "0.00";
                        if(!empty($reports2)){
                            foreach($reports2 as $row2){
                                if($row->id == $row2->pooja_master_id){
                                    $rate2 = $row2->total_amount;
                                }
                            }
                        }
                        $rate3 = "0.00";
                        if(!empty($reports3)){
                            foreach($reports3 as $row3){
                                if($row->id == $row3->pooja_master_id){
                                    $rate3 = $row3->total_amount;
                                }
                            }
                        }
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->id</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$rate1</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$rate2</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;text-align:right'>$rate3</td>";
                        echo "</tr>";
                        $total=$total + $rate1;
                        $total1=$total1 + $rate2;
                        $total2=$total2 + $rate3;
                    } 
                    ?>
                      <tr>
                        <th colspan='3' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total1, 2, '.', ''); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total2, 2, '.', ''); ?></th>

                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>