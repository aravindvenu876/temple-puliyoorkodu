<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                <h3><?php echo $this->lang->line('expense_reports'); ?></h3>
												<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('transaction_type'); ?></span>
                                                <div class="form-group">
                                                    <select name="type" id="type" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="Income">Income</option>
                                                        <option value="Expense">Expense</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('expense_type'); ?></span>
                                                <div class="form-group">
                                                    <select name="head" id="head" class="form-control">
														<option value="">--Select--</option>
													</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('expense_type'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Party Name"/>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12">
                                                <button id="btn_submit" class="btn btn-primary saveButton"><?php echo $this->lang->line('filter'); ?></button>
                                                <button class="btn btn-primary btn_print_html"><?php echo $this->lang->line('print'); ?></button>
                                                <button class="btn btn-primary pdf_report">PDF</button>
                                                <button class="btn btn-primary excel_report">Excel</button>
                                                <button class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                                            </div>
                                        </div>	
                                        <div class="table-responsive" style="margin-top:15px;">
                                            <table class="table table-bordered scrolling table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th><?php echo $this->lang->line('sl'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('voucher_number'); ?></th>
                                                        <th><?php echo $this->lang->line('expense_type'); ?></th>
                                                        <th><?php echo $this->lang->line('transaction_type'); ?></th>
                                                        <th><?php echo $this->lang->line('expense_amount'); ?></th>
                                                        <th><?php echo $this->lang->line('mode_of_transfer'); ?></th>
                                                        <th><?php echo $this->lang->line('description'); ?></th>
                                                        <th>Name & Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="report_body"></tbody>
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
