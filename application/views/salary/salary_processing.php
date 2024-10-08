<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;"> 
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('new_salary_processing'); ?></h3>
													              <hr class="hrCustom">
   
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
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
                                            <div class="NewSalary bg_new_form">

                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan='2'>
                                                                    <input type="hidden" name="count" id="count"/>
                                                                    <?php echo $this->lang->line('staff'); ?>
                                                                </th>
                                                                <th><?php echo $this->lang->line('basic_salary(₹)'); ?></th>
                                                                <th><?php echo $this->lang->line('advances_paid'); ?></th>
                                                                <th><?php echo $this->lang->line('other_addition'); ?></th>
                                                                <th><?php echo $this->lang->line('leave_deduction'); ?></th>
                                                                <th><?php echo $this->lang->line('extra_allowance'); ?></th>
                                                                <th><?php echo $this->lang->line('extra_deduction'); ?></th>
                                                                <th><?php echo $this->lang->line('salary_payable'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="dynamic_asset_register"></tbody>
                                                    </table>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                <h3><?php echo $this->lang->line('processed_salary_details'); ?></h3>
                                                 <hr class="hrCustom">
                                                 <div class="row">
                                            <div class="col-md-4 col-sm-5 col-4 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('staff'); ?></span>
                                                <div class="form-group">
                                                <select id="filter_staff" class="form-control parsley-validated" data-required="true">
                                                        <option value="">Select Staff</option>
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('year'); ?></span>
                                                    <div class="form-group">
                                                        <select  id="filter_year" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('month'); ?></span>
                                                    <div class="form-group">
                                                        <select  id="filter_month" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('process_salary'); ?></button>

                                        <div class="table-responsive table_div ">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="salary_processing" table="salary_processing" action_url="<?php echo base_url() ?>service/Salary_data/processed_salaries">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                                        <th><?php echo $this->lang->line('salary_month'); ?></th>
                                                        <th><?php echo $this->lang->line('monthly_salary(₹)'); ?></th>
                                                        <th><?php echo $this->lang->line('total_deductable(₹)'); ?></th>

                                                        <th><?php echo $this->lang->line('total_addable(₹)'); ?></th>
                                                        <th><?php echo $this->lang->line('payable_salary'); ?></th>
                                                        <th><?php echo $this->lang->line('processed_on'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
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