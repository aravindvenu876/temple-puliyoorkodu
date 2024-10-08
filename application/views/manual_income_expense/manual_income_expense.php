<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3>Manual Income Expense Reports</h3>
                                        <div class="table-responsive" style="margin-top:15px;">
                                            <table class="table table-bordered scrolling table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>Sl</th>
                                                        <th>Temple</th>
                                                        <th>Month</th>
                                                        <th>PDF</th>
                                                        <th>Excel</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach($reports as $row){
                                                        $i++;
                                                        echo '<tr>';
                                                        echo '<td>'.$i.'</td>';
                                                        echo '<td>'.$row->temple.'</td>';
                                                        echo '<td>'.date('d M Y',strtotime($row->from_date)).' To '.date('d M Y',strtotime($row->to_date)).'</td>';
                                                        echo '<td>';
                                                        echo '<a href="'.base_url().'manual_income_expense/report_pdf/'.$row->temple_id.'/'.$row->from_date.'/'.$row->to_date.'" target="blank">';
                                                        echo '<button class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Pdf </button>';
                                                        echo '</a>';
                                                        echo '</td>';
                                                        echo '<td>';
                                                        echo '<a href="'.base_url().'manual_income_expense/report_csv/'.$row->temple_id.'/'.$row->from_date.'/'.$row->to_date.'" target="blank">';
                                                        echo '<button class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel </button>';
                                                        echo '</a>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </tbody>
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