<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pending Pooja Report</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
              <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple ?></h3>
               <h2 style="margin:0;font-size:22px;text-align: center;">Pending Pooja Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <!-- <div style="float:left;width:100%;margin:10px 0;">
                <div style="float:left;width:50%;">
                </div>
            </div>
            <hr style="border:.5px solid #dedede;"> -->
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Date</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja Type</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Receipt No</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;">Phone</th>
                    </tr>
                    <?php 
                    $i =0;
                    $phonech=0;
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
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>".date('d-m-Y',strtotime($row->date))."</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->receipt_status</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->receipt_no</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;'>$phone</td>";
                        echo "</tr>";
                    } 
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>