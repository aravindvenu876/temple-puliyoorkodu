<?php

// $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

require_once(APPPATH.'/libraries/tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');



$title = "Chelamattom Temple ";
$pdf->SetTitle("Reports");
$pdf->SetSubject('Reports');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data

$headerTitle = "Chelamattom Temple ";
$headerString = "Income and Expense Report";
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $headerTitle, $headerString);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------


// set font

// $pdf->SetFont('dejavusans', '', 10);
$fontname = TCPDF_FONTS::addTTFfont(APPPATH.'/libraries/tcpdf/fonts/SakalBharati Malayalam_N 9.14 _Ship.ttf', 'SakalBharati Malayalam_N 9.14 _Ship', '', 32);
$pdf->SetFont($fontname, '', 8, '', 'false');


// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
 //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";

$html .= '<h1>Income and Expense  Report From '.$from_date.' To '.$to_date.' </h1>';

$html .= '<table><tr><td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:70%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th width="15%">SL NO</th>';
$html .= '<th width="50%">Item</th>';
$html .= '<th style="text-align:right">Cash(₹)</th>';
$html .= '<th style="text-align:right">Card(₹)</th>';
$html .= '<th style="text-align:right">MO(₹)</th>';
$html .= '<th style="text-align:right">Cheque(₹)</th>';
$html .= '<th style="text-align:right">DD(₹)</th>';
$html .= '</tr>';
$i=0;
$cashIncome = 0;
$cardIncome = 0;
$moIncome = 0;
$chequeIncome = 0;
$ddIncome = 0;
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
    $html .= '<tr style="text-align:left;">';
    $html .= '<td width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->category.'</td>';
    $html .= '<td style="text-align:right">'.number_format($cash, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($card, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($mo, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($cheque, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($dd, 2, '.', '').'</td>';
    $html .= '</tr>';
}
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align:right;border-right:0;'>Total Amount</td>";
$html .= '<td style="text-align:right"><b>'.number_format($cashIncome, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($cardIncome, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($moIncome, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($chequeIncome, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($ddIncome, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= '</table></td>';
$html .= '<td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:70%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th width="15%">SL NO</th>';
$html .= '<th width="50%">Item</th>';
$html .= '<th style="text-align:right">Cash(₹)</th>';
$html .= '<th style="text-align:right">Card(₹)</th>';
$html .= '<th style="text-align:right">MO(₹)</th>';
$html .= '<th style="text-align:right">Cheque(₹)</th>';
$html .= '<th style="text-align:right">DD(₹)</th>';
$html .= '</tr>';
$i=0;
$cashExpense = 0;
$cardExpense = 0;
$moExpense = 0;
$chequeExpense = 0;
$ddExpense = 0;
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
    $html .= '<tr style="text-align:left;">';
    $html .= '<td  width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->category.'</td>';
    $html .= '<td style="text-align:right">'.number_format($cash, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($card, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($mo, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($cheque, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($dd, 2, '.', '').'</td>';
    $html .= '</tr>';
}
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align:right;border-right:0;'>Total Amount</td>";
$html .= '<td style="text-align:right"><b>'.number_format($cashExpense, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($cardExpense, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($moExpense, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($chequeExpense, 2, '.', '').'</b></td>';
$html .= '<td style="text-align:right"><b>'.number_format($ddExpense, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= '</table></td></tr></table>';
$html .= "<br><br>";


$html .= '<table><tr><td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:100%">';
foreach($accountReport as $row){
    $html .= "<tr>";
    $html .= "<td style='text-align:right;border-right:0;'>Withdrawal ($row->bank_eng => Temple)</td>";
    $html .= '<td style="text-align:right"><b>'.number_format($row->totalWithdrawal, 2, '.', '').'</b></td>';
    $html .= '</tr>';
}
$html .= "<tr>";
$html .= "<td style='text-align:right;border-right:0;'>Total Withdrawal</td>";
$html .= '<td style="text-align:right"><b>'.number_format($bankWithdrawal, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= "<tr>";
$html .= "<td style='text-align:right;border-right:0;'>Income By Receipts</td>";
$html .= '<td style="text-align:right"><b>'.number_format($totalReceiptIncome, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= '</table></td>';
$html .= '<td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:100%">';
foreach($accountReport as $row){
    $html .= "<tr>";
    $html .= "<td style='text-align:right;border-right:0;'>Deposit (Temple => $row->bank_eng)</td>";
    $html .= '<td style="text-align:right"><b>'.number_format($row->totalDeposit, 2, '.', '').'</b></td>';
    $html .= '</tr>';
}
$html .= "<tr>";
$html .= "<td style='text-align:right;border-right:0;'>Total Deposit</td>";
$html .= '<td style="text-align:right"><b>'.number_format($bankDeposit, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= "<tr>";
$html .= "<td style='text-align:right;border-right:0;'>Expense From Vouchers</td>";
$html .= '<td style="text-align:right"><b>'.number_format($totalVoucherExpense, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= "<tr style='border:0;' colspan='5'><td colspan='2' style='border:0;'></td></tr>";
$html .= "<tr>";
$html .= "<td style='text-align:right;border-right:0;'>Balance To Deposit as on ".$to_date."</td>";
$balanceToDeposit = $totalReceiptIncome - $bankDeposit;
$html .= '<td style="text-align:right"><b>'.number_format($balanceToDeposit, 2, '.', '').'</b></td>';
$html .= '</tr>';
$html .= '</table></td></tr></table>';
$html .= "<br><br>";

$html .= '<table><tr><td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th width="15%">SL NO</th>';
$html .= '<th width="50%">Item</th>';
$html .= '<th style="text-align:right">Amount(₹)</th>';
$html .= '</tr>';
$i=1;
$sum=0;
$total = $pettyCashOpen;
$html .= "<tr>";
$html .= "<td style='width:30px'>1</td>";
$html .= "<td style='text-align:right'>Petty cash</td>";
$html .= '<td style="text-align:right">'.number_format($pettyCashOpen, 2, '.', '').'</td>';
$html .= "</tr>";

foreach($accountReport as $row){
    $i++;
    $sum=$row->opening;
    $total=$total+$sum;
    $html .= '<tr style="text-align:left;">';
    $html .= '<td width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->bank_eng.'</td>';
    $html .= '<td style="text-align:right">'.$row->opening.'</td>';
    $html .= '</tr>';
}
foreach($fdAccountsOpening as $row){
    $i++;
    $sum=$row->amount;
    $total=$total+$sum;
    $html .= '<tr style="text-align:left;">';
    $html .= '<td width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->bank_eng.'FD</td>';
    $html .= '<td style="text-align:right">'.$row->amount.'</td>';
    $html .= '</tr>';
}
$totalIncomeAmount = $bankWithdrawal + $totalReceiptIncome;
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align:right;border-right:0;'>Total Amount</td>";
$html .= '<td style="text-align:right">'.number_format($totalIncomeAmount, 2, '.', '').'</td></tr>';
$html .= '</table></td>';

$html .= ' <td><table cellspacing="0" cellpadding="4" border="1" style="float:right;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th width="15%">SL NO</th>';
$html .= '<th width="50%">Item</th>';
$html .= '<th style="text-align:right">Amount(₹)</th>';
$html .= '</tr>';
$i=1;
$total=0;
$sum=0;
$total = $pettyCashClose;
$html .= "<tr>";
$html .= "<td style='width:30px'>1</td>";
$html .= "<td style='text-align:right'>Petty cash</td>";
$html .= '<td style="text-align:right">'.number_format($pettyCashClose, 2, '.', '').'</td>';
$html .= "</tr>";
foreach($accountReport as $row){
    $i++;
    $sum=$row->closing;
    $total=$total+$sum;
    $html .= '<tr style="text-align:left;">';
    $html .= '<td width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->bank_eng.'</td>';
    $html .= '<td style="text-align:right">'.$row->closing.'</td>';
    $html .= '</tr>';
}
foreach($fdAccountsClosing as $row){
    $i++;
    $sum=$row->amount;
    $total=$total+$sum;
    $html .= '<tr style="text-align:left;">';
    $html .= '<td width="15%">'.$i.'</td>';
    $html .= '<td width="50%">'.$row->bank_eng.'FD</td>';
    $html .= '<td style="text-align:right">'.$row->amount.'</td>';
    $html .= '</tr>';
}
$totalExpenseAmount = $bankDeposit + $totalVoucherExpense;
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align:right;border-right:0;border:0;'>Total Amount</td>";
$html .= '<td style="text-align:right">'.number_format($totalExpenseAmount, 2, '.', '').'</td></tr>';
$html .= '</table></td></tr></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>