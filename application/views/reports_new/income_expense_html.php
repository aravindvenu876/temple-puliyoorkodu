            <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                <div class="tab_nav">
                    <div class="tab_box ">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                    <h3><?php echo $this->lang->line('income_expense'); ?></h3>
                                    <hr class="hrCustom">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                            <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                                            <div class="form-group">
                                                <input type="text" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                            <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                                            <div class="form-group">
                                                <input type="text" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <br>
                                            <button class="btn btn-primary" onclick="report_html()">SEARCH REPORT</button>
                                            <button class="btn btn-danger"  onclick="report_pdf()">IN PDF</button> 
                                            <!-- <button class="btn btn-danger"  onclick="report_excel()">IN EXCEL</button> -->
                                            <button class="btn btn-default" onclick="report_clear()">CLEAR</button>
                                        </div>
                                    </div>
                                    <hr class="hrCustom">
                                    <div id="report_content"><b><i>INCOME EXPENSE REPORTS</i></b></div>
                                    <hr class="hrCustom">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>