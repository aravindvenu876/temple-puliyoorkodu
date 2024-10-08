<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                        <div class="tab_nav">
                            <div class="tab_box ">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <div class="dtl_tbl show_form_add"  style="min-height: auto;" >          
                                            <h3>Generate XML For Tally Integration</h3>
                                            <hr class="hrCustom">
                                        </div>
                                        <div class="row ">
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12"> 
                                                <div class="form-group">
                                                    <input type="text" placeholder="Select Date" name="date" id="date" class="form-control" data-required="true" readonly=""/>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                <div class="form-group">
                                                    <button class="btn btn-primary" id="sync" onclick="sync_accounting_entries()">Sync Receipt Data With Accounting Entries</button>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                <div class="form-group">
                                                    <button class="btn btn-primary" id="generate" onclick="generate_tally_xml()">Generate Tally XML</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane active">
                                        <div class="dtl_tbl show_form_add"  style="min-height: auto;" >          
                                            <h3>Generate XML For Tally Integration</h3>
                                            <hr class="hrCustom">
                                        </div>
                                        <div class="row ">
                                            <style>
                                                .liMainClass{
                                                    font-family: bold;
                                                    margin-bottom: 5px;
                                                    margin-left: -15px;
                                                    font-size: 15px;
                                                }
                                                .liSubClass{
                                                    list-style: disc !important;
                                                }
                                            </style>
                                            <?php       
                                                $map = directory_map('./tally_files/');
                                                $dir = './tally_files/';
                                                foreach($map as $key => $row){
                                                    if(!empty($key)){
                                                        echo "<ul><li class='liMainClass'>$key</li>";
                                                        foreach($row as $key1 => $row1){
                                                            echo "<li class='liSubClass'><a href='".base_url()."tally_files/".$key.$row1."?download=download' download>$row1</a></li>";
                                                        }
                                                        echo "</ul>";
                                                    }
                                                }  
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>