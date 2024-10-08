<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Postal Stickers</title>
    </head>
    <body style="float:left;width:100%;" onload="window.print()">
        <div style="width:100%">
            <br><br>
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
					} ?>
					<div style='width:40%;height:150px;padding:20px;float:left;margin-left:3%;margin-right:0px;border:1px solid black'>
                    <?php echo "<b>TO,</b>";
                    echo "<br>";
                    echo "<span style='text-transform: uppercase;'><b>$row->address</b></span>";
                    echo "<br>";
                    echo "<span><b>Your Subscription Balance is $subscription</b></span>";
                    if($lastDate != "0"){
                        echo "<br>";
                        echo "<span style='text-transform: uppercase;'><b>***Next ".$malayalam['gregmonth']." ".$malayalam['gregday']." ".date('l',strtotime($row->date))." ".$vavu."***</b></span>";
                    }
                    echo "</div>";
				} 
				echo "</div>";
            ?>
        </div>
    </body>
</html>
