<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                   <h3><?php echo $this->lang->line('income_expense'); ?></h3>
                  <!-- <h3>Income_expense</h3> -->
                  <hr class="hrCustom">
                  <div class="row">
                     <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                        <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                        <div class="form-group">
                           <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                        </div>
                     </div>
                     <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                        <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                        <div class="form-group">
                           <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>"/>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-12 ">
                        <button id="btn_submit" class="btn btn-primary saveButton"><?php echo $this->lang->line('filter'); ?></button>
                        <!-- <button class="btn btn-primary btn_print_html"><?php echo $this->lang->line('print'); ?></button> -->
                        <!-- <a style='cursor: pointer;' data-toggle='tooltip' class='pdf_payslip btn btn-warning' data-placement='right' data-original-title = 'PDF Payslip'>
                           <i class='fa fa-file-pdf-o' aria-hidden='true'></i></a> -->
                        <button class="pdf_report btn btn-primary">PDF</button> 
                        <!--<button class="pdf_report1 btn btn-primary">New PDF</button> -->

                        <!-- <button class="btn btn-warning"><i class="fa fa-file-excel-o"></i></button> -->
                        <button class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                     </div>
                  </div>
                  <div class="row">
                  <h3 style="padding:10px;text-align: center;">  
                      <?php echo $this->lang->line('income'); ?></h3>
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    
                        <div class="table-responsive" style="margin-top:15px">
                        
                           <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <tr class="bg-warning text-white text-center">
                                 	<th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                 	<th style='text-align: left'><?php echo $this->lang->line('item'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('cash'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('card'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('mo'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('cheque'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('dd'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('online'); ?></th>

                                    <th  style='text-align: right'><?php echo $this->lang->line('total'); ?></th>
                                 </tr>
                              </thead>
                              <tbody id="report_body"></tbody>
                           </table>
                        </div>
                     </div>

                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive" style="margin-top:15px">     
                           <table class="table table-bordered scrolling table table-sm" style="background: #f1f1f3;">
                              <tbody id="report_body4"></tbody>
                           </table>
                        </div>
                     </div>

              
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <h3 style="padding-bottom:0;">    
                      <?php echo $this->lang->line('opening_balance'); ?></h3>
                        <div class="table-responsive" style="margin-top:15px">
                      
                            <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <!-- <tr class="bg-warning text-white text-center">
                                 <th colspan="3"></th>
                                 </tr> -->
                                 <tr class="bg-warning text-white text-center">
                                 <th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                 <th style='text-align: left'><?php echo $this->lang->line('item'); ?></th>
                                    <th style='text-align: left'><?php echo $this->lang->line('account'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('amount'); ?></th>
                                 </tr>
                              </thead>
                              <tbody id="report_body2"></tbody>
                           </table>
                        </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <h3 style="padding-bottom:0;"> FD 
                      <?php echo $this->lang->line('opening_balance'); ?></h3>
                        <div class="table-responsive" style="margin-top:15px">
                      
                            <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <!-- <tr class="bg-warning text-white text-center">
                                 <th colspan="3"></th>
                                 </tr> -->
                                 <tr class="bg-warning text-white text-center">
                                 <th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                 <th style='text-align: left'><?php echo $this->lang->line('bank'); ?></th>
                                    <th  style='text-align: left'><?php echo $this->lang->line('account'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('amount'); ?></th>
                                 </tr>
                              </thead>
                              <tbody id="report_body8"></tbody>
                           </table>
                        </div>
                  </div>
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <h3 style="padding-bottom:0;">  
                      <?php echo $this->lang->line('expense'); ?></h3>
                        <div class="table-responsive" style="margin-top:15px">
                           <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <tr class="bg-warning text-white text-center">
                                    <th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                    <th style='text-align: left'><?php echo $this->lang->line('item'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('cash'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('card'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('mo'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('cheque'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('dd'); ?></th>
                                    <th style='text-align: right'><?php echo $this->lang->line('online'); ?></th>
                                    <th  style='text-align: right'><?php echo $this->lang->line('total'); ?></th>
                                 </tr>
                              </thead>
                              <tbody id="report_body1"></tbody>
                           </table>
                        </div>
                     </div>
                  </div>

                  <div class="row">                 
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="report_body50"></div>
                  </div>
						
                  <div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive" style="margin-top:15px">
                           <table class="table table-bordered scrolling table table-sm" style="background: #f1f1f3;">
                              <tbody id="report_body5"></tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <h3  style="padding-bottom:0;"><?php echo $this->lang->line('closing_balance'); ?></h3>
                        <div class="table-responsive" style="margin-top:15px">
                      
                           <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <tr class="bg-warning text-white text-center">
                                 <th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                 <th style='text-align: left'><?php echo $this->lang->line('item'); ?></th>
                                    <th  style='text-align: left'><?php echo $this->lang->line('account'); ?></th>
                                    <th  style='text-align: right'><?php echo $this->lang->line('amount'); ?></th>
                                 </tr>
                              </thead>
                              <tbody id="report_body3"></tbody>
                           </table>
                        </div>
                     </div>
               </div>
               <div class="row">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <h3 style="padding-bottom:0;"> FD 
                      <?php echo $this->lang->line('closing_balance'); ?></h3>
                        <div class="table-responsive" style="margin-top:15px">
                      
                            <table class="table table-bordered scrolling table-striped table-sm">
                              <thead>
                                 <!-- <tr class="bg-warning text-white text-center">
                                 <th colspan="3"></th>
                                 </tr> -->
                                 <tr class="bg-warning text-white text-center">
                                 <th style='text-align: left'><?php echo $this->lang->line('sl'); ?></th>
                                 <th style='text-align: left'><?php echo $this->lang->line('bank'); ?></th>
                                 <th  style='text-align: left'><?php echo $this->lang->line('account'); ?></th>
                                 <th  style='text-align: right'><?php echo $this->lang->line('amount'); ?></th>
                                 </tr> 
                              </thead>
                              <tbody id="report_body9"></tbody>
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
</div>
</section>
</section>
