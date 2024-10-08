<?php error_reporting(0); ?><!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja Wise Collection Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
               <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
               <h2 style="margin:0;font-size:22px;text-align: center;"> Pooja Wise Collection Reports</h2>

            </div>
            <hr style="border:.5px solid #dedede;">
          
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <!-- <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th> -->
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja Category</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Rate</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Quantity</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Amount(₹)</th>
                    </tr>
                    <?php 
                    $total_amount =0;
                    $i=0;
                    $last_id = "";
                    $last_category = "";
                    $total_category_amount = 0.00;
                    foreach($report as $row){   
                       $i++;
                       if($i == 1){
                          $last_id = $row->pooja_category_id;
                          $last_category = $row->category_alt;
                       }
                       if($last_id != $row->pooja_category_id){ 
                        echo "<tr>";
                        echo "<th colspan='5' style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total Amount</th>";
                        echo "<th style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".number_format((float)$total_category_amount, 2, '.', '')."</th>";
                        echo "<th colspan='5'></th></tr>";
                        $total_category_amount = "0.00";
                        }
                       if($row->count == "0"){
                      
                       $total=number_format((float)$row->amount, 2, '.', '');
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        // echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".date('d-m-Y',strtotime($row->receipt_date))."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->category</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'></td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'></td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$total</td>";
                        echo "</tr>";
                      
                    } else{ 
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->category_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_name_eng</td>";
                        $total=number_format((float)$row->amount, 2, '.', '');
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->rate</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->count</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$total</td>";
                        echo "</tr>";}
                    
                    $total_amount = $total_amount + $total;
                    $total_category_amount = $total_category_amount + $total;
                    $last_id = $row->pooja_category_id;
                    }?>
                    <tr>
                    <th colspan='5' style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total Amount</th>
                    <th style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'><?php echo  number_format((float)$total_category_amount, 2, '.', ''); ?></th></tr>
                    <tr><th colspan='5'  style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>Total</th>
                    <th style='text-align:right;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'><?php echo number_format((float)$total_amount, 2, '.', ''); ?></th></tr>
                    
                </table>
            </div>
        </div>
    </body>
</html>