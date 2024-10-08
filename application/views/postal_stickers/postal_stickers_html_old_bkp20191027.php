<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Nivedya List</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;"><?php echo $temple['temple'] ?> Postal Sticker for <?php echo $from_date." to ". $to_date ?></h3>
            </div>
            <hr style="border:.5px solid #dedede;">
            <br><br>
            <table width="100%" border="0" cellpadding="8">
            <?php 
                $i = 0;
                foreach($postal as $row){ 
                    $i++;  
                    $subscription = get_balance_subscriptions($row->main_id,$row->date);
                    $lastDate = get_postal_last_date($row->main_id,$row->detail_id);
                    if($lastDate != "0"){
                        $malayalam = get_malayalam_alternate_calendar_details($row->date);
                        $english = get_english_alternate_calendar_details($row->date);
                        $vavu = "";
                        if($malayalam['vavu'] == 17){
                            $vavu = "(".$english['malmonth']. " VAVU)";
                        }
                    }
                    if($i%2 == 1){
                        echo "<tr>";
                    }
                    echo "<td>";
                    echo "<b>TO,</b>";
                    echo "<br>";
                    // echo "<span style='text-transform: uppercase;'><b>$row->name($row->receipt_no)</b></span>";
                    // echo "<br>";
                    echo "<span style='text-transform: uppercase;'><b>$row->address</b></span>";
                    echo "<br>";
                    echo "<span><b>Your Subscription Balance is $subscription</b></span>";
                    if($lastDate != "0"){
                        echo "<br>";
                        echo "<span style='text-transform: uppercase;'><b>***Next ".$malayalam['gregmonth']." ".$malayalam['gregday']." ".date('l',strtotime($row->date))." ".$vavu."***</b></span>";
                    }
                    echo "</td>";  
                    if($i%2 == 0){
                        echo "</tr>";
                    }
                } 
            ?>
            </table>
        </div>
    </body>
</html>