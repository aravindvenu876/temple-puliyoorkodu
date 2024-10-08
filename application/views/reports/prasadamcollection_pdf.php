<?php error_reporting(0); require_once(APPPATH.'/language/english/site_lang.php');
   require_once(APPPATH.'/language/malayalam/site_lang.php');?><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title><?php echo $this->lang->line('cat_wise_income_report'); ?></title>
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
         <p style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
            <?php echo $this->lang->line('cat_wise_income_report'); ?>
         </p>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;"/>
         <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;"/>
         <?php if($type==""){ ?>
         	<!-- Prasadam start -->
         	<h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
					 <?php echo $this->lang->line('prasadam'); ?>
				</h3>
         	<table style="width: 100%;margin: 20px 0px;">
					<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('sl'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('category'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('item'); ?> </td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('rate'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('quantity'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('total'); ?></td>
					</tr>
            	<?php   
						$total_amount =0;$i=0;  $last_id = 0; $last_category = 0;
						$total_category_amount = 0;
						foreach($report0 as $row){   
							$i++;
							if($i == 1){
								$last_id = $row->item_category_id;
								$last_category = $row->category;
							}
                  	if($last_id != $row->item_category_id){ ?>
            				<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
									<td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
										<span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
										<?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
									</td>
            				</tr>
            			<?php
             				$total_category_amount=0;
            			}
               		if($row->count == "0"){
                  		$totalr = $row->amount;
                  		?>
            				<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
										<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
									</td>
            				</tr>
            			<?php }else {
								$totalr = $row->amount; ?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->name_eng; ?>
									</td>
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
							$total_amount = $total_amount + $totalr;
							$total_category_amount = $total_category_amount + $totalr;
							$last_id = $row->item_category_id;
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
				<!-- Main Temple pooja -->
				<h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
					<?php echo $this->lang->line('pooja'); ?>
				</h3>
         	<table style="width: 100%;margin: 20px 0px;">
					<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('sl'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('category'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('item'); ?> </td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('rate'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('quantity'); ?></td>
						<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('total'); ?></td>
					</tr>
            	<?php   
						$total_amount =0;
						$i=0;  
						$last_id = 0; 
						$last_category = 0;
						$total_category_amount = 0;
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
								$total_category_amount = 0;  
							}
              			if($row->count == "0"){
                  		$totalr = $row->amount;
                  		?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
										<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
									</td>
								</tr>
            			<?php }else{    
								$totalr = $row->amount; ?>
            				<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->name; ?>
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
            			<?php }
               		$total_amount = $total_amount + $totalr;
							$total_category_amount = $total_category_amount + $totalr;
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
         	<?php if($this->session->userdata('temple')==1){?>
					
					<h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         		<?php echo $this->lang->line('chovazhchakavu'); ?></h3>
					<table style="width: 100%;margin: 20px 0px;">
						<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
						</tr>
            		<?php   
               	$total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
               	foreach($report_1 as $row){   
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
								$total_category_amount = 0;  
							}
               		if($row->count == "0"){
                  		$totalr = $row->amount;
                  		?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
										<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
									</td>
								</tr>
           				<?php }else{    
								$totalr = $row->amount; ?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->name; ?>
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
							$total_amount = $total_amount + $totalr;
							$total_category_amount = $total_category_amount + $totalr;
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
                   
					<h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
					<?php echo $this->lang->line('mathampilli'); ?></h3>
					<table style="width: 100%;margin: 20px 0px;">
						<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
						</tr>
						<?php   
						$total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
						foreach($report_2 as $row){   
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
								$total_category_amount = 0;  
							}
							if($row->count == "0"){
								$totalr = $row->amount;
								?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
									</td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
										<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
									</td>
								</tr>
							<?php }else{    
								$totalr = $row->amount; ?>
								<tr>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
										<?php echo $i ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->category; ?>
									</td>
									<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
										<?php echo $row->name; ?>
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
							$total_amount = $total_amount + $totalr;
							$total_category_amount = $total_category_amount + $totalr;
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
				<?php } ?>

         
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
				<?php echo $this->lang->line('asset'); ?>
			</h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
					<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('sl'); ?></td>
					<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('category'); ?></td>
					<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('quantity'); ?></td>
					<td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
					$total_amount =0;
					$i=0; 
               foreach($report1 as $row){   
						$i++;
						?>
						<tr>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
								<?php echo $i ?>
							</td>
							<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
								<?php echo $row->category; ?>
							</td>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
								<?php echo $row->total_quantity; ?>
							</td>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
								<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
							</td>
            		</tr>
            		<?php $total_amount = $total_amount + $row->amount;
               } 
            ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="3" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total_amount'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_amount, 2, '.', ''); ?>
               </td>
            </tr>
			</table>
			
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('postal'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         	<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('postal'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
				$total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
				foreach($report2 as $row){   
					$i++;
					if($i == 1){
						$last_id = $row->pooja_master_id;
						$last_category = $row->category;
					}
					if($last_id != $row->pooja_master_id){ ?>
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
								(<?php echo $this->lang->line('receipt_book'); ?>)
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
								<?php echo $row->category; ?>
							</td>
							<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
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
					$last_id = $row->pooja_master_id;
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
			
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
				<?php echo $this->lang->line('hall'); ?>
			</h3>
         <table style="width: 100%;margin: 20px 0px;">
         	<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
				$total_amount =0;
				$i=0;
				foreach($report_hall as $row){   
					$i++; ?>
					<tr>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
							<?php echo $i ?>
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
							<?php echo $row->category; ?>
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
							<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
						</td>
					</tr>
					<?php $total_amount = $total_amount + $row->amount;
				} 
				?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="2" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total_amount'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_amount, 2, '.', ''); ?>
               </td>
            </tr>
			</table>
			
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('donation'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         	<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
				$total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
				foreach($report_donation as $row){   
					$i++;
					if($i == 1){
						$last_id = $row->donation_id;
					} 
					if($last_id != $row->donation_id){ ?>
						<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
							<td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
								<span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
							</td>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
								<?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
							</td>
						</tr>
            		<?php   
						$total_category_amount=0;
					}?>
					<tr>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
							<?php echo $i ?>
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
							<?php echo $row->category_eng; ?>
						</td>
						<?php $totalr = $row->amount; ?>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
							<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
						</td>
					</tr>
            	<?php 
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->donation_id;
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
			
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('annadanam'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
				$total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
				foreach($report_ann as $row){   
					$i++;
					if($i == 1){
						$last_id = $row->id;
					} 
					if($last_id != $row->id){ ?>
						<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
							<td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
								<span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
							</td>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
								<?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
							</td>
						</tr>
						<?php   
						$total_category_amount=0; 
					}?>
					<tr>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
							<?php echo $i ?>
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
						</td>
						<?php 
						if($row->count == 1){
							$book=$this->lang->line('annadhanam')." (".$this->lang->line('receipt_book').")";
						}else{
							if($row->booked_type=='ANNADHANAM'){
								$book=$this->lang->line('fixed_annadhanam');
							}else{
								$book=$this->lang->line('normal_annadhanam');
							} 
						}?>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
							<?php echo $book; ?>
						</td>
						<?php $totalr = $row->amount; ?>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
							<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
						</td>
					</tr>
					<?php 
					$total_amount = $total_amount + $totalr;
					$total_category_amount = $total_category_amount + $totalr;
					$last_id = $row->id;
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

         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('balithara'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         	<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
				$total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
				foreach($report_bali as $row){   
					$i++;
					if($i == 1){
						$last_id = $row->balithara_id;
					} 
					if($last_id != $row->balithara_id){ ?>
            		<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
							<td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
								<span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
							</td>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
								<?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
							</td>
						</tr>
            		<?php   
						 $total_category_amount=0;  
					}?>
					<tr>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
							<?php echo $i ?>
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
						</td>
						<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
							<?php echo $row->name; ?>
						</td>
						<?php $totalr = $row->amount; ?>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
						</td>
						<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
							<?php echo number_format((float)$row->amount, 2, '.', ''); ?>
						</td>
					</tr>
            	<?php 
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->balithara_id;
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
			
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
				<?php echo $this->lang->line('mattuvarumanam'); ?>
			</h3>
         <table style="width: 100%;margin: 20px 0px;">
         	<tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"><?php echo $this->lang->line('total'); ?></td>
            </tr>
         	<?php    
				$total_amount =0; 
				$i=0;
				foreach($mattu_in as $row){
					if($row->amount != 0){
						$i++;
						?>
						<tr>
							<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;"><?php echo $i ?></td>
							<td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $row->category; ?></td>              
							<td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;"><?php echo number_format((float)$row->amount, 2, '.', ''); ?></td>
						</tr>
						<?php 
						$total_amount = $total_amount + $row->amount;
					}
				}
            ?>          
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="2" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total_amount'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_amount, 2, '.', ''); ?>
               </td>
            </tr>
			</table>

		<?php } ?>

        <?php 
           if($type=='Prasadam'){  ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
          <?php echo $this->lang->line('prasadam'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
              $total_amount =0;$i=0;  $last_id = 0; $last_category = 0;
              $total_category_amount = 0;
               foreach($report0 as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->item_category_id;
                     $last_category = $row->category;
                  }
                 // $total_category_amount=$total_category_amount;
                  if($last_id != $row->item_category_id){ ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php
              $total_category_amount=0;
            }
               if($row->count == "0"){
                  $totalr = $row->amount;
                  ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php }else{ $totalr = $row->amount; ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->name_eng; ?>
               </td>
               <?php  ?>
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
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->item_category_id;
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
            <?php }  ?>
         <!-- prasadam end -->
         <!-- pooja start -->
         <?php if($type=='Pooja'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('pooja'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
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
             $total_category_amount = 0;  }
               if($row->count == "0"){
                  $totalr = $row->amount;
                  ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php }else{    $totalr = $row->amount; ?>
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
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
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
         <?php if($this->session->userdata('temple')==1){?>
           
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('chovazhchakavu'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_1 as $row){   
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
             $total_category_amount = 0;  }
               if($row->count == "0"){
                  $totalr = $row->amount;
                  ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php }else{    $totalr = $row->amount; ?>
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
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
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
           
          
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('mathampilli'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('item'); ?> </td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('rate'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_2 as $row){   
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
             $total_category_amount = 0;  }
               if($row->count == "0"){
                  $totalr = $row->amount;
                  ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->pooja_name; ?> (<?php echo $this->lang->line('receipt_book'); ?>)
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">                         
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">  
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php }else{    $totalr = $row->amount; ?>
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
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
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
         <?php } } ?>
             <!-- asset Start -->
             <?php if($type=='Asset'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
       
         <?php echo $this->lang->line('asset'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
                  <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;  $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report1 as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->asset_master_id;
                     $last_category = $row->category_alt;
                  }
               
                 $totalr = $row->amount;
                 if($row->temple_id==2){
                  $temple="(ചൊവ്വാഴ്‌ചക്കാവ്)" ;
                 }else if($row->temple_id==3){
                  $temple="(മാതംപിള്ളി)";
                 }else{ $temple="";} ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category_eng.$temple; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                 
               </td>
               <?php $totalr = $row->amount - $row->discount; ?>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
                
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo $row->count; ?>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$totalr, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
               
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->asset_category_id;
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
            <?php }  ?>
         <!-- asset end -->
         <!-- postal start -->
         <?php if($type=='Postal'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('postal'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('postal'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('quantity'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report2 as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->pooja_master_id;
                     $last_category = $row->category;
                  }
                  if($last_id != $row->pooja_master_id){ ?>
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
                  (<?php echo $this->lang->line('receipt_book'); ?>)
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
                  <?php echo $row->category; ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
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
               $last_id = $row->pooja_master_id;
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
            <?php } ?>
         <!-- postal end -->
         <!-- hall start -->
         <?php if($type=='Hall'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
              <?php echo $this->lang->line('hall'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_hall as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->id;
                   //  $last_category = $row->category_alt;
                  } 
                   if($last_id != $row->id){ ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php   
               }?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->name_eng; ?>
               </td>
               <?php $total = $row->amount; ?>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
               $total_amount = $total_amount + $total;
               $total_category_amount = $total_category_amount + $total;
               $last_id = $row->id;
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
            <?php } ?>
         <!-- hall end -->
         <!-- donation start -->
         <?php if($type=='Donation'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('donation'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_donation as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->donation_id;
                   //  $last_category = $row->category_alt;
                  } 
                   if($last_id != $row->donation_id){ ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php   
               $total_category_amount=0;}?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->category_eng; ?>
               </td>
               <?php $totalr = $row->amount; ?>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->donation_id;
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
            <?php } ?>
         <!-- Donation end -->
         <!-- Annandam start -->
         <?php if($type=='Annadhanam'){ ?>
         <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo $this->lang->line('annadanam'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
            <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_ann as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->id;
                   //  $last_category = $row->category_alt;
                  } 
                   if($last_id != $row->id){ ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php   
              $total_category_amount=0; }?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
               </td>
               <?php 
					if($row->count == 1){
						$book=$this->lang->line('annadhanam')." (".$this->lang->line('receipt_book').")";
					}else{
						if($row->booked_type=='ANNADHANAM'){
							$book=$this->lang->line('fixed_annadhanam');
						}else{
							$book=$this->lang->line('normal_annadhanam');
						} 
					}?>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $book; ?>
               </td>
               <?php $totalr = $row->amount; ?>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->id;
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
            <?php } ?>
         <!-- Annanadam end -->

        
          <!-- balithara start -->
          <?php if($type=='Balithara'){ ?>
          <h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
          <?php echo $this->lang->line('balithara'); ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('category'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;">  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
            <?php   
               $total_amount =0;$i=0;   $total=0; $last_id = 0; $last_category = 0;$total_category_amount = 0;
               foreach($report_bali as $row){   
                  $i++;
                  if($i == 1){
                     $last_id = $row->balithara_id;
                   //  $last_category = $row->category_alt;
                  } 
                   if($last_id != $row->balithara_id){ ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:10px;">
                  <?php echo number_format((float)$total_category_amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php   
             $total_category_amount=0;  }?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;">
                  <?php echo $i ?>
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
               </td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;">
                  <?php echo $row->name; ?>
               </td>
               <?php $totalr = $row->amount; ?>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:13px;">
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
               $total_amount = $total_amount + $totalr;
               $total_category_amount = $total_category_amount + $totalr;
               $last_id = $row->balithara_id;
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
            <?php } ?>
         <!-- Balithara end -->
         <!-- Income start -->
         <?php if($type=='Mattu Varumanam'){ ?>
				<h3 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16.5px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
         <?php echo "Mattu Varumanam"; ?></h3>
         <table style="width: 100%;margin: 20px 0px;">
         <tr style= "background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('sl'); ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $this->lang->line('category'); ?>  </td>
               <td style="font-family: 'Montserrat', meera;font-weight: bold;color: #26272F;text-align: right;padding: 10px;font-size:14px;"> <?php echo $this->lang->line('total'); ?></td>
            </tr>
         <?php    
          	$total_amount =0; $i=0;
            foreach($mattu_in as $row){
               $i++;
            ?>
            <tr>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:12px;"><?php echo $i ?></td>
               <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;font-size:14px;"><?php echo $row->category; ?></td>
               
               <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:13px;">
                  <?php echo number_format((float)$row->amount, 2, '.', ''); ?>
               </td>
            </tr>
            <?php 
					$total_amount = $total_amount + $row->amount;
				}
               ?>
           
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
               <td colspan="5" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:14px;">
                  <span style="padding:0 30px;"><?php echo $this->lang->line('total_amount'); ?></span>
               </td>
               <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;font-size:12px;">
                  <?php echo number_format((float)$total_amount, 2, '.', ''); ?>
               </td>
            </tr>
         </table>
      	<?php  }?>

            
         <!-- Income end -->
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
