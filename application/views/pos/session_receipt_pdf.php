<?php

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');
$title = "SESSION NO ".$session['id'] ." FOR COUNTER ".$session['counter_no'];
$pdf->SetTitle($title);
$pdf->SetSubject('Payslip');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$headerTitle = "Session Receipts";
$headerString = "Session Id ".$session['id'].", Counter ".$session['counter_no']." Receipts \nOperated By ".$session['username']."\nBy $temple";
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
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";
// $html .= '<h1>'.$staff['staff']['name'].' '.$salary['processing_time'].' Payslip</h1>';
// $html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;">';
// $html .= '<tr style="text-align:left;">';
// $html .= '<th>Staff Name</th><td><b>'.$staff['staff']['name'].'</b></td>';
// $html .= '<th>Staff ID</th><td><b>'.$staff['staff']['staff_id'].'</b></td>';
// $html .= '</tr><tr style="text-align:left;">';
// $html .= '<th>Designation</th><td><b>'.$staff['staff']['designation_eng'].'</b></td>';
// $html .= '<th>Salary Scheme</th><td><b>'.$staff['staff']['salary_scheme'].'</b></td>';
// $html .= '</tr>';
// $html .= '</table>';
// $html .= "<br>";
$html .= '<h4>Receipts</h4>';
$html .= '<table cellpadding="4" cellspacing="1" border="1">';
$html .= '<tr style="text-align:left;"><th style="width:40px"><b>Sl#</b></th><th><b>Receipt No</b></th><th><b>Amount(₹)</b></th><th><b>Receipt Type</b></th><th><b>Payment Type</b></th><th><b>Status</b></th><th><b>Date</b></th></tr>';
$i = 0;
$totalAmount=0;
foreach($receipts as $row){
    $i++;
   
    $totalAmount = $totalAmount + $row->receipt_amount;
    $html .= '<tr style="text-align:left;"><td  style="width:40px">'.$i.'</td><td>'.$row->receipt_no.'</td><td style="text-align:right;">'.$row->receipt_amount.'</td><td>'.$row->receipt_type.'</td><td>'.$row->pay_type.'</td><td>'.$row->receipt_status.'</td><td>'.date("d-m-Y",strtotime($row->receipt_date)).'</td></tr>';
}
$html .= '</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>
