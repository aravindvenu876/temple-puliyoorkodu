<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                      
                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('new_rent_form');?></h3>
													<hr class="hrCustom">
                                       
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('name'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="name" id="name" class="alpha form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('phone'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="phone"  id="phone" maxlength="10"  class="numeric number form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('address'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <textarea name="address" id="address" class="Bookser_no form-control" placeholder="<?php echo $this->lang->line('address'); ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_asset_register">
                                                    <input type="hidden" name="count" id="count"/>
                                                    <input type="hidden" name="actual" id="actual"/>
                                                    <input type="hidden" name="asset_count_1" id="asset_count_1"/>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <select name="asset_1" id="asset_1" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent(1)"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('rent_price'); ?></span>
                                                        <div class="form-group">
                                                            <input type="number" name="cost_1" id="cost_1" class="form-control" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <select name="quantity_1" id="quantity_1" class="form-control parsley-validated" data-required="true" onchange="calculate_total_rate(1)"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('unit'); ?></span>
                                                        <div class="form-group">
                                                            <input type="text" name="unit_1" id="unit_1" class="form-control" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('total_amount'); ?></span>
                                                        <div class="form-group">
                                                            <input type="number" name="total_rate_1" id="total_rate_1" min="1" class="form-control" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
                                                        <br>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" onclick="add_asset_dynamic()"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('total_amount'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="total_amount" id="total_amount" min="1" class="form-control" step="any" readonly data-required="true" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('discount'); ?></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="number" name="discount" id="discount" min="0.00" class="form-control parsley-validated"  data-required="true" autocomplete="off" step="0.01" onkeyup="calculate_net_rate()">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('net_amount'); ?></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="number" name="net_amount" id="net_amount" min="0.00" step="0.01" class="form-control" readonly autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                                                </div>
                                            </div>
										
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        
                                                <h3><?php echo $this->lang->line('asset_rent_details');?></h3>
                                           <hr class="hrCustom">
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('asset_rent_from'); ?></button>
                                        
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="asset_rent" table="asset_rent" action_url="<?php echo base_url() ?>service/Asset_rent_data/assets_rent_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>   
                                                        <th><?php echo $this->lang->line('rented_by'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('total'); ?></th>
                                                        <th><?php echo $this->lang->line('discount'); ?></th>
                                                        <th><?php echo $this->lang->line('net_amount'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
                                                        <th><?php echo $this->lang->line('outpass'); ?></th>
                                                        <th><?php echo $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
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
<script>

</script>
