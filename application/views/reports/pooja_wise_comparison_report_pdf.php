<?php require_once(APPPATH.'/language/english/site_lang.php');
require_once(APPPATH.'/language/malayalam/site_lang.php');?><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Pooja Wise Collection Comparison Report</title>
      <style>
         html,
         body,
         div,
         span,
         applet,
         object,
         iframe,
         h1,
         h2,
         h3,
         h4,
         h5,
         h6,
         p,
         blockquote,
         pre,
         a,
         abbr,
         acronym,
         address,
         big,
         cite,
         code,
         del,
         dfn,
         em,
         img,
         ins,
         kbd,
         q,
         s,
         samp,
         small,
         strike,
         strong,
         sub,
         sup,
         tt,
         var,
         b,
         u,
         i,
         center,
         dl,
         dt,
         dd,
         ol,
         ul,
         li,
         fieldset,
         form,
         label,
         legend,
         table,
         caption,
         tbody,
         tfoot,
         thead,
         tr,
         th,
         td,
         article,
         aside,
         canvas,
         details,
         embed,
         figure,
         figcaption,
         footer,
         header,
         hgroup,
         menu,
         nav,
         output,
         ruby,
         section,
         summary,
         time,
         mark,
         audio,
         video {
         margin: 0;
         padding: 0;
         border: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         }
         /* HTML5 display-role reset for older browsers */
         article,
         aside,
         details,
         figcaption,
         figure,
         footer,
         header,
         hgroup,
         menu,
         nav,
         section {
         display: block;
         }
         body {
         line-height: 1;
         }
         ol,
         ul {
         list-style: none;
         }
         blockquote,
         q {
         quotes: none;
         }
         blockquote:before,
         blockquote:after,
         q:before,
         q:after {
         content: '';
         content: none;
         }
         table {
         border-collapse: collapse;
         border-spacing: 0;
         }
         @page {
            margin-top: 20px;
    margin-bottom: 20px;
    margin-left: 20px;
    margin-right: 20px;
    }
      </style>
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
   </head>
   <body style="background: #fafafa;width:100%;">
      <div style="width: 100%; margin: auto;padding: 10px;border: 1px solid #ccc;background: #fff;">
	  <div style="width:100%; text-align: center;">
            <h1 style="font-family: 'Montserrat', meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold">
                <b><?php echo $this->lang->line('temple_trust'); ?></b>
            </h1>
            <h1 style="font-family: 'Montserrat', meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold;">
                <b><?php echo $temple; ?></b>
				</h1>
			</div>
        	<div style="width:70%; float: left;">
			<p
                  style="font-family: 'Montserrat', meera;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 12px;font-weight: bold;padding: 0px 10px;margin-bottom: 3px;">
                  <?php echo $this->lang->line('year_&_month'); ?>:<span><?php echo $current ?></span> 
               </p>
			</div>
        	<div style="width:120px; float: right; text-align:left;">
            <p style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               	<?php echo $this->lang->line('date'); ?> : <span><?php echo date("d-m-Y"); ?></span>
            </p>
            <p style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;">
               <?php echo $this->lang->line('time'); ?>  : <span><?php echo date("h:i A"); ?></span>
            </p>
        	</div>
         <div style=" clear: both"></div>
         <p
         style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('pooja_wise_comparison_reports'); ?>
         </p>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;">
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;">
         <h3   style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;"> <?php echo $this->lang->line('pooja'); ?></h3>


         <table style="width: 100%;margin: 20px 0px;">
         <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">SI</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">Pooja Code</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">Pooja</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $current;?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $previous;?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $prevYear;?></td>4            </tr>
         <?php  $i=1;
            $total=0;$total1=0;$total2=0;
             $total11=0;$total22=0;$total33=0;
            foreach($poojas as $row){
                //$i++;
                ?>
                 <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px">
                 <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 12px;color: #26272F;text-align: left;padding: 10px;font-size:14px">
               <?php echo $row->id; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">
               <?php echo $row->pooja_name; ?>
               </td>
               <?php $rate1 = "0.00";
                if(!empty($reports1)){
                    foreach($reports1 as $row1){
                        if($row->id == $row1->pooja_master_id){
                            $rate1 = $row1->total_amount;
                        }
                    }
                }
                $rate2 = "0.00";
                if(!empty($reports2)){
                    foreach($reports2 as $row2){
                        if($row->id == $row2->pooja_master_id){
                            $rate2 = $row2->total_amount;
                        }
                    }
                }
                $rate3 = "0.00";
                if(!empty($reports3)){
                    foreach($reports3 as $row3){
                        if($row->id == $row3->pooja_master_id){
                            $rate3 = $row3->total_amount;
                        }
                    }
                }


                /*     receipt amount adding script    */

                // $total3=0;$total4=0;$total5=0;
            // foreach($receipt as $row){
              $i++;
                $rate4 = "0.00";
                echo $rate4;
         //       echo'<pre>';print_r($receipt); die();
                if(!empty($receipt)){
                    foreach($receipt as $row4){
                        if($row->id == $row4->pooja_master_id){
                            $rate4 = $row4->total_amount;
                          }
                    }
                }
                $rate5 = "0.00";
                if(!empty($receipt1)){
                    foreach($receipt1 as $row5){
                        if($row->id == $row5->pooja_master_id){
                            $rate5 = $row5->total_amount;
                            
                        }
                    }
                }
                $rate6 = "0.00";
                if(!empty($receipt2)){
                    foreach($receipt2 as $row6){
                        if($row->id == $row6->pooja_master_id){
                            $rate6 = $row6->total_amount;
                            
                        }
                    }
                }
              // }



                    $total11= $rate1 +$rate4;
                    $total22=$rate2 +$rate5;
                    $total33=$rate3 +$rate6;


        ?>
               
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total11, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total22, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total33, 2, '.', ''); ?>
               </td>
            </tr>
            <?php       $total=$total + $total11;
                        $total1=$total1 + $total22;
                        $total2=$total2 + $total33;
            } ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="3"
                  style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo $this->lang->line('total_amount'); ?>(₹)

               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total, 2, '.', ''); ?>
               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total1, 2, '.', ''); ?>
               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total2, 2, '.', ''); ?>
               </td>
               <td colspan="1"></td>
            </tr>
         </table>
         <!-- prasaddam report -->
        <!-- <div style=" clear: both"></div> -->
         <!-- <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;">
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;"> -->
         <h3   style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;"> <?php echo $this->lang->line('prasadam'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">SI</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">Prasadam Code</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">Prasadam</td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $current;?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $previous;?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px"><?php echo $prevYear;?></td>4            </tr>
         <?php  
            $total_pr=0;$total_pr1=0;$total_pr2=0;
             $total111=0;$total222=0;$total333=0;
             foreach($prasadam as $row){
                  //$i++;
                  ?>
                 <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px">
                 <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 12px;color: #26272F;text-align: left;padding: 10px;font-size:14px">
               <?php echo $row->id; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px">
               <?php echo $row->name; ?>
               </td>
               <?php $rate7 = "0.00";
                if(!empty($reports4)){
                    foreach($reports4 as $row7){
                        if($row->id == $row7->item_master_id){
                            $rate7 = $row7->total_amount;
                        }
                    }
                }
                $rate8 = "0.00";
                if(!empty($reports5)){
                    foreach($reports5 as $row8){
                        if($row->id == $row8->item_master_id){
                            $rate8 = $row8->total_amount;
                        }
                    }
                }
                $rate9 = "0.00";
                if(!empty($reports6)){
                    foreach($reports6 as $row9){
                        if($row->id == $row9->item_master_id){
                            $rate9 = $row9->total_amount;
                        }
                    }
                }


                /*     receipt amount adding script    */

                // $total3=0;$total4=0;$total5=0;
            // foreach($receipt as $row){
             $i++;
                $rate10 = "0.00";
                //echo $rate4;
         //       echo'<pre>';print_r($receipt); die();
                if(!empty($receipt_pr)){
                    foreach($receipt_pr as $row10){
                        if($row->id == $row10->item_master_id){
                            $rate10 = $row10->total_amount;
                          }
                    }
                }
                $rate11 = "0.00";
                if(!empty($receipt1_pr)){
                    foreach($receipt1_pr as $row11){
                        if($row->id == $row11->item_master_id){
                            $rate11 = $row11->total_amount;
                            
                        }
                    }
                }
                $rate12 = "0.00";
                if(!empty($receipt2_pr)){
                    foreach($receipt2_pr as $row12){
                        if($row->id == $row6->item_master_id){
                            $rate12 = $row6->total_amount;
                            
                        }
                    }
                }
           // }
                    $total111= $rate7 +$rate10;
                    $total222=$rate8 +$rate11;
                    $total333=$rate9 +$rate12;

        ?>
               
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total111, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total222, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$total333, 2, '.', ''); ?>
               </td>
            </tr>
            <?php       $total_pr=$total_pr + $total111;
                        $total_pr1=$total_pr1 + $total222;
                        $total_pr2=$total_pr2 + $total333;
             } 

            ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="3"
                  style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo $this->lang->line('total_amount'); ?>(₹)

               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total_pr, 2, '.', ''); ?>
               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total_pr1, 2, '.', ''); ?>
               </td>
               <td
                  style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
                  <?php echo number_format((float)$total_pr2, 2, '.', ''); ?>
               </td>
               <td colspan="1"></td>
            </tr>
         </table>
         <ul style="padding:0px;margin:0px;list-style:none;">
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"> <?php echo $this->lang->line('manager'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('president'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
         </ul>
         <ul style="padding:0px;margin:0px;list-style:none;margin-top:20px;">
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"><?php echo $this->lang->line('secretary'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('treasurer'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
         </ul>


      </div>
   </body>
</html>
