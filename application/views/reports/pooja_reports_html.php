<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:800px;margin:auto;float:none;padding:15px">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
                <h2 style="margin:0;font-size:22px;text-align: center;"><?php echo $this->lang->line('pooja_reports'); ?>   <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja'); ?></th>
                        <th style="text-align:right;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja_rpt_rate'); ?></th>
                        <th style="text-align:right;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja_rpt_count'); ?></th>
                        <th style="text-align:right;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja_rpt_amount'); ?></th>

                    </tr>
                    <?php 
                    $i = 1;
                    $total = 0;
                    foreach($report as $row){
                        $total = $total + $row->amount; 
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".$i++."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->rate</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->quantity</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->amount</td>";
                        echo "</tr>";
                    } 
                    ?>
                     <tr>
                        <th colspan='4' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>