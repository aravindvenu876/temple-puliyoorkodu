<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Asset Issue & Return report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
            <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
            <h2 style="margin:0;font-size:22px;text-align: center;">Asset Issue & Return From <?php echo $from_date ?> To <?php echo $to_date ?></h2>
            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Asset Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Status</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Quantity</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Returned Quantity</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Damaged quantity</th>

                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    foreach($report as $row){
                        $i++;
                        $date_ex=$row->date;
                        $date=date("d-m-Y", strtotime($date_ex));
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->asset_eng</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->asset_status</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->quantity</td>";

                        if($row->returned_quantity!=null){
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->returned_quantity</td>";

                        }
                        else{
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'></td>";
                        }
                        if($row->scrapped_quantity!=null){
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->scrapped_quantity</td>";
                        }
                        else{
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'></td>";
                        }

                      
                        echo "</tr>";
                    } 
                    ?>                   
                </table>
            </div>
        </div>
    </body>
</html>