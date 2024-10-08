<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('return_rented_assets'); ?></h3>
											<hr class="hrCustom">
                                    
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?><b id="date"></b></span>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('name'); ?><b id="name"></b></span>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('phone'); ?><b id="phone"></b></span>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('address'); ?><b id="address"></b></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="bg_form ">
                                                <div class="row">
                                                    <input type="hidden" name="count" id="count" vlue='0'/>
													<div class="table-responsive tableCustomDes">
                                                    <table class="table table-sm table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo $this->lang->line('sl'); ?></th>
                                                                <th><?php echo $this->lang->line('asset'); ?></th>
                                                                <th><?php echo $this->lang->line('rented'); ?></th>
                                                                <th><?php echo $this->lang->line('unit'); ?></th>
                                                                <th><?php echo $this->lang->line('returned'); ?><span class="asterisk">*</span></th>
                                                                <th><?php echo $this->lang->line('rent_unit_inr'); ?></th>
                                                                <th><?php echo $this->lang->line('rent_inr'); ?></th>
                                                                <th><?php echo $this->lang->line('scrap'); ?><span class="asterisk">*</span></th>
                                                                <th><?php echo $this->lang->line('price_unit_inr'); ?></th>
                                                                <th><?php echo $this->lang->line('price_inr'); ?></th>
                                                                <th><?php echo $this->lang->line('total'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="dynamic_asset_register"></tbody>
                                                    </table>
                                                </div></div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('total_amount'); ?></span>
                                                    <div class="form-group">
                                                        <input type="number" name="total_amount" id="total_amount" min="1" class="form-control parsley-validated" readonly data-required="true" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('discount'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="number" name="discount" id="discount" min="0" class="form-control parsley-validated" data-required="true" readonly autocomplete="off" onkeyup="calculate_net_rate()">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('net_amount'); ?></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="number" name="net_amount" id="net_amount" min="1" class="form-control" readonly autocomplete="off">
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
                             
                                           
                                                <h3><?php echo $this->lang->line('return_rented_assets'); ?></h3>
                                      <hr class="hrCustom">
                           
                                                <a class="plus_btn"></a>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="asset_rent" table="asset_rent" action_url="<?php echo base_url() ?>service/Asset_rent_data/assets_rent_return_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <!-- <th><?php echo $this->lang->line('status'); ?></th> -->
                                                        <th><?php echo $this->lang->line('rented_on'); ?></th>
                                                        <th><?php echo $this->lang->line('rented_by'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('total'); ?></th>
                                                        <th><?php echo $this->lang->line('discount'); ?></th>
                                                        <th><?php echo $this->lang->line('net_amount'); ?></th>
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
