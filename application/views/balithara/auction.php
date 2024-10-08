<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('add_balithara_auction'); ?></h3>
												<hr class="hrCustom">	

                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('from'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="from_date" id="from_date" class="date form-control parsley-validated" data-required="true" readonly="">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('to'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="to_date" id="to_date" class="date form-control parsley-validated" data-required="true" readonly="">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('balithara'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="balithara" id="balithara" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text"  name="name" id="name" class="alpha form-control parsley-validated" data-required="true" placeholder="Name"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('phone'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text"  name="phone" id="phone"  class="form-control parsley-validated" maxlength="10" data-required="true" placeholder="Phone"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('address'); ?></span>
                                                    <div class="form-group">
                                                        <textarea name="address" id="address" class="form-control" placeholder="Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">

                                                <h3><?php echo $this->lang->line('balithara_auction_details'); ?></h3>
<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('balithara'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_balithara" class="form-control">
                                                        <option value="">Select Balithara</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">From Date</span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_from_date" class="form-control" value=""/>
                                                </div>
                                            </div> -->
                                            <!-- <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">To Date</span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_to_date" class="form-control" value=""/>
                                                </div>
                                            </div> -->
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('phone'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_phone" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('name'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary saveButton" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_balithara_auction'); ?></button>

                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="balithara_auction_master" table="balithara_auction_master" action_url="<?php echo base_url() ?>service/Balithara_data/balithara_auction_master_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('balithara'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('address'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
                                                        <th><?php echo $this->lang->line('start_date'); ?></th>
                                                        <th><?php echo $this->lang->line('end_date'); ?></th>
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
