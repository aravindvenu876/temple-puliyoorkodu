<?php if(!isset($master)){ ?>
    <div class="col-md-12 col-sm-12 col-12 listview">
        <div class="row ">
            <div class="col-md-12 col-sm-12 col-12">
                <h2 class="poojaList"><?php echo $data['temple']['temple'] ?> <?php echo $this->lang->line('stock_list_issue_for'); ?> <?php echo $data['date'] ?></h2>
            </div>
        </div>
        <div class="table-responsive">
            <form method="post" id="stock_issue_form">
                <input type="hidden" name="date" value="<?php echo $data['date'] ?>"/>
                <table class="table table-bordered table-sm table-striped">
                    <tr class="bg-warning text-white"><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset/item'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th></tr>
                    <?php
                        $j = 0;
                        foreach($arrayToPrint as $row){
                            $j++;
                            echo "<input type='hidden' name='assetId[]' value='".$row['asset_id']."'/>";
                            echo "<input type='hidden' name='quantity[]' value='".$row['quantity']."'/>";
                            echo "<tr>";
                            echo "<td>$j</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['quantity'] ." ". $row['unit']."</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </form>
        </div>
    </div>
<?php }else{ ?>
    <div class="col-md-12 col-sm-12 col-12 listview">
        <div class="row ">
            <div class="col-md-12 col-sm-12 col-12">
                <h2 class="poojaList"><?php echo $temple['temple'] ?><?php echo $this->lang->line('stock_issue_list_for'); ?> <?php echo date('d-m-Y',strtotime($master['date'])) ?>
                    <div class="printPosition" id="print_btn_div"></div>
                </h2>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped">
                <tr class="bg-warning text-white"><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('asset/item'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th></tr>
                <?php
                    $j = 0;
                    foreach($details as $row){
                        $j++;
                        echo "<tr>";
                        echo "<td>$j</td>";
                        echo "<td>" . $row->asset_name . "</td>";
                        echo "<td>" . $row->quantity ." ". $row->notation."</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </div>
<?php } ?>