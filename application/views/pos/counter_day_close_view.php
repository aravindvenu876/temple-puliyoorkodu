<div class="col-md-12 col-sm-12 col-12 listview">
    <div class="row ">
        <div class="col-md-12 col-sm-12 col-12">
            <h2 class="poojaList"><?php echo $temple['temple'] ?> <?php echo $this->lang->line('day_wise_counter_closing_for'); ?> <?php echo $date ?></h2>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white">
                <th colspan='2'>Slno</th>
                <th>Counter</th>
                <th>Session</th>
                <th  style='text-align:right'>Closing Amount</th>
                <th  style='text-align:right'>Actual Amount</th>
                <th>Status</th>
            </tr>
            <?php 
                $i =0;
                $finalClosingTotal = 0;
                $finalActualTotal = 0;
                foreach($counters as $row){
                    $i++;
                    $counterSessions = get_all_counter_sessions($row->id,$date);
                    $closingTotal = 0;
                    $actualTotal = 0;
                    $j = 0;
                    $sessionOut = "";
                    if(!empty($counterSessions)){
                        foreach($counterSessions as $val){
                            $j++;
                            $finalClosingTotal = $finalClosingTotal + $val->closing_amount;
                            $closingTotal = $closingTotal + $val->closing_amount;
                            $finalActualTotal = $finalActualTotal + $val->actual_closing_amount;
                            $actualTotal = $actualTotal + $val->actual_closing_amount;
                            if($val->actual_closing_amount == 0){
                                $mainStatus = "Counter balance not confirmed";
                            }else{
                                $mainStatus = "Exact Amount";
                                if($val->closing_amount < $val->actual_closing_amount){
                                    $differenceMainAmount  = $val->actual_closing_amount - $val->closing_amount;
                                    $mainStatus = "Excess of ₹".number_format($differenceMainAmount,2);
                                }else if($val->closing_amount > $val->actual_closing_amount){
                                    $differenceMainAmount  = $val->closing_amount - $val->actual_closing_amount;
                                    $mainStatus = "Shortage of ₹".number_format($differenceMainAmount,2);
                                }
                            }
                            $sessionOut .= "<tr><td></td><td></td><td>Session : ".$val->id."</td>";
                            $sessionOut .= "<td>".date('h:i A',strtotime($val->session_start_time))." - ".date('h:i A',strtotime($val->session_close_time))."</td>";
                            $sessionOut .= "<th style='text-align:right'>₹".number_format($val->closing_amount,2)."</th>";
                            $sessionOut .= "<th style='text-align:right'>₹".number_format($val->actual_closing_amount,2)."</th>";
                            $sessionOut .= "<th>$mainStatus</th></tr>";
                        }
                    }
                    if($closingTotal == 0){
                        $mainStatus = "Counter balance not confirmed";
                    }else{
                        $mainStatus = "Exact Amount";
                        if($closingTotal < $actualTotal){
                            $differenceMainAmount  = $actualTotal - $closingTotal;
                            $mainStatus = "Excess of ₹".number_format($differenceMainAmount,2);
                        }else if($closingTotal > $actualTotal){
                            $differenceMainAmount  = $closingTotal - $actualTotal;
                            $mainStatus = "Shortage of ₹".number_format($differenceMainAmount,2);
                        }
                    }
                    echo "<tr><td>$i</td><td></td><td>$row->counter_no</td><td></td>";
                    echo "<th style='text-align:right'>₹".number_format($closingTotal,2)."</th>";
                    echo "<th style='text-align:right'>₹".number_format($actualTotal,2)."</th>";
                    echo "<th>$mainStatus</th></tr>";
                    echo $sessionOut;
                }
                if($finalClosingTotal == 0){
                    $mainStatus = "Counter balance not confirmed";
                }else{
                    $mainStatus = "Exact Amount";
                    if($finalClosingTotal < $finalActualTotal){
                        $differenceMainAmount  = $finalActualTotal - $finalClosingTotal;
                        $mainStatus = "Excess of ₹".number_format($differenceMainAmount,2);
                    }else if($finalClosingTotal > $finalActualTotal){
                        $differenceMainAmount  = $finalClosingTotal - $finalActualTotal;
                        $mainStatus = "Shortage of ₹".number_format($differenceMainAmount,2);
                    }
                }
                echo "<tr><td colspan='4'>Total</td>";
                echo "<th style='text-align:right'>₹".number_format($finalClosingTotal,2)."</th>";
                echo "<th style='text-align:right'>₹".number_format($finalActualTotal,2)."</th>";
                echo "<th>$mainStatus</th></tr>";
            ?>
        </table>
    </div>
</div>
<hr>