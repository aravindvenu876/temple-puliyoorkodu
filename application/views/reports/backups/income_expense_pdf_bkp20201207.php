<?php error_reporting(0); ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INCOME EXPENDITURE ACCOUNT</title>
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
<body style="background: #fafafa;margin: 15px 0;">
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
    </div>
    <div style=" clear: both"></div>
        <p style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
            <?php echo $this->lang->line('income_expense'); ?>
        </p>
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block; margin-top: 5px;">
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px; ">
        <div style="background: #00A7B6;height: 14px;margin: 20px 0px 0px;padding: 10px;">
            <h4 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
                <?php echo $this->lang->line('income'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('cash'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('card'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('mo'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('cheque'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('dd'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('online'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('total'); ?>(₹)</td>
            </tr>
            <?php   
			$i=0;
			$indexStoreArray = array();
            $cashIncome = 0;
            $cardIncome = 0;
            $moIncome = 0;
            $chequeIncome = 0;
            $ddIncome = 0;
            $onlineIncome = 0;
            $ttIncome = 0;
            $cash1=0;
            $amount1=0;
			$annadanamAmount = 0;
			$annedanamCash = 0;
			$annadanamCard = 0;
			$annadanamMo = 0;
			$annadanamDd = 0;
            $annadanamCheque = 0;
            $annadanamOnline = 0;
            $ulsavamLabel = "";
            $ulsavamAmount = 0;
            $ulsavamCash = 0;
            $ulsavamCard = 0;
            $ulsavamMo = 0;
            $annadanamOnline = 0;
            $ulsavamDd = 0;
            $ulsavamCheque = 0;
            $ulsavamOnline = 0;
            foreach($incomeReport as $row){
				if($row->item_section_id =="report167"){
					$annadanamAmount = $row->amount;
					$annedanamCash = $row->cash;
					$annadanamCard = $row->card;
					$annadanamMo = $row->mo;
					$annadanamDd = $row->dd;
                    $annadanamCheque = $row->cheque;
                    $annadanamOnline = $row->online;
				}else{
				$recash=0;
				$remo = 0;
				$recard = 0;
				$redd = 0;
                $recheque = 0;
                $reonline= 0;
                foreach($receiptBookIncome as $key => $row1){
                    if($row->receipt_type==$row1->receipt_type){
						if($row->receipt_type=="Pooja"){
                            if($row->pooja_category_id==$row1->pooja_category_id){
								$recash= $recash + $row1->amount;
								array_push($indexStoreArray,$key);
                            }
                        }
						if($row->receipt_type=="Annadhanam"){
							$recash= $recash + $row1->amount;
							array_push($indexStoreArray,$key);
						}
						if($row->receipt_type=="Prasadam"){
                            if($row->item_category_id==$row1->item_category_id){
								$recash= $recash +$row1->amount;
								array_push($indexStoreArray,$key);
							}
						}
						if($row->receipt_type=="Asset"){
							$recash= $recash + $row1->amount;
						}
						if($row->receipt_type=="Postal"){
							$recash= $recash + $row1->amount;
							array_push($indexStoreArray,$key);
						}
						if($row->receipt_type=="Balithara"){
							$recash= $recash + $row1->amount;
						}
						if($row->receipt_type=="Hall"){
							$recash= $recash + $row1->amount;
						}
						if($row->receipt_type=="Nadavaravu"){
							$recash= $recash + $row1->amount;
						}
						if($row->receipt_type=="Donation"){
							$recash= $recash + $row1->amount;
						}
					}
					if($row->item_section_id == "report171"){
						if($row1->category == "Mattu Varumanam"){
							$recash= $recash + $row1->amount;
							array_push($indexStoreArray,$key);
						}
					}
				}
				if($row->item_section_id == "report171"){
					foreach($mattuvarumanam as $row1) {
						$recash= $recash+ + $row1->cash;
						$recard= $recard+ + $row1->card;
						$remo= $remo+ + $row1->mo;
						$recheque= $recheque+ + $row1->cheque;
                        $redd= $redd+ + $row1->dd;
                        $reonline= $reonline+ + $row1->online;
					}
				}
				if($row->receipt_type == "Annadhanam"){
					$recash= $recash+ $annedanamCash;
					$recard= $recard+ $annadanamCard;
					$remo= $remo+ $annadanamMo;
					$recheque= $recheque+ $annadanamCheque;
                    $redd= $redd+ $annadanamDd;
                    $reonline= $reonline+ $annadanamOnline;
				}
                $cash=$row->cash*$row->count;
                $cash1 = $cash + $recash;
                $cashIncome=$cashIncome+$cash1;
				$card=$row->card*$row->count;
				$card = $card + $recard;
                $cardIncome=$cardIncome+$card;
                $mo=$row->mo*$row->count;
				$mo = $mo + $remo;
                $moIncome=$moIncome+$mo;
                $cheque=$row->cheque*$row->count;
				$cheque = $cheque + $recheque;
                $chequeIncome=$chequeIncome+$cheque;
                $dd=$row->dd*$row->count;
				$dd = $dd + $redd;
                $ddIncome=$ddIncome+$dd;
                $online=$row->online*$row->count;
				$online = $online + $reonline;
                $onlineIncome=$onlineIncome+$online;
                $amount = $row->amount*$row->count;
                $amount1 = $amount + $recash + $recard + $remo + $recheque + $redd + $reonline;
                $ttIncome = $ttIncome + $amount1;
                if($row->temple_id == 1){
                    $pooja_category_id = 44;
                    $donation_category_id = 9;
                }else if($row->temple_id == 2){
                    $pooja_category_id = 34;
                    $donation_category_id = 8;
                }else if($row->temple_id == 3){
                    $pooja_category_id = 40;
                    $donation_category_id = 7;
                }
				if($amount1 != 0){
                    if($row->receipt_type == "Pooja" && $row->pooja_category_id ==$pooja_category_id){
                                                $ulsavamAmount = $ulsavamAmount + $amount1;
                                                $ulsavamCash = $ulsavamCash + $cash1;
                                                $ulsavamCard = $ulsavamCard + $card;
                                                $ulsavamMo = $ulsavamMo + $mo;
                                                $ulsavamDd = $ulsavamDd + $dd;
                                                $ulsavamCheque = $ulsavamCheque + $cheque;
                                                $ulsavamOnline = $ulsavamOnline + $online;
                                                $ulsavamLabel = $row->category;
                                           }else if($row->receipt_type == "Donation" && $row->donation_category_id ==$donation_category_id){
                                                $ulsavamAmount = $ulsavamAmount + $amount1;
                                                $ulsavamCash = $ulsavamCash + $cash1;
                                                $ulsavamCard = $ulsavamCard + $card;
                                                $ulsavamMo = $ulsavamMo + $mo;
                                                $ulsavamDd = $ulsavamDd + $dd;
                                                $ulsavamCheque = $ulsavamCheque + $cheque;
                                                $ulsavamOnline = $ulsavamOnline + $online;
                                                $ulsavamLabel = $row->category;
                                           }else{
					$i++;
					?>
					<tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->category; ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($cash1, 2, '.', ''); ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($card, 2, '.', ''); ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($mo, 2, '.', ''); ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($cheque, 2, '.', ''); ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($dd, 2, '.', ''); ?></td>
                        <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($online, 2, '.', ''); ?></td>
						<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($amount1, 2, '.', ''); ?></td>
					</tr>
                    <?php } } } }  ?>
        <?php if($ulsavamAmount >0){ $i++;?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $ulsavamLabel; ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamCash, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamCard, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamMo, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamCheque, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamDd, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamOnline, 2, '.', ''); ?></td>
            <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($ulsavamAmount, 2, '.', ''); ?></td>
        </tr>
		<?php } ?>
		<?php 	foreach($receiptBookIncome as $key => $row1){
				if(!in_array($key,$indexStoreArray)){
					$i++;
					$cashIncome=$cashIncome + $row1->amount;
					$ttIncome = $ttIncome + $row1->amount;
            ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 9px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo $i; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row1->category; ?>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($row1->amount, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($row1->amount, 2, '.', ''); ?></td>
			</tr>
			<?php 			
				}
			}
			?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="2" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
                <?php echo $this->lang->line('total_amount'); ?>(₹)
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cashIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cardIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($moIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($chequeIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ddIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($onlineIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ttIncome, 2, '.', ''); ?>
                </td>
            </tr>
        </table>
        <?php 
            $totalIncome = $cashIncome + $cardIncome + $moIncome + $chequeIncome + $ddIncome + $onlineIncome;
            $CashIncomeWithoutBank = $cashIncome + $moIncome; 
        ?>
		<table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Journal Entires</caption>
			</caption>
			<?php 
			$totalJournalAmount 		= 0;
			$headId 					= 0;
			$headAmount 				= 0;
			$headName 					= "";
			foreach($journal_entries as $row){
				if($row->type == "To"){ 
					$totalJournalAmount = $totalJournalAmount + $row->credit;
					if($headId == 0){
						$headId 		= $row->sub_head_id;
						$headName 		= $row->head;
						$headAmount 	= $row->credit;
					}else{
						if($headId != $row->sub_head_id){
							?>
							<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
								<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $headName; ?></td>
								<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($headAmount, 2, '.', ''); ?></td>
							</tr>			
							<?php 
							$headId 	= $row->sub_head_id;
							$headName 	= $row->head;
							$headAmount = $row->credit;
						}else{
							$headAmount = $headAmount + $row->credit;
						}
					}
				}
			} 
			?>
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $headName; ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($headAmount, 2, '.', ''); ?></td>
			</tr>	
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($totalJournalAmount, 2, '.', ''); ?></td> 
			</tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">
            <?php  foreach($accountReport as $row){ ?>
                <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                    <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('Withdrawal').'('.$row->bank_eng.'=>'.$this->lang->line('temple').')'; ?></td>
                    <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalWithdrawal, 2, '.', ''); ?></td>
                </tr>
                <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                    <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('petty_cash_withdrawal').'('.$row->bank_eng.')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. number_format($row->pettyCashWithdrawal, 2, '.', ''); ?></td>
               </tr>
            <?php } ?>
            <?php foreach ($bankWithdrawalSplit as $row){ ?>
                <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                    <?php 
                        if($row->type == "PETTY CASH WITHDRAWAL"){
                            $type = "petty_cash_withdrawal";
                            ?>
                            <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line($type)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($row->amount, 2, '.', '') ?></td>
                            <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"></td>
                            <?php
                        }
                    ?>
               </tr>
            <?php } ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"> <?php echo $this->lang->line('total')." ".$this->lang->line('Withdrawal'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($bankWithdrawal, 2, '.', ''); ?></td> 
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"> <?php echo $this->lang->line('total')." FD ".$this->lang->line('varavu'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($total_fd_to_sb['amount'], 2, '.', ''); ?></td> 
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('Income_By_Receipts'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format(($totalIncome), 2, '.', ''); ?></td>
            </tr>
            <?php $totalIncomeAmount = $bankWithdrawal + $totalIncome + $total_fd_to_sb['amount'] + $totalJournalAmount; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format(($totalIncomeAmount), 2, '.', ''); ?></td>
            </tr>
        </table>

        <table style="width: 100%;margin: 20px 0px;">
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
            <?php echo $this->lang->line('opening_balance'); ?></caption>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php  
                $i=1;
                $sum=0;
                $total = $pettyCashOpen; 
            ?>
            <tr style="background: #FFFFFF;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">1</td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;"><?php echo $this->lang->line('petty_cash'); ?></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($pettyCashOpen, 2, '.', ''); ?></td>
            </tr>
            <?php 
                foreach($accountReport as $row){ 
                    $i++;
                    $sum=$row->opening;
                    $total=$total+$sum;
                ?>
                <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->account_no; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->opening; ?></td>
                </tr> 
            <?php }?>
			<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> SB</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format(($total - $pettyCashOpen), 2, '.', ''); ?></td>
            </tr>
        </table>

        <table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                FD <?php echo $this->lang->line('opening_balance'); ?></caption>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php 
                $defaultBankId = 0;
                $defaultBank = "";
                $i = 0;
                $totalSum = 0;
                $bankSum = 0;
                foreach($fdAccountsOpening as $row){
					if($row->st == 1){
						if($i != 0){
							if($defaultBankId != $row->bank_id){ ?>
								<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
									<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
									<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
									<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><b><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</b></td>
									<td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
								</tr> 
							<?php
							$bankSum = 0;
							}
						}
						$defaultBankId = $row->bank_id;
						$defaultBank = $row->bank_eng;
						$i++;
						$sum = $row->amount;
						$totalSum = $totalSum + $row->amount;
						$bankSum = $bankSum + $row->amount;
                ?>
                <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?>FD</td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->account_no; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->amount; ?></td>
                </tr> 
				<?php }} ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> FD</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($totalSum, 2, '.', ''); ?></td>
            </tr>
        </table>


        <div style="background: #9575CD;height: 14px;margin: 20px 0px 0px;padding: 10px;">
        <h4 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
            <?php echo $this->lang->line('expense'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('cash'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('card'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('mo'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('cheque'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('dd'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('online'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('total'); ?>(₹)</td>
            </tr>
            <?php
                $i=0;
                $cashExpense = 0;
                $cardExpense = 0;
                $moExpense = 0;
                $chequeExpense = 0;
                $ddExpense = 0;
                $onlineExpense = 0;
                $ttExpense = 0;
                foreach($expenseReport as $row){
                    $i++;
                    $cash=$row->cash*$row->count;
                    $cashExpense=$cashExpense+$cash;
                    $card=$row->card*$row->count;
                    $cardExpense=$cardExpense+$card;
                    $mo=$row->mo*$row->count;
                    $moExpense=$moExpense+$mo;
                    $cheque=$row->cheque*$row->count;
                    $chequeExpense=$chequeExpense+$cheque;
                    $dd=$row->dd*$row->count;
                    $ddExpense=$ddExpense+$dd;
                    $online=$row->online*$row->count;
                    $onlineExpense=$onlineExpense+$online;
                    $amount = $row->amount*$row->count;
                    $ttExpense = $ttExpense + $amount;
            ?>
                <tr style="background: #FFFFFF;;paddimeera;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->category; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($cash, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($card, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($mo, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($cheque, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($dd, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($online, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:12px"><?php echo number_format($amount, 2, '.', ''); ?></td>
                </tr>
            <?php } ?>
           
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="2" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
                    <?php echo $this->lang->line('total_amount'); ?>(₹)
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cashExpense, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cardExpense, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($moExpense, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($chequeExpense, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ddExpense, 2, '.', ''); ?>
                </td>
                 <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($onlineExpense, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ttExpense, 2, '.', ''); ?>
                </td>
            </tr>
		</table>
		<table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Journal Entires(Debit)</caption>
			</caption>
			<?php 
			$totalJournalAmount 		= 0;
			$headId 					= 0;
			$headAmount 				= 0;
			$headName 					= "";
			foreach($journal_entries as $row){
				if($row->type == "By"){ 
					$totalJournalAmount = $totalJournalAmount + $row->debit;
					if($headId == 0){
						$headId 		= $row->sub_head_id;
						$headName 		= $row->head;
						$headAmount 	= $row->debit;
					}else{
						if($headId != $row->sub_head_id){
							?>
							<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
								<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $headName; ?></td>
								<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($headAmount, 2, '.', ''); ?></td>
							</tr>			
							<?php 
							$headId 	= $row->sub_head_id;
							$headName 	= $row->head;
							$headAmount = $row->debit;
						}else{
							$headAmount = $headAmount + $row->debit;
						}
					}
				}
			} 
			?> 
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $headName; ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($headAmount, 2, '.', ''); ?></td>
			</tr>	
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($totalJournalAmount, 2, '.', ''); ?></td> 
			</tr>
        </table>
		
		<div style="background: #9575CD;height: 14px;margin: 20px 0px 0px;padding: 10px;">
        <h4 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
            <?php echo $this->lang->line('bank')." ".$this->lang->line('Deposit'); ?>
        </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo "SB ".$this->lang->line('Deposit'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;"><?php echo "FD ".$this->lang->line('Deposit'); ?>(₹)</td>
			</tr>
			<?php $ikl = 0;foreach($accountReport as $row){ $ikl++;?>
				<tr style="background: #FFFFFF;;paddimeera;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
					<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $ikl; ?></td>
					<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo $row->bank_eng; ?></td>
					<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalDeposit, 2, '.', ''); ?></td>
					<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalFDDeposit, 2, '.', ''); ?></td>
                </tr>
			<?php } ?> 
			<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="3" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
				<?php echo $this->lang->line('total')." SB ".$this->lang->line('Deposit'); ?>(₹)
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($bankDeposit, 2, '.', ''); ?>
				</td>
			</tr>
			<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="3" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
				<?php echo $this->lang->line('total')." FD ".$this->lang->line('Deposit'); ?>(₹)
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($total_sb_to_fd['amount'], 2, '.', ''); ?>
				</td>
			</tr>
		</table>


        <table style="width: 100%;margin: 20px 0px;">
            <!-- <?php foreach($accountReport as $row){ ?>
                <tr style="background: #F1F1F1;padding: 10px;font-size: 112px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                    <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('Deposit').'('.$this->lang->line('temple').'=>'.$row->bank_eng.')'; ?></td>
                    <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalDeposit, 2, '.', ''); ?></td>
                </tr>
			<?php } ?>
			<tr style="background: #F1F1F1;padding: 10px;font-size: 112px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('Deposit').'(SB=>FD)'; ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($total_sb_to_fd['amount'], 2, '.', ''); ?></td>
			</tr> -->
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('total')." ".$this->lang->line('Deposit') ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($totalBankDeposit, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo  $this->lang->line('Expense_Vouchers');?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format($totalVoucherExpense, 2, '.', ''); ?></td>               
            </tr>
            <?php $pettyCashSpent = $cashExpense + $moExpense; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px;"><?php echo  $this->lang->line('petty_cash_spent')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-weight: bold;'>".number_format($pettyCashSpent, 2, '.', '')."</span>";?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"></td>               
            </tr>
            <?php //$balanceToDeposit = $totalIncome - $bankDeposit; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px;"><?php echo $this->lang->line('Deposit_Balance');?> - <?php echo $to_date."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($balanceToDeposit, 2, '.', '');?></td>           
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"></td>              
            </tr>
            <?php $totalExpenseAmount = $totalBankDeposit + $totalVoucherExpense + $totalJournalAmount; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('total_amount'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: bold;"><?php echo number_format(($totalExpenseAmount), 2, '.', ''); ?></td>
            </tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                <?php echo $this->lang->line('closing_balance') ?>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php  
                $i=1;
                $sum=0;
                $total = $pettyCashOpen; 
            ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">1</td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;"><?php echo $this->lang->line('petty_cash'); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($pettyCashClose, 2, '.', ''); ?></td>
            </tr>
            <?php 
                foreach($accountReport as $row){ 
                    $i++;
                    $sum=$row->closing;
                    $total=$total+$sum;
                ?>
                <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->account_no; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->closing; ?></td>
                </tr> 
            <?php } ?>
			
			<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> SB</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format(($total - $pettyCashOpen), 2, '.', ''); ?></td>
            </tr>
        </table>

        <table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                FD <?php echo $this->lang->line('closing_balance') ?>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php 
                $defaultBankId = 0;
                $defaultBank = "";
                $i = 0;
                $totalSum = 0;
                $bankSum = 0;
                foreach($fdAccountsClosing as $row){
                    if($row->st == 1){
                    if($i != 0){
                        if($defaultBankId != $row->bank_id){ ?>
                            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px;font-weight: bold;"><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</td>
                                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 13px;color: #26272F;text-align: right;padding: 10px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
                            </tr> 
                        <?php
                        $bankSum = 0;
                        }
                    }
                    $defaultBankId = $row->bank_id;
                    $defaultBank = $row->bank_eng;
                    $i++;
                    $sum = $row->amount;
                    $totalSum = $totalSum + $row->amount;
                    $bankSum = $bankSum + $row->amount;
                ?>
                <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?>FD</td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->account_no; ?></td>
                    <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->amount; ?></td>
                </tr> 
            <?php } 
			} ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px;font-weight: bold;"><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-size:14px;font-weight: bold;"><?php echo $this->lang->line('total') ?> FD</td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($totalSum, 2, '.', ''); ?></td>
            </tr>
        </table>

        <ul style="padding:0px;margin:0px;list-style:none;">
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"> <?php echo $this->lang->line('manager'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('president'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
         </ul>
         <ul style="padding:0px;margin:0px;list-style:none;margin-top:20px;">
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"><?php echo $this->lang->line('secretary'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('treasurer'); ?></li>
            <li style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
         </ul>
    </div>
</body>

</html>
