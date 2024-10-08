<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;"></div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('todays_booked_poojas'); ?> (<?php echo date('d M Y') ?>)</h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('pooja'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_pooja_name" class="form-control">
                                                        <option value="">Select Pooja</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-12 ">
                                                <span class="span_label"><?php echo $this->lang->line('receipt_no'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_receipt_no" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-12 ">
                                                <span class="span_label"><?php echo $this->lang->line('name'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-12 ">
                                                <span class="span_label"><?php echo $this->lang->line('phone'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_phone" class="form-control"/>
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="today_poojas" table="today_poojas" action_url="<?php echo base_url() ?>service/Pooja_data/today_pooja_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('temple'); ?></th>
                                                        <th><?php echo $this->lang->line('pooja'); ?></th>
                                                        <th><?php echo $this->lang->line('booked_for'); ?></th>
                                                        <th><?php echo $this->lang->line('booked_on'); ?></th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('star'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('prasadam'); ?></th>
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
