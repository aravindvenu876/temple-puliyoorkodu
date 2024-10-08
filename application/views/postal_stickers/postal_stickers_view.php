<div class="col-md-12 col-sm-12 col-12 listview">
    <div class="row ">
        <div class="col-md-12 col-sm-12 col-12">
            <h2 class="poojaList"><?php echo $temple['temple'] ?> Postal Sticker for <?php echo $from_date." to ". $to_date ?>
                <div class="printPosition" id="print_btn_div"></div>
            </h2>
        </div>
    </div>
    <?php 
        $i = 0;
        foreach($postal as $row){ 
            $i++;  
            $subscription = get_balance_subscriptions($row->main_id,$row->date);
            $lastDate = get_postal_last_date($row->main_id,$row->detail_id);
            if($lastDate != "0"){
                $malayalam = get_malayalam_alternate_calendar_details($lastDate);
                $english = get_english_alternate_calendar_details($lastDate);
                $vavu = "";
                if($malayalam['vavu'] == 17){
                    $vavu = "(".$english['malmonth']. " VAVU)";
                }
            }
            if($i%2 == 1){
                echo "<div class='row'>";
            }
            echo "<div class='col-5' style='margin:5px'>";
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
            echo "</div>";  
            if($i%2 == 0){
                echo "</div>";
                echo "<div class='clearfix'></div>";
                echo "<br><br>";
            }
        } 
    ?>
</div>
<hr>