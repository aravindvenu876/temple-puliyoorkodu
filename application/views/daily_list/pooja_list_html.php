<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Pooja List</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple['temple'] ?> Daily Pooja List for <?php echo $date ?></h3>
            </div>
            <?php 
                $masterArray = array(); 
                $unitArray = array(); 
                $itemArray = array();
            ?>
            <hr style="border:.5px solid #dedede;">
            <h4 style="margin:0;font-size:18px;text-align: center;">Daily Pooja List</h4>
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;">Pooja</th>
                    </tr>
                    <?php 
                    $i =0;
                    foreach($daily_pooja_list as $row){
                        $i++;
                        $masterArray[$row->pooja_name] = 1;
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;'>$row->pooja_name</td>";
                        echo "</tr>";
                    } 
                    ?>
                </table>
            </div>
            <hr style="border:.5px solid #dedede;">
            <h4 style="margin:0;font-size:18px;text-align: center;">Booked Pooja List</h4>
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Pooja</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Devotee Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;">Star</th>
                    </tr>
                    <?php 
                    $i =0;
                    $yesterDate = date('Y-m-d',strtotime($date) - (24*3600));
                    $defaultTime = date('Gi',strtotime(DEFAULT_DAILY_LIST_TIME));
                    foreach($booked_pooja_list as $row){
                        if($row->receipt_date == $yesterDate){
                            if($defaultTime >= date('Gi',strtotime($row->receipt_time))){
                                $i++;
                                if(isset($masterArray[$row->pooja_name])){
                                    $masterArray[$row->pooja_name] = $masterArray[$row->pooja_name] + 1;
                                }else{
                                    $masterArray[$row->pooja_name] = 1;
                                }
                                echo "<tr>";
                                echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                                echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_name</td>";
                                echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                                echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;'>$row->star</td>";
                                echo "</tr>";
                            }
                        }else if($row->receipt_date < $yesterDate){
                            $i++;
                            if(isset($masterArray[$row->pooja_name])){
                                $masterArray[$row->pooja_name] = $masterArray[$row->pooja_name] + 1;
                            }else{
                                $masterArray[$row->pooja_name] = 1;
                            }
                            echo "<tr>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->pooja_name</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->name</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;'>$row->star</td>";
                            echo "</tr>";
                        }
                    } 
                    ?>
                </table>
            </div>
            <hr style="border:.5px solid #dedede;">
            <h4 style="margin:0;font-size:18px;text-align: center;">Total Pooja List</h4>
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Nivedyam</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;">Quantity</th>
                    </tr>
                    <?php 
                    $i =0;
                    ksort($masterArray);
                    foreach($masterArray as $row => $value){
                        $i++;
                        echo "<tr>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row</td>";
                        echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;'>$value</td>";
                        echo "</tr>";
                    } 
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>