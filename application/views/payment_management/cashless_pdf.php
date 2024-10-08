<?php require_once(APPPATH.'/language/english/site_lang.php');
require_once(APPPATH.'/language/malayalam/site_lang.php');?><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Pooja Report</title>
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
         <div style="width:50%; float: left;">
            <div style="float: left;width:40px;">
               <img src="<?php echo base_url();?>assets/images/logo.png" style="display: inline-block;width: 40px;">
            </div>
            <div style="margin-left:45px;">
               <h1
                  style="font-family: 'Montserrat', sans-serif;font-size: 11px;color: #26272F;letter-spacing: 0.41px;text-align: left;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;">
                  <?php echo $temple; ?>
               </h1>
            </div>
         </div>
         <div style="width:130px; float: right; text-align:left;">
            <p
               style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 10px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               Date : <span><?php echo date("d-m-Y"); ?></span>
            </p>
            <p
               style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 10px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;">
               Time : <span><?php echo date("h:i a"); ?></span>
            </p>
            </div>
            <div style=" clear: both"></div>
            <p style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 11px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
                Chelamattom Sreekrishnaswami Devasvom Trust Cheque Received Report
            </p>
            <hr style="width: 134px;height: 1px;margin: auto;background:#979797; margin-top: 5px;">
            <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px;">
            <table style="width: 100%;margin: 20px 0px;">
                <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat';font-weight: bold;">
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">SI</td>
                    <?php if($type == "DD"){ ?>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">DD NO</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;">AMOUNT</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">RECEIVED DATE</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">DD DATE</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">DD STATUS</td>
                    <?php }else{ ?>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">CHEQUE NO</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: right;padding: 10px;">AMOUNT</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">RECEIVED DATE</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">CHEQUE DATE</td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">CHEQUE STATUS</td>
                    <?php } ?>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">RECEIPT NUMBER</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">NAME</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">PHONE</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">BANK</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">RECEIVED AT</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">PROCESSED DATE</td>
                    <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">REMARKS</td>
                </tr>
                <?php    
                $i=0;
                foreach($report as $row){
                    $i++; 
                    ?>
                    <tr>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $i ?>
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $row->cheque_no; ?>
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: right;padding: 10px;">
                            <?php echo $row->amount; ?>
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo date('d-m-Y',strtotime($row->created_on)); ?> 
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo date('d-m-Y',strtotime($row->date)); ?> 
                        </td>
                        <td style="font-family: meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $row->status; ?>
                        </td>
                        <?php if($row->section == "RECEIPT"){ ?>
                            <?php $receipt = $this->db->select('receipt_no')->where('id',$row->receip_id)->get('receipt')->row_array();?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                                <?php echo $receipt['receipt_no']; ?>
                            </td>
                        <?php }else{ ?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                                <?php echo $row->receip_id; ?>
                            </td>
                        <?php } ?>
                        <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $row->name; ?>
                        </td>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $row->phone; ?>
                        </td>
                        <td style="font-family: 'Montserrat', meera;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php   $BankData = $this->General_Model->get_bank_data($row->bank);
                             if($this->session->userdata('language')== 1){
                                echo $BankData['bank_eng']; 
                             }else{
                                echo $BankData['bank_alt']; 

                             }?>
                        </td>
                        <?php if($row->section == "RECEIPT"){ ?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            COUNTER
                            </td>
                        <?php }else{ ?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            MANAGEMENT
                            </td>
                        <?php } ?>
                        <?php if($row->processed_date == ""){ ?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            </td>
                        <?php }else{ ?>
                            <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo date('d M Y',strtotime($row->processed_date)); ?>
                            </td>
                        <?php } ?>
                        <td style="font-family: 'Montserrat', sans-serif;font-weight: 500;font-size: 9px;color: #26272F;text-align: left;padding: 10px;">
                            <?php echo $row->remarks; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <ul style="padding:0px;margin:0px;list-style:none;">
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;">Manager</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">Signature</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">President</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">Signature</li>
         </ul>
         <ul style="padding:0px;margin:0px;list-style:none;margin-top:20px;">
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;">Secretary</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">Signature</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">Treasurer</li>
            <li style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 9px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;">Signature</li>
         </ul>
      </div>
   </body>
</html>