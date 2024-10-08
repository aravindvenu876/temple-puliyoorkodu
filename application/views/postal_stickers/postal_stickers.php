<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                        <div class="tab_nav">
                            <div class="tab_box ">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <div class="dtl_tbl show_form_add"  style="min-height: auto;" >          
                                            <h3><?php echo $this->lang->line('postal_sticker_creation'); ?></h3>
                                            <hr class="hrCustom">
                                        </div>
                                        <div class="row ">
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12"> 
                                                <span class="span_label ">From</span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" readonly="" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
                                                <span class="span_label ">To</span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" readonly="" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-12 ">
                                                <br>
                                                <button class="btn btn-primary" onclick="get_postal_stickers()">Filter</button>
                                            </div>
                                        </div>
                                        <div id="list" class="row"></div>
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