<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INCOME AND EXPENSE REPORT</title>
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
    </style>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
</head>
<body style="background: #fafafa;margin: 15px 0;">
    <div style="width: 100%; margin: auto;padding: 20px;border: 1px solid #ccc;background: #fff;">
        <div style="width:80%; float: left;">
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
                  <?php echo $this->lang->line('from_date'); ?>:<span><?php echo $from_date; ?></span> 
               </p>
               <p
                  style="font-family: 'Montserrat', meera;font-size: 14px;color: #26272F;letter-spacing: 0.42px;line-height: 12px;font-weight: bold;padding: 0px 10px;">
                  <?php echo $this->lang->line('to_date'); ?> &nbsp;&nbsp; : <span><?php echo $to_date; ?></span>
               </p>
            </div>
        </div>
        <div style="width:120px; float: right; text-align:left;">
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
         <?php echo $this->lang->line('income_expense'); ?>
         </p>
       
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block; margin-top: 5px;">
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px; ">
  <div style="width:50%;float:left;display:inline-block">
        <div style="background: #00A7B6;height: 14px;margin: 20px 0px 0px;padding: 10px;">
        <h4 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
            <?php echo $this->lang->line('income'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">SI</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Item</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Cash(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Card(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">MO(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Cheque(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">DD(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Total(₹)</td>
            </tr>
            <?php   
            $i=0;
            $cashIncome = 0;
            $cardIncome = 0;
            $moIncome = 0;
            $chequeIncome = 0;
            $ddIncome = 0;
            $ttIncome = 0;
            foreach($incomeReport as $row){
                $i++;
                $cash=$row->cash*$row->count;
                $cashIncome=$cashIncome+$cash;
                $card=$row->card*$row->count;
                $cardIncome=$cardIncome+$card;
                $mo=$row->mo*$row->count;
                $moIncome=$moIncome+$mo;
                $cheque=$row->cheque*$row->count;
                $chequeIncome=$chequeIncome+$cheque;
                $dd=$row->dd*$row->count;
                $ddIncome=$ddIncome+$dd;
                $amount = $row->amount*$row->count;
                $ttIncome = $ttIncome + $amount;
                ?>
                <tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:34px"><?php echo $i; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:36px"><?php echo $row->category; ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($cash, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($card, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($mo, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($cheque, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($dd, 2, '.', ''); ?></td>
                    <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($ttIncome, 2, '.', ''); ?></td>
                </tr>
            <?php } 
            $i++;
            $cashIncome=$cashIncome+$receiptBookIncome;
            $ttIncome = $ttIncome + $receiptBookIncome;
            ?>
            <tr style="background: #FFFFFF;;padding: 10px;font-size: 9px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo $i; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:36px">Receipt Book Income</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($receiptBookIncome, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px">0.00</td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($receiptBookIncome, 2, '.', ''); ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 40px;color: #26272F;text-align: left;padding: 10px;">
                <?php echo $this->lang->line('total'); ?>(₹)
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: left;padding: 10px;">
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cashIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cardIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($moIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($chequeIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ddIncome, 2, '.', ''); ?>
                </td>
                <td style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ttIncome, 2, '.', ''); ?>
                </td>
            </tr>
        </table>
        </div>
        <div style="width:50%;float:left;display:inline-block">
        <div style="background: #9575CD;height: 14px;margin: 20px 0px 0px;padding: 10px;">
        <h4 style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 18px;color: #FFFFFF;letter-spacing: 0.45px;text-align: left;text-transform: uppercase;">
            <?php echo $this->lang->line('expense'); ?>
            </h4>
        </div>
        <table style="width: 100%;margin: 20px 0px;">
          
            <tr
                style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">SI</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Item</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Cash(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Card(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">MO(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Cheque(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">DD(₹)</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 40px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;">Total(₹)</td>
            </tr>
            <?php
                        $i=0;
            $cashExpense = 0;
            $cardExpense = 0;
            $moExpense = 0;
            $chequeExpense = 0;
            $ddExpense = 0;
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
                $amount = $row->amount*$row->count;
                $ttExpense = $ttExpense + $amount;
                 ?>
            <tr
                style="background: #FFFFFF;;paddimeera;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;font-size:34px"><?php echo $i; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:36px"><?php echo $row->category; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($cash, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($card, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($mo, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($cheque, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($dd, 2, '.', ''); ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: 500;font-size:34px"><?php echo number_format($amount, 2, '.', ''); ?></td>
            </tr>
            <?php } ?>
           
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td 
                    style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 40px;color: #26272F;text-align: left;padding: 10px;">
                    <?php echo $this->lang->line('total'); ?>(₹)</td>
                    <td 
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size:40px;color: #26272F;text-align: left;padding: 10px;">
                    </td>
                <td
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size:40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cashExpense, 2, '.', ''); ?></td>
                    <td
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($cardExpense, 2, '.', ''); ?></td>
                    <td 
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($moExpense, 2, '.', ''); ?></td>
                    <td 
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($chequeExpense, 2, '.', ''); ?></td>
                    <td 
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($ddExpense, 2, '.', ''); ?></td>
                    <td 
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 40px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($amount, 2, '.', ''); ?></td>
            </tr>
        </table>
</div>
<div style="width:50%;float:left;display:inline-block">
        <?php $totalIncome = $cashIncome + $cardIncome + $moIncome + $chequeIncome + $ddIncome; ?>
        <table style="width: 100%;margin: 20px 0px;">
            <?php  foreach($accountReport as $row){ ?>
                <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                    <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 10px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo 'Withdrawal('.$row->bank_eng.'=>Temple)'; ?></td>
                    <td style="padding: 10px;background: #F1F1F1;padding: 12px;font-size: 10px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalWithdrawal, 2, '.', ''); ?></td>
               </tr>
            <?php } ?>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Total Withdrawal</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($bankWithdrawal, 2, '.', ''); ?></td> 
            </tr>
            <tr style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Income By Receipts</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format(($totalIncome), 2, '.', ''); ?></td>
            </tr>
        </table>
</div>
<div style="width:50%;float:left;display:inline-block">
<table style="width: 100%;margin: 20px 0px;">
            
            <?php  foreach($accountReport as $row){ ?>
          <tr
              style="background: #F1F1F1;padding: 10px;font-size: 112px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo 'Deposit('.$row->bank_eng.'=>Temple)'; ?></td>
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($row->totalDeposit, 2, '.', ''); ?></td>
             
          </tr><?php } ?>
          <tr
              style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Total Deposit</td>
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($bankDeposit, 2, '.', ''); ?></td>
             
          </tr>
          <tr
              style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Expense From Vouchers</td>
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($totalVoucherExpense, 2, '.', ''); ?></td>
             
          </tr>
          <tr
              style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Balance To Deposit as on <?php echo $to_date;?></td>
              <?php $balanceToDeposit = $totalReceiptIncome - $bankDeposit; ?>
              <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($balanceToDeposit, 2, '.', ''); ?></td>
             
          </tr>
      </table>
</div>
<div style="width:50%;float:left;display:inline-block">

        <table style="width: 100%;margin: 20px 0px;">
            <caption
                style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Bank opening balance</caption>
            <tr
                style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">SI</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">Item</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">Amount</td>
            </tr>
           <?php  $i=0;
                  $sum=0;
                  $total = $pettyCashOpen; ?>
            <tr
                style="background: #FFFFFF;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">1</td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Petty Cash</td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($pettyCashOpen, 2, '.', ''); ?></td>
            </tr>
          
            <?php foreach($accountReport as $row){ 
                  $i++;
                $sum=$row->opening;
                $total=$total+$sum;?>
            <tr
                style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?></td>
                <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->opening; ?></td>
            </tr> <?php }?>
           
           <?php foreach($fdAccountsOpening as $row){
                $i++;
                $sum=$row->amount;
                $total=$total+$sum;?>
               <tr
               style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
               <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
               <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?>FD</td>
               <td style="padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->amount; ?></td>
           </tr> <?php } $totalIncomeAmount = $bankWithdrawal + $totalIncome;?>

            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
            <td colspan="1" style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
            <?php echo $this->lang->line('total')." ".$this->lang->line('income'); ?></td>
                <td colspan="2"
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($totalIncomeAmount, 2, '.', ''); ?></td>
            </tr>
        </table>

</div>
  
<div style="width:50%;float:left;display:inline-block">

        <table style="width: 100%;margin: 20px 0px;">
            <caption
                style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #212121;letter-spacing: 0.37px;text-align: center;margin-bottom: 15px;text-transform: uppercase">
                Bank Closing balance</caption>
            <tr
                style="background: #F1F1F1;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">SI</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">Item</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: 'Montserrat', meera;font-weight: bold;font-weight: 500">Amount</td>
            </tr>
           <?php  $i=1;
                  $sum=0;
                  $total = $pettyCashOpen; ?>
            <tr
                style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">1</td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">Petty Cash</td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo number_format($pettyCashClose, 2, '.', ''); ?></td>
            </tr>
          
            <?php foreach($accountReport as $row){ 
                  $i++;
                  $sum=$row->closing;
                  $total=$total+$sum;?>
            <tr
                style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?></td>
                <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->closing; ?></td>
            </tr> <?php }?>
           
           <?php foreach($fdAccountsClosing as $row){
                $i++;
                $sum=$row->amount;
                $total=$total+$sum;?>
               <tr
               style="background: #FFFFFF;;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;">
               <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $i; ?></td>
               <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: left;font-family: 'Montserrat', meera;font-weight: 500;font-size:14px"><?php echo $row->bank_eng; ?></td>
               <td style="padding: 10px;padding: 10px;font-size: 12px;color: #26272F;text-align: right;font-family: 'Montserrat', sans-serif;font-weight: 500;"><?php echo $row->amount; ?></td>
           </tr> <?php } $totalExpenseAmount = $bankDeposit + $totalVoucherExpense;?>

            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="1"
                    style="font-family: 'Montserrat', meera;font-weight: bold;font-size: 16px;color: #26272F;text-align: left;padding: 10px;">
                    <?php echo $this->lang->line('total')." ".$this->lang->line('expense'); ?></td>
                <td colspan="2"
                    style="font-family: 'Montserrat', sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($totalExpenseAmount, 2, '.', ''); ?></td>
            </tr>
        </table>
</div>
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