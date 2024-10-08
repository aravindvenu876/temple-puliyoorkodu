<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3><?php echo $this->lang->line('salary_advance'); ?></h3>
                                        <hr class="hrCustom">
                                        <div class="row">   
                                        <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Processed Salary Year</span>
                                                <div class="form-group">
                                                    <select id="filter_year" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Processed Salary Month</span>
                                                <div class="form-group">
                                                    <select id="filter_month" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Staff</span>
                                                <div class="form-group">
                                                    <select id="filter_staff" class="form-control"></select>
                                                </div>
                                            </div>                                                         
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12">
                                            <button class="btn btn-primary getData" onclick="get_salary_advance()"><?php echo $this->lang->line('filter'); ?></button>
                                            <button class="btn btn-primary buttonExcel" onclick="get_salary_advance_excel()">Excel</button>
                                                <button class="btn btn-primary pdf_report">PDF</button>
                                                 <button class="btn btn-primary btn_print_html"><?php echo $this->lang->line('print'); ?></button>
                                                <button id="btn_clearr" class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                                            </div>
                                        </div>	
                                        <div class="NewSalary bg_new_form" style="margin-top:15px;">
                                            <table class="table">
                                                <thead>
                                                <tr class="bg-warning text-white ">
                                                        <th>Slno</th>
                                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('amount(₹)'); ?></th>
                                                        <th><?php echo $this->lang->line('type'); ?></th>
                                                       
                                                        <th><?php echo $this->lang->line('description'); ?></th>
                                                        <th><?php echo $this->lang->line('payslip_id'); ?></th>
                                                        <th><?php echo $this->lang->line('created_on'); ?></th>
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