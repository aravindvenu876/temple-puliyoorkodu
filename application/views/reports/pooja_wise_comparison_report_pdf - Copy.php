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
      <div style="width: 100%; margin: auto;padding: 20px;border: 1px solid #ccc;background: #fff;">
         <div style="width:70%; float: left;">
            <div style="float: left;width:40px;">
               <img src="<?php echo base_url();?>assets/images/logo.png" style="display: inline-block;width: 40px;">
            </div>

            <div style="margin-left:45px;">
            <h1
                  style="font-family: 'Montserrat', meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: left;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold">
                <b>  <?php echo $this->lang->line('temple_trust'); ?> </b>
               </h1>
               <h1
                  style="font-family: 'Montserrat', meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: left;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold;">
                <b>  <?php echo $temple; ?> </b>
               </h1>
               <p
                  style="font-family: 'Montserrat', meera;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 12px;font-weight: bold;padding: 0px 10px;margin-bottom: 3px;">
                  <?php echo $this->lang->line('year')."&".$this->lang->line('month'); ?>:<span><?php echo $current ?></span> 
               </p>
              
            </div>

         
         </div>
         <div style="width:130px; float: right; text-align:left;">
             <p
               style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               <?php echo $this->lang->line('date'); ?> : <span><?php echo date("d-m-Y"); ?></span>
            </p>
            <p
               style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;">
               <?php echo $this->lang->line('time'); ?>  : <span><?php echo date("h:i a"); ?></span>
            </p>
         </div>
         <div style=" clear: both"></div>
         <p
         style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('pooja_wise_comparison_reports'); ?>
         </p>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;">
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;">
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
            foreach($poojas as $row){
                $i++;
                $rate1 = "0.00";
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
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$rate1, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$rate2, 2, '.', ''); ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px">
               <?php echo number_format((float)$rate3, 2, '.', ''); ?>
               </td>
            </tr>
            <?php       $total=$total + $rate1;
                        $total1=$total1 + $rate2;
                        $total2=$total2 + $rate3;} ?>
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