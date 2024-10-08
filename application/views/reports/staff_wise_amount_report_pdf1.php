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
$html .= '<h1>Counter Closing Report From '.$from_date.' To '.$to_date.' </h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">Sl NO</th>';
$html .= '<th>Date</th>';
$html .= '<th>User</th>';
$html .= '<th>Counter</th>';
$html .= '<th>Session</th>';
$html .= '<th style="text-align:right">Closing Amount(₹)</th>';
$html .= '<th style="text-align:right">Actual Amount(₹)</th>';
$html .= '<th style="text-align:right">Excess Amount(₹)</th>';
$html .= '<th style="text-align:right">Shortage Amount(₹)</th>';
$html .= '<th style="text-align:right">Reason For Difference</th>';
$html .= '</tr>';
$i=0;
$totalExcessAmount = 0.00;
$totalShortageAmount = 0.00;
$excessAmount = "0.00";
$shortageAmount = "0.00";
$difference = "0.00";
foreach($report as $row){
    $i++;
    $session_date = date('d-m-Y',strtotime($row->session_date));
    $excessAmount = "0.00";
    $shortageAmount = "0.00";
    if($row->actual_closing_amount < $row->closing_amount){
        $shortageAmount = $row->closing_amount - $row->actual_closing_amount;
        $totalShortageAmount = $totalShortageAmount + $shortageAmount;
    }else{
        $excessAmount = $row->actual_closing_amount - $row->closing_amount;
        $totalExcessAmount = $totalExcessAmount + $excessAmount;
    }
    $html .= '<tr style="text-align:left;">';
    $html .= '<td style="width:40px">'.$i.'</td>';
    $html .= '<td>'.$session_date.'</td>';
    $html .= '<td>'.$row->name.'</td>';
    $html .= '<td>'.$row->counter_no.'</td>';
    $html .= '<td>'.$row->id.'</td>';
    $html .= '<td style="text-align:right">'.$row->closing_amount.'</td>';
    $html .= '<td style="text-align:right">'.$row->actual_closing_amount.'</td>';
    $html .= '<td style="text-align:right">'.number_format($excessAmount, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format($shortageAmount, 2, '.', '').'</td>';
    $html .= '<td>'.$row->description.'</td>';
    $html .= '</tr>';
} 
if($totalExcessAmount < $totalShortageAmount){
    $difference = $totalShortageAmount - $totalExcessAmount;
    $difference = "-".$difference;
}else{
    $difference = $totalExcessAmount - $totalShortageAmount;
    $difference = "+".$difference;
}
$html .= '<tr style="text-align:left;">';
$html .= '<td colspan="7">Total</td>';
$html .= '<td style="text-align:right">'.number_format($totalExcessAmount, 2, '.', '').'</td>';
$html .= '<td style="text-align:right">'.number_format($totalShortageAmount, 2, '.', '').'</td>';
$html .= '<td  colspan="3"></td>';
$html .= '</tr>';
$html .= '<tr style="text-align:left;">';
$html .= '<td colspan="7">Difference</td>';
$html .= '<td colspan="2" style="text-align:right">'.number_format($difference, 2, '.', '').'</td>';
$html .= '<td colspan="3"></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= "<br>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>