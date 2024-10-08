<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3>Ledger Report</h3>
                                        <hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Account</span>
                                                <div class="form-group">
                                                    <select name="head" id="head" class="form-control">
														<option value="">Select Account</option>
													</select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12 ">
                                                <button id="btn_submit" class="btn btn-primary saveButton"><?php echo $this->lang->line('filter'); ?></button>
                                                <button class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                                                <button class="btn btn-success pull-right" id="ledger_pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Pdf</button>&nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-success pull-right" id="ledger_excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
                                            </div>
                                        </div>	
                                        <div class="table-responsive" style="margin-top:15px;" id="report_body"></div>			    	
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
