<div class="col-md-12 col-sm-12 col-12 listview">
    <div class="row ">
        <div class="col-md-12 col-sm-12 col-12">
            <h2 class="poojaList"><?php echo $temple['temple'] ?> Daily Nivedya Pooja List for <?php echo $date ?>
			    <div class="printPosition" id="print_btn_div"></div>
			</h2>
        </div>
    </div>
    <?php 
        $masterArray = array(); 
        $unitArray = array(); 
        $itemArray = array();
    ?>
    <h3>Daily Nivedya List</h3>	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Nivedyam</th><th>Pooja</th><th>Quantity</th></tr>
            <?php 
                $i =0;
                foreach($daily_nivedya_list as $row){
                    if(isset($masterArray[$row->item_category_id])){
                        $masterArray[$row->item_category_id] = (float)$masterArray[$row->item_category_id] + (float)$row->defined_quantity;
                    }else{
                        $masterArray[$row->item_category_id] = $row->defined_quantity;
                        $unitArray[$row->item_category_id] = $row->notation;
                        $itemArray[$row->item_category_id] = $row->category;
                    }
                    $i++;
                    echo "<tr><td>$i</td><td>$row->name</td><td>$row->pooja_name</td><td>$row->defined_quantity $row->notation</td></tr>";
                }
            ?>
        </table>
    </div>
    <h3>List of Nivedyams Booked for <?php echo $date ?></h3>	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Nivedyam</th><th>Pooja</th><th>Quantity</th><th>Devotee Name</th><th>Star</th></tr>
            <?php 
                $i = 0;
                foreach($booked_nivedya_list as $row){
                    if(isset($masterArray[$row->item_category_id])){
                        $masterArray[$row->item_category_id] = (float)$masterArray[$row->item_category_id] + (float)$row->total_quantity;
                    }else{
                        $masterArray[$row->item_category_id] = $row->total_quantity;
                        $unitArray[$row->item_category_id] = $row->notation;
                        $itemArray[$row->item_category_id] = $row->category;
                    }
                    $i++;
                    echo "<tr><td>$i</td><td>$row->item</td><td>$row->pooja_name</td><td>$row->total_quantity $row->notation</td><td>$row->name</td><td>$row->star</td></tr>";
                }
            ?>
        </table>
    </div>
    <h3>List of Additional Nivedyams for <?php echo $date ?></h3>	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Nivedyam</th><th>Pooja</th><th>Quantity</th></tr>
            <?php 
                $i =0;
                foreach($additional_nivedya_list as $row){
                    if(isset($masterArray[$row->item_category_id])){
                        $masterArray[$row->item_category_id] = (float)$masterArray[$row->item_category_id] + (float)$row->quantity;
                    }else{
                        $masterArray[$row->item_category_id] = $row->quantity;
                        $unitArray[$row->item_category_id] = $row->notation;
                        $itemArray[$row->item_category_id] = $row->category;
                    }
                    $i++;
                    echo "<tr><td>$i</td><td>$row->item</td><td>$row->type</td><td>$row->quantity $row->notation</td></tr>";
                }
            ?>
        </table>
    </div>
    <h3>Total Nivedya list for <?php echo $date ?></h3>	<hr class="hrCustom">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <tr class="bg-warning text-white"><th>Sl#</th><th>Nivedyam</th><th>Quantity</th></tr>
            <?php 
                $i =0;
                ksort($masterArray);
                foreach($masterArray as $row => $value){
                    $i++;
                    echo "<tr><td>$i</td><td>".$itemArray[$row]."</td><td>".number_format($value,2,'.', '')." ".$unitArray[$row]."</td></tr>";
                }
            ?>
        </table>
    </div>
</div>
<hr>