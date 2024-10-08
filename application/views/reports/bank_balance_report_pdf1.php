<?php

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');



$title = "Chelamattom Temple ";
$pdf->SetTitle("Reports");
$pdf->SetSubject('Reports');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data

$headerTitle = "Chelamattom Temple ";
$headerString = "Balithara Report";
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
$fontname = TCPDF_FONTS::addTTFfont(APPPATH.'/libraries/tcpdf/fonts/SakalBharati Malayalam_N 9.14 _Ship.ttf', 'SakalBharati Malayalam_N 9.14 _Ship', '', 32);
$pdf->SetFont($fontname, '', 10, '', 'false');
// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";
$html .= '<h1>Bank Balance Report From '.$from_date.' To '.$to_date.' </h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">Sl NO</th>';
$html .= '<th>Bank</th>';
$html .= '<th>Account</th>';
$html .= '<th style="text-align:right">Opening Amount(₹)</th>';
$html .= '<th style="text-align:right">Closing Amount(₹)</th>';
$html .= '</tr>';
$i=0;
$totalOpeningAmount = 0.00;
$totalClosingAmount = 0.00;
foreach($accountReport as $row){
    $i++;
    $totalOpeningAmount = $totalOpeningAmount + $row->opening;
    $totalClosingAmount = $totalClosingAmount + $row->closing;
    $html .= '<tr style="text-align:left;">';
    $html .= '<td  style="width:40px">'.$i.'</td>';
    $html .= '<td>'.$row->bank_eng.'</td>';
    $html .= '<td>'.$row->account_no.'</td>';
    $html .= '<td style="text-align:right">'.$row->opening.'</td>';
    $html .= '<td style="text-align:right">'.$row->closing.'</td>';
    $html .= '</tr>';
}
$html .= '<tr style="text-align:left;">';
$html .= '<td  colspan="3">Total</td>';
$html .= '<td style="text-align:right">'.number_format($totalOpeningAmount, 2, '.', '').'</td>';
$html .= '<td style="text-align:right">'.number_format($totalClosingAmount, 2, '.', '').'</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= "<br>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>