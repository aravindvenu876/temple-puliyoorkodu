<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3> <?php echo $this->lang->line('salary_process_report'); ?></h3>
                                        <hr class="hrCustom">
                                        <div class="row">   
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                <span class="span_label "><?php echo $this->lang->line('year'); ?></span>
                                                <div class="form-group">
                                                    <select name="year" id="year" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                <span class="span_label "><?php echo $this->lang->line('month'); ?></span>
                                                <div class="form-group">
                                                    <select name="month" id="month" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>                                                         
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12">
                                            <button class="btn btn-primary getData" onclick="getProcessedSalaryData()"><?php echo $this->lang->line('filter'); ?></button>
                                            <button class="btn btn-primary buttonExcel" onclick="getProcessedSalaryDataExcel()">Excel</button>
                                                <button class="btn btn-primary pdf_report">PDF</button>
                                                <button class="btn btn-primary btn_print_html"><?php echo $this->lang->line('print'); ?></button>
                                                <button class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                                            </div>
                                        </div>	
                                        <div class="NewSalary bg_new_form" style="margin-top:15px;">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl#</th>
                                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                                        <th><?php echo $this->lang->line('salary_payable'); ?></th>
                                                        <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th><?php echo $this->lang->line('account_no'); ?></th>
                                                        <th><?php echo $this->lang->line('ifsc_code'); ?></th>
                                                        <th>Process Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dynamic_asset_register"></tbody>
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