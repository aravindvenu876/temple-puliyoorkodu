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
            <h1 style="font-family: `Montserrat`, meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold">
                <b><?php echo $this->lang->line('temple_trust'); ?></b>
            </h1>
            <h1 style="font-family: `Montserrat`, meera;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold;">
                <b><?php echo $temple; ?></b>
			</h1>
		</div>
        <div style="width:70%; float: left;">
			<p style="font-family: `Montserrat`, meera;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 12px;font-weight: bold;padding: 0px 10px;">
				<?php echo $this->lang->line('date'); ?> &nbsp;&nbsp; : <span><?php echo $from_date." / ".$to_date; ?></span>
			</p>
		</div>
        <div style="width:120px; float: right; text-align:left;">
            <p style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               	<?php echo $this->lang->line('date'); ?> : <span><?php echo date("d-m-Y"); ?></span>
            </p>
            <p style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;">
               <?php echo $this->lang->line('time'); ?>  : <span><?php echo date("h:i A"); ?></span>
            </p>
        </div>
    </div>
    <div style=" clear: both"></div>
        <p style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
            <?php echo $this->lang->line('income_expense'); ?>
        </p>
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block; margin-top: 5px;">
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px; ">
        <div style="background: #00A7B6;height: 14px;margin: 20px 0px 0px;padding: 10px;">
            <h4 style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
                <?php echo $this->lang->line('income'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('cash'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('card'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('mo'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('cheque'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('dd'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('online'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('total'); ?>(₹)</td>
            </tr>
            <?php   
			$i              = 0;
            $cash_income    = 0;
            $card_income    = 0;
            $mo_income      = 0;
            $dd_income      = 0;
            $cheque_income  = 0;
            $online_income  = 0;
            $total_income   = 0;
            $cash_mo_income = 0;
            foreach($reports as $row){
                if($row->type == 'Income'){
                    $i++;
                    $head_total = $row->cash + $row->card + $row->mo + $row->cheque + $row->dd + $row->online;
                    $cash_income = $cash_income + $row->cash;
                    $card_income = $card_income + $row->card;
                    $mo_income = $mo_income + $row->mo;
                    $dd_income = $dd_income + $row->dd;
                    $cheque_income = $cheque_income + $row->cheque;
                    $online_income = $online_income + $row->online;
                    $total_income = $total_income + $head_total;
                    $cash_mo_income = $cash_mo_income + $row->cash + $row->mo;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->cash.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->card.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->mo.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->cheque.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->dd.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->online.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.number_format($head_total, 2, '.', '').'</td>';
                    echo '</tr>';
                }
			}
			?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="2" style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
                <?php echo $this->lang->line('total_amount'); ?>(₹)
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cash_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($card_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($mo_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cheque_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($dd_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($online_income, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($total_income, 2, '.', ''); ?>
                </td>
            </tr>
        </table>
		<table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Journal Entires
            </caption>
			<?php 
			$totalJournalAmount = 0;
            foreach($reports as $row){
                if($row->type == 'Journal Income'){
                    $totalJournalAmount = $totalJournalAmount + $row->amount;
                    echo '<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">';
                    echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
				}
			} 
			?>
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($totalJournalAmount, 2, '.', ''); ?></td> 
			</tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">
            <?php 
            $total_withdrawal = 0;
            foreach($reports as $row){
                if($row->type == 'Bank Withdrawal'){
                    if($row->petty_flag == 0){
                        $total_withdrawal = $total_withdrawal + $row->amount;
                        echo '<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">';
                        echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$this->lang->line('Withdrawal').'('.$row->head.'=>'.$this->lang->line('temple').')</td>';
                        echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                        echo '</tr>';
                    }else{
                        echo '<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">';
                        echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('petty_cash_withdrawal').'('.$row->head.')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->amount.'</td>';
                        echo '</tr>';
                    }
                }
            }
            ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"> <?php echo $this->lang->line('total')." ".$this->lang->line('Withdrawal'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($total_withdrawal, 2, '.', ''); ?></td> 
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"> <?php echo $this->lang->line('total')." FD ".$this->lang->line('varavu'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($fd_to_sb_amount, 2, '.', ''); ?></td> 
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('Income_By_Receipts'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format(($total_income), 2, '.', ''); ?></td>
            </tr>
            <?php $totalIncomeAmount = $total_withdrawal + $total_income + $fd_to_sb_amount + $totalJournalAmount; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format(($totalIncomeAmount), 2, '.', ''); ?></td>
            </tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
            <?php echo $this->lang->line('opening_balance'); ?></caption>
            </caption>
            <tr style="background: #FFFFFF;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;"><?php echo $this->lang->line('opening_balance').' - '.$from_date ?></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"><?php echo number_format($openingBalanceToDeposit, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;"><?php echo $this->lang->line('petty_cash').' - '.$from_date; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"><?php echo number_format($pettyCashOpen, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php  
            $i      = 0;
            $total  = $pettyCashOpen; 
            foreach($reports as $row){ 
                if($row->type == 'BOB'){
                    $i++;
                    $total  = $total + $row->amount;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->account.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
                }
            }
            ?>
			<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> SB</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format(($total - $pettyCashOpen), 2, '.', ''); ?></td>
            </tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                <?php echo 'FD '.$this->lang->line('opening_balance'); ?></caption>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php 
            $defaultBank    = "";
            $i              = 0;
            $totalSum       = 0;
            $bankSum        = 0; 
            foreach($reports as $row){ 
                if($row->type == 'FDOB'){
                    if($i != 0){
                        if($defaultBank != $row->head){
                            echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><b>'.$this->lang->line('total').' '.$defaultBank.' FD</b></td>';
                            echo '<td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;">'.number_format($bankSum, 2, '.', '').'</td>';
                            echo '</tr>';
                            $bankSum = 0;
                        }
                    }
                    $i++;
                    $defaultBank    = $row->head;
                    $totalSum       = $totalSum + $row->amount;
                    $bankSum        = $bankSum + $row->amount;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.' FD</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->account.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
                }
            }
            ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> FD</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($totalSum, 2, '.', ''); ?></td>
            </tr>
        </table>
        <div style="background: #9575CD;height: 14px;margin: 20px 0px 0px;padding: 10px;">
            <h4 style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
                <?php echo $this->lang->line('expense'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('cash'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('card'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('mo'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('cheque'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('dd'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('online'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('total'); ?>(₹)</td>
            </tr>
            <?php
            $i=0;
            $cash_expense   = 0;
            $card_expense   = 0;
            $mo_expense     = 0;
            $cheque_expense = 0;
            $dd_expense     = 0;
            $online_expense = 0;
            $total_expense  = 0;
            foreach($reports as $row){
                if($row->type == 'Expense'){
                    $i++;
                    $head_total = $row->cash + $row->card + $row->mo + $row->cheque + $row->dd + $row->online;
                    $cash_expense = $cash_expense + $row->cash;
                    $card_expense = $card_expense + $row->card;
                    $mo_expense = $mo_expense + $row->mo;
                    $dd_expense = $dd_expense + $row->dd;
                    $cheque_expense = $cheque_expense + $row->cheque;
                    $online_expense = $online_expense + $row->online;
                    $total_expense = $total_expense + $head_total;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->cash.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->card.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->mo.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->cheque.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->dd.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->online.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.number_format($head_total, 2, '.', '').'</td>';
                    echo '</tr>';
                }
			}
            ?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="2" style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
                    <?php echo $this->lang->line('total_amount'); ?>(₹)
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cash_expense, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($card_expense, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($mo_expense, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($dd_expense, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cheque_expense, 2, '.', ''); ?>
                </td>
                 <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($online_expense, 2, '.', ''); ?>
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($total_expense, 2, '.', ''); ?>
                </td>
            </tr>
		</table>
		<table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Journal Entires(Debit)
			</caption>
            <?php
			$totalJournalAmount = 0;
            foreach($reports as $row){
                if($row->type == 'Journal Expense'){
                    $totalJournalAmount = $totalJournalAmount + $row->amount;
                    echo '<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">';
                    echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
				}
			} 
			?>
			<tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px"><?php echo $this->lang->line('total_amount'); ?></td>
				<td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($totalJournalAmount, 2, '.', ''); ?></td> 
			</tr>
        </table>
		<div style="background: #9575CD;height: 14px;margin: 20px 0px 0px;padding: 10px;">
        <h4 style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
            <?php echo $this->lang->line('bank')." ".$this->lang->line('Deposit'); ?>
        </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo "SB ".$this->lang->line('Deposit'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;"><?php echo "FD ".$this->lang->line('Deposit'); ?>(₹)</td>
			</tr>
            <?php 
            $i = 0;
            $sb_deposit = 0;
            $fd_deposit = 0;
            $totalBankDeposit = 0;
            foreach($reports as $row){
                if($row->type == 'Bank Deposit'){
                    $sb_deposit = $sb_deposit + $row->amount;
                    $fd_deposit = $fd_deposit + $row->amount1;
                    $totalBankDeposit = $totalBankDeposit + $row->amount + $row->amount1;
                    echo '<tr style="background: #FFFFFF;;paddimeera;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px;">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount1.'</td>';
                    echo '</tr>';
                }
            }
            ?>
			<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="3" style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
				<?php echo $this->lang->line('total')." SB ".$this->lang->line('Deposit'); ?>(₹)
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($sb_deposit, 2, '.', ''); ?>
				</td>
			</tr>
			<tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="3" style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
				<?php echo $this->lang->line('total')." FD ".$this->lang->line('Deposit'); ?>(₹)
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($fd_deposit, 2, '.', ''); ?>
				</td>
			</tr>
		</table>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('total')." ".$this->lang->line('Deposit') ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($totalBankDeposit, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px;"><?php echo  $this->lang->line('Expense_Vouchers');?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format($total_expense, 2, '.', ''); ?></td>               
            </tr>
            <?php $pettyCashSpent = $cash_expense + $mo_expense; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px;"><?php echo  $this->lang->line('petty_cash_spent')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-weight: bold;'>".number_format($pettyCashSpent, 2, '.', '')."</span>";?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"></td>               
            </tr>
            <?php $totalExpenseAmount = $totalBankDeposit + $total_expense + $totalJournalAmount; ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px;"><?php echo $this->lang->line('total_amount'); ?>(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;"><?php echo number_format(($totalExpenseAmount), 2, '.', ''); ?></td>
            </tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                <?php echo $this->lang->line('closing_balance') ?>
            </caption>
            <tr style="background: #FFFFFF;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;"><?php echo $this->lang->line('closing_balance').' - '.$to_date ?></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"><?php echo number_format($closingBalanceToDeposit, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;"><?php echo $this->lang->line('petty_cash').' - '.$to_date; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"><?php echo number_format($pettyCashClose, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('item'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php  
            $i      = 0;
            $total  = $pettyCashOpen; 
            foreach($reports as $row){ 
                if($row->type == 'BCB'){
                    $i++;
                    $total = $total + $row->amount;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->account.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
                }
            }
            ?>
			<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><?php echo $this->lang->line('total') ?> SB</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format(($total - $pettyCashOpen), 2, '.', ''); ?></td>
            </tr>
        </table>
        <table style="width: 100%;margin: 20px 0px;">  
            <caption style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                <?php echo 'FD '.$this->lang->line('closing_balance') ?>
            </caption>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('sl'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('bank'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('account'); ?></td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-weight: 500"><?php echo $this->lang->line('amount'); ?></td>
            </tr>
            <?php 
            $defaultBank    = "";
            $i              = 0;
            $totalSum       = 0;
            $bankSum        = 0; 
            foreach($reports as $row){ 
                if($row->type == 'FDCB'){
                    if($i != 0){
                        if($defaultBank != $row->head){
                            echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>';
                            echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px"><b>'.$this->lang->line('total').' '.$defaultBank.' FD</b></td>';
                            echo '<td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;">'.number_format($bankSum, 2, '.', '').'</td>';
                            echo '</tr>';
                            $bankSum = 0;
                        }
                    }
                    $i++;
                    $defaultBank    = $row->head;
                    $totalSum       = $totalSum + $row->amount;
                    $bankSum        = $bankSum + $row->amount;
                    echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$i.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, meera;font-weight: 500;font-size:14px">'.$row->head.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->account.'</td>';
                    echo '<td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;">'.$row->amount.'</td>';
                    echo '</tr>';
                }
            }
            ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px;font-weight: bold;"><?php echo $this->lang->line('total')." ".$defaultBank; ?>FD</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($bankSum, 2, '.', ''); ?></td>
            </tr>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;"></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: `Montserrat`, meera;font-weight: bold;font-size:14px;font-weight: bold;"><?php echo $this->lang->line('total') ?> FD</td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 13px;"><?php echo number_format($totalSum, 2, '.', ''); ?></td>
            </tr>
        </table>
        <ul style="padding:0px;margin:0px;list-style:none;">
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"> <?php echo $this->lang->line('manager'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('president'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"> <?php echo $this->lang->line('signature'); ?></li>
         </ul>
         <ul style="padding:0px;margin:0px;list-style:none;margin-top:20px;">
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;height:35px;"><?php echo $this->lang->line('secretary'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('treasurer'); ?></li>
            <li style="font-family: `Montserrat`, meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;border-bottom: 1px solid #796f6f;width: 20%;disply:inline-block;float:left;margin-left:15px;height:35px;"><?php echo $this->lang->line('signature'); ?></li>
         </ul>
    </div>
</body>
</html>