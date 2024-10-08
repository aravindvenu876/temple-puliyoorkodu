<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                  <h3><?php echo $this->lang->line('cat_wise_income_report'); ?></h3>
                  <hr class="hrCustom">
                  <div class="row">
                     <div class="col-md-2 col-sm-2 col-12 calendar_iconless">
                        <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                        <div class="form-group">
                           <input type="text" name="from_date" id="from_date" data-required="true" readonly class="form-control parsley-validated parsley-error" 
                            value="<?php echo date('d-m-Y') ?>"/>
                        </div>
                     </div>
                     <div class="col-md-2 col-sm-2 col-12 calendar_iconless">
                        <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                        <div class="form-group">
                           <input type="text" name="to_date" id="to_date" data-required="true" readonly class="form-control parsley-validated parsley-error" value="<?php echo date('d-m-Y') ?>"/>
                        </div>
                     </div>
                     <div class="col-md-2 col-sm-3 col-12">
                        <span class="span_label"><?php echo $this->lang->line('type'); ?></span>
                        <div class="form-group">
                           <select name="type" id="type" class="form-control"></select>
                        </div>
                     </div>
                     <div class="col-md-3 col-sm-3 col-12"  id="variableInputType">
                        <span class="span_label"><?php echo $this->lang->line('category'); ?></span>
                        <div class="form-group">
                           <select name="item" id="item" class="form-control">
                           <option value=""><option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3 col-sm-3 col-12"  id="variableInputType1">
                        <span class="span_label" id="variableInputTypeSpan1"><?php echo $this->lang->line('pooja'); ?></span>
                        <div class="form-group">
                           <select name="pooja" id="pooja" class="form-control">
                           <option value=""><option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-12 ">
                        <button id="btn_submit" class="btn btn-primary saveButton"><?php echo $this->lang->line('filter'); ?></button>
                        <!-- <button class="btn btn-primary btn_print_html"><?php echo $this->lang->line('print'); ?></button> -->
                        <button class="pdf btn btn-primary">PDF</button> 
                        <!-- <button class="btn btn-warning"><i class="fa fa-file-excel-o"></i></button> -->
                        <button class="btn btn-default btn_clear"><?php echo $this->lang->line('clear'); ?></button>
                     </div>
                  </div>
                  <div class="table-responsive" style="margin-top:15px" id="itemid" >
                     <table class="table table-bordered scrolling table-striped table-sm" > 
                        <thead  id="tableid">
                           <h3><?php echo $this->lang->line('prasadam'); ?></h3>
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('category'); ?></th>
                              <th><?php echo $this->lang->line('item'); ?></th>
                              <th><?php echo $this->lang->line('rate'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                           </tr>
                        </thead>
                        <tbody id="report_body"></tbody>
                     </table>
                  </div>
                  <div class="table-responsive" style="margin-top:15px" id="poojaid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('pooja'); ?></h3>
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('pooja_category'); ?></th>
                              <th><?php echo $this->lang->line('pooja'); ?></th>
                              <th><?php echo $this->lang->line('rate'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_bodyy"></tbody>
                     </table>
                  </div>
                  <div class="table-responsive poojaid_sub1" style="margin-top:15px" id="poojaid1">
                     <table class="table poojaid_sub1 table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('chovazhchakavu'); ?></h3>
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('pooja_category'); ?></th>
                              <th><?php echo $this->lang->line('pooja'); ?></th>
                              <th><?php echo $this->lang->line('rate'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_bodyy_sub"></tbody>
                     </table>
                  </div>
                  <div class="table-responsive poojaid_sub2" style="margin-top:15px"id="poojaid2">
                     <table class="table poojaid_sub2 table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('mathampilli'); ?></h3>
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('pooja_category'); ?></th>
                              <th><?php echo $this->lang->line('pooja'); ?></th>
                              <th><?php echo $this->lang->line('rate'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_bodyy_sub1"></tbody>
                     </table>
                  </div>
                  <div class="table-responsive" style="margin-top:15px" id="postalid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('postal'); ?></h3>
                            <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('category'); ?></th>
                              <th><?php echo $this->lang->line('rate'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_body3"></tbody>
                     </table>
                  </div>
                  <!-- hall -->
                  <div class="table-responsive" style="margin-top:15px" id="hallid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('hall'); ?></h3>
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('hall'); ?></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr>
                        </thead>
                        <tbody id="report_body_hall"></tbody>
                     </table>
                  </div>
                  <!-- donation -->
                  <div class="table-responsive" style="margin-top:15px" id="donid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('donation'); ?></h3>
                            <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('donation'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                           </tr>
                        </thead>
                        <tbody id="report_body_donation"></tbody>
                     </table>
                  </div>
                  <!-- donation    --> 
                  <!-- anna -->
                  <div class="table-responsive" style="margin-top:15px" id="annid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('annadanam'); ?></h3>
                          <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('annadanam'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                           </tr> 
                        </thead>
                        <tbody id="report_body_ann"></tbody>
                     </table>
                  </div>
                   <!-- Balithara -->
                   <div class="table-responsive" style="margin-top:15px" id="baliid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('balithara'); ?></h3>
                            <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('balithara'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_body_bali"></tbody>
                     </table>
                  </div>
                  <div class="table-responsive" style="margin-top:15px" id="assetid">
                     <table class="table table-bordered scrolling table-striped table-sm">
                        <thead>
                           <h3><?php echo $this->lang->line('asset'); ?></h3>
                            <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('asset_category'); ?></th>
                              <th><?php echo $this->lang->line('quantity'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                              </tr> 
                        </thead>
                        <tbody id="report_body2"></tbody>
                     </table>
                  </div>
                  
                  <!-- Balithara end -->
                  <!-- Income -->
                  <div class="table-responsive" style="margin-top:15px" id="report_body_income">
                     <h3><?php echo $this->lang->line('mattuvarumanam'); ?></h3>
                     <table class="table table-bordered scrolling table-striped table-sm">
                     	<thead>                        
                           <tr class="bg-warning text-white ">
                              <th><?php echo $this->lang->line('sl'); ?></th>
                              <th><?php echo $this->lang->line('item'); ?></th>
                              <th><?php echo $this->lang->line('amount'); ?></th>
                           </tr>
                        </thead>
                        <tbody id="report_body_mattu"></tbody>
                     </table>
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
