<?php require_once(APPPATH.'/language/english/site_lang.php');
require_once(APPPATH.'/language/malayalam/site_lang.php');?>
<hr>
<div class="col-md-12 col-sm-12 col-12 listview">
    <div class="row ">
        <div class="col-md-12 col-sm-12 col-12">
            <h2 class="poojaList"><?php echo $temple['temple'] ?> Daily Pooja List for <?php echo $date ?>
			<div class="printPosition" id="print_btn_div"></div>
			</h2>
        </div>
        
    </div>
    <?php $masterArray = array(); ?>
    <h3>Daily Pooja List</h3>
	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Pooja</th></tr>
            <?php 
                $i =0;
                foreach($daily_pooja_list as $row){
                    $i++;
                    $masterArray[$row->pooja_name] = 1;
                    echo "<tr><td>$i</td><td>$row->pooja_name</td></tr>";
                }
            ?>
        </table>
    </div>
    <h3>List of Poojas Booked for <?php echo $date ?></h3>
	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Pooja</th><th>Devotee Name</th><th>Star</th></tr>
            <?php 
                $i =0;
                $yesterDate = date('Y-m-d',strtotime($date) - (24*3600));
                $defaultTime = date('Gi',strtotime(DEFAULT_DAILY_LIST_TIME));
                foreach($booked_pooja_list as $row){
                    if($row->receipt_date == $yesterDate){
                        if($defaultTime >= date('Gi',strtotime($row->receipt_time))){
                            if(isset($masterArray[$row->pooja_name])){
                                $masterArray[$row->pooja_name] = $masterArray[$row->pooja_name] + 1;
                            }else{
                                $masterArray[$row->pooja_name] = 1;
                            }
                        }
                        $i++;
                        echo "<tr><td>$i</td><td>$row->pooja_name</td><td>$row->name</td><td>$row->star</td></tr>";
                    }else if($row->receipt_date < $yesterDate){
                        if(isset($masterArray[$row->pooja_name])){
                            $masterArray[$row->pooja_name] = $masterArray[$row->pooja_name] + 1;
                        }else{
                            $masterArray[$row->pooja_name] = 1;
                        }
                        $i++;
                        echo "<tr><td>$i</td><td>$row->pooja_name</td><td>$row->name</td><td>$row->star</td></tr>";
                    }
                }
            ?>
        </table>
    </div>
    <h3>Total Poojas for <?php echo $date ?></h3>
	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Pooja</th><th>Count</th></tr>
            <?php 
                $i =0;
                ksort($masterArray);
                foreach($masterArray as $row => $value){
                    $i++;
                    echo "<tr><td>$i</td><td>$row</td><td>$value</td></tr>";
                }
            ?>
        </table>
    </div>
</div>
<hr>