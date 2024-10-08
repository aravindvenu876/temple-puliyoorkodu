<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
                <h2 style="margin:0;font-size:22px;text-align: center;"><?php echo $this->lang->line('pooja_reports'); ?>   <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%;margin:10px 0;">
                <div style="float:left;width:50%;">
                    <label style="line-height:1.3;float:left;width:100%;"><b><?php echo $this->lang->line('counter'); ?>:</b><?php echo $counter ?></label>
                    <label style="line-height:1.3;float:left;width:100%;"><b><?php echo $this->lang->line('user'); ?>:</b><?php echo $user ?></label>
                </div>
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('date'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('star'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('pooja_type'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('receipt_no'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('amount'); ?>(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('name'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('phone'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('user'); ?></th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;"><?php echo $this->lang->line('counter'); ?></th>

                    </tr>
                    <?php 
                    $i =0;
                    $phonech="";
                    $total=0;
                    foreach($report as $row){
                        $i++;
                        if($row->phone==null){
                            $phone = "";
                        }
                        else{
                            $str = $row->phone;
                           $phonech = strlen($str);
                        }
                        if($phonech<=10){
                            $phone =$row->phone;
                        }
                        else{
                            $phone = "";
                        }
                        $total=$total+$row->amount;       
                        
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".date('d-m-Y',strtotime($row->receipt_date))."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->star</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_type</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->receipt_no</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->amount</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$phone</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->user_name</td>";
                        echo "<td style='text-align:center;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pos_counter_id</td>";
                        echo "</tr>";
                    } 
                    ?>
                     <tr>
                        <th colspan='6' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                        <th colspan='7' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;"></th>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>