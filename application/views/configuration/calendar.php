            <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                  <div class="tab_box ">
                     <div class="tab-content">
                        <div class="tab-pane active">
                           <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                              <h3><?php echo $this->lang->line('calendar'); ?></h3>
                              <hr class="hrCustom">
                              <div class="row">
                                 <div class="col-sm-3"> 
                                    <span class="span_label "><?php echo $this->lang->line('english_year'); ?><span class="asterisk">*</span></span>
                                    <div class="form-group">
                                       <select class="form-control parsley-validated" data-required="true" name="gregyear" id="gregyear">
                                          <?php 
                                             $year = AIY;
                                             while($year <= 2037){
                                                echo '<option value="'.$year.'">'.$year.'</option>';
                                                $year+=1;
                                             }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-3"> 
                                    <span class="span_label "><?php echo $this->lang->line('english_month'); ?><span class="asterisk">*</span></span>
                                    <div class="form-group">
                                       <select class="form-control parsley-validated" data-required="true" name="gregmonth" id="gregmonth">
                                          <?php 
                                             $months = gregMonths();
                                             $currentGregMonth = date('m',time());
                                             foreach($months as $k=>$v){
                                                if($currentGregMonth == date('m',strtotime($v['gregdate']))){
                                                   echo '<option selected value="'.date('m',strtotime($v['gregdate'])).'">'.$v['gregmonth'].'</option>';
                                                }else{
                                                   echo '<option value="'.date('m',strtotime($v['gregdate'])).'">'.$v['gregmonth'].'</option>';
                                                }
                                             }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-3"> 
                                    <span class="span_label "><?php echo $this->lang->line('malayalam_year'); ?><span class="asterisk">*</span></span>
                                    <div class="form-group">
                                       <select class="form-control parsley-validated" data-required="true" name="malyear" id="malyear">
                                          <?php 
                                             $malYears = malYears();
                                             $currentMalYear = getMalYear(date('Y-m-d',time()));
                                             foreach($malYears as $row){
                                                if($currentMalYear['malyear'] == $row['malyear']){
                                                   echo '<option selected value="'.$row['malyear'].'">'.$row['malyear'].'</option>';
                                                }else{
                                                   echo '<option value="'.$row['malyear'].'">'.$row['malyear'].'</option>';
                                                }
                                             }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-3"> 
                                    <span class="span_label "><?php echo $this->lang->line('malayalam_month'); ?><span class="asterisk">*</span></span>
                                    <div class="form-group">
                                       <select class="form-control parsley-validated" data-required="true" name="malmonth" id="malmonth">
                                          <?php 
                                             $malMonths = malMonths($currentMalYear['malyear']);
                                             $currentMalMonth = getMalMonth(date('Y-m-d',time()));
                                             foreach($malMonths as $row){
                                                if($currentMalMonth['malmonth'] == $row['malmonth']){
                                                   echo '<option selected value="'.explode('-',$row['maldate'])[1].'">'.$row['malmonth'].'</option>';
                                                }else{
                                                   echo '<option value="'.explode('-',$row['maldate'])[1].'">'.$row['malmonth'].'</option>';
                                                }
                                             }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <br>
                              <hr class="hrCustom">
                              <div class="row" id="calendar_heading">
                                 <?php echo getCalendarHeading('eng',date('Y-m-d',time()));?>
                              </div>
                              <form id="edit_calendar_form" method="post" enctype="multipart/form-data">
                                 <div class="table-responsive table_div">
                                    <table class="table table-bordered  table-striped" style="text-align:center;">
                                       <thead>
                                          <tr class="bg-warning text-white">
                                             <th width="10%"><?php echo $this->lang->line('weekday'); ?></th>
                                             <th width="10%"><?php echo $this->lang->line('malDate'); ?></th>
                                             <th width="10%"><?php echo $this->lang->line('engDate'); ?></th>
                                             <th width="15%"><?php echo $this->lang->line('nakshatram'); ?></th>
                                             <th width="10%"><?php echo $this->lang->line('nakshatramTime'); ?></th>
                                             <th width="15%"><?php echo $this->lang->line('thithi'); ?></th>
                                             <th width="10%"><?php echo $this->lang->line('thithiTime'); ?></th>
                                             <th width="10%"><?php echo $this->lang->line('vavu'); ?></th>
                                             <th width="5%"><?php echo $this->lang->line('hall_blocking'); ?></th>
                                             <th width="5%"><?php echo $this->lang->line('aavahanam_blocking'); ?></th>
                                          </tr>
                                       </thead>
                                       <tbody id="calendar_content">
                                       <?php echo getCalendarContent('eng',date('Y-m-d',time()));?>
                                       </tbody>
                                    </table>
                                 </div>
                              </form>
                              <div class="row">
                                 <div class="col-sm-12">
                                    <button onClick="save_calendar_changes()" id="calendar_save" class="btn btn-success pull-right">
                                       Save
                                    </button>
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