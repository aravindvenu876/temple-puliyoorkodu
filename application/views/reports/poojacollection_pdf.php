<?php require_once(APPPATH.'/language/english/site_lang.php');
require_once(APPPATH.'/language/malayalam/site_lang.php');?><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Pooja Wise Collection Report</title>
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
				<p style="font-family: 'Montserrat', meera;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 12px;font-weight: bold;padding: 0px 10px;">
					<?php echo $this->lang->line('date'); ?> &nbsp;&nbsp; : <span><?php echo $from_date." / ".$to_date; ?></span>
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
         <?php echo $this->lang->line('Pooja_Wise_Collection'); ?>
         </p>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;"/>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;"/>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('pooja_category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('pooja'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->pooja_category_id;
                     $last_category = $row->category_alt;
                  }
                  if($last_id != $row->pooja_category_id){ ?>
                     <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                        <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                        <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                           <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
                        </td>
                     </tr> 
                     <?php 
                  }
                  if($row->count == "0"){
                     $total = $row->amount;
                     ?>
                        <tr>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                              <?php echo $i ?>
                           </td>
                           <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                              <?php echo $row->category; ?>
                           </td>
                           <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                              <?php echo $row->pooja_name; ?> (Receipt Book)
                           </td>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
                           </td>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
                           </td>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                              <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
                           </td>
                        </tr>
                     <?php }else{ ?>
                        <tr>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                              <?php echo $i ?>
                           </td>
                           <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                              <?php echo $row->category_eng; ?>
                           </td>
                           <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                              <?php echo $row->pooja_name_eng; ?>
                           </td>
                           <?php $total = $row->amount; ?>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
                              <?php echo $row->rate; ?>
                           </td>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
                              <?php echo $row->count; ?>
                           </td>
                           <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                              <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
                           </td>
                        </tr>
                     <?php 
                  }
                  $total_amount = $total_amount + $total;
                  $total_category_amount = $total_category_amount + $total;
                  $last_id = $row->pooja_category_id;
               } 
            ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total_amount'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_amount, 2, '.', ''); ?>
               </td>
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
