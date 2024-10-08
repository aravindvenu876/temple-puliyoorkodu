<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                <h3>Staff Details Reports</h3>
												<hr class="hrCustom">
                                        <div class="row">   
                                        <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">From Date</span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">To Date</span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                                                </div>
                                            </div>                                       
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <span class="span_label">Designation</span>
                                                <div class="form-group">
                                                    <select name="designation" id="designation" class="form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12">
                                                <button id="btn_submit" class="btn btn-primary saveButton">Filter</button>
                                                <button class="btn btn-primary btn_print_html">Print</button>
                                                <button class="btn btn-primary pdf_report">PDF</button>
                                                <!-- <button class="btn btn-warning"><i class="fa fa-file-excel-o"></i></button> -->
                                                <button class="btn btn-default btn_clear">Clear</button>
                                            </div>
                                        </div>	
                                        <div class="table-responsive" style="margin-top:15px;">
                                            <table class="table table-bordered scrolling table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>Sl#</th>
                                                        <th>Date</th>
                                                        <th>Staff-Id</th>
                                                        <th>Name</th>
                                                        <th>Phone Number</th>
                                                        <th>Designation</th>
                                                        <th>Type</th>
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