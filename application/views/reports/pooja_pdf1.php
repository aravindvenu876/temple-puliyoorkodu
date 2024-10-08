<?php

// $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

require_once(APPPATH.'/libraries/tcpdf/tcpdf.php');
require_once(APPPATH.'/language/english/site_lang.php');
require_once(APPPATH.'/language/malayalam/site_lang.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');



$title = "Chelamattom Temple ";
$pdf->SetTitle("Reports");
$pdf->SetSubject('Reports');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data

$headerTitle = "Chelamattom Temple ";
$headerString = "Pooja Report";
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
$pdf->SetFont($fontname, '', 10, '', 'false');


// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
 //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";
$html .= '<h1>Pooja Report From '.$from_date.' To '.$to_date.' </h1>';
$html .= '<h5 style="width:100%;float:left;"><b>Counter:</b>'.$counter.'</h5>';
$html .= '<h5 style="width:100%;float:left;"><b>User:</b>'.$user.'</h5>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">SL NO</th>';
$html .= '<th>Date</th>';
$html .= '<th>Pooja</th>';
$html .= '<th>Star</th>';
$html .= '<th>Pooja Type</th>';
$html .= '<th>Receipt Number</th>';
$html .= '<th style="text-align:right">Amount</th>';
$html .= '<th>Name</th>';
$html .= '<th>'.$this->lang->line('amount').'</th>';
$html .= '<th>User</th>';
$html .= '<th>Counter</th>';
$html .= '</tr>';
$i=0;
foreach($report as $row){
$i++;
$phonech="";
if($row->phone==null){
    $phone = "";
}
else{
    $str = $row->phone;
   $phonech = strlen($str);
}
if($phonech<=10){
    $phone =$row->phone;
}
else{
    $phone = "";
}

$html .= '<tr style="text-align:left;">';
$html .= '<td style="width:40px">'.$i.'</td>';
$html .= '<td>'.date('d-m-Y',strtotime($row->receipt_date)).'</td>';
$html .= '<td>'.$row->pooja.'</td>';
$html .= '<td>'.$row->star.'</td>';
$html .= '<td>'.$row->pooja_type.'</td>';
$html .= '<td>'.$row->receipt_no.'</td>';
$html .= '<td style="text-align:right">'.$row->amount.'</td>';
$html .= '<td>'.$row->name.'</td>';
$html .= '<td>'.$phone.'</td>';
$html .= '<td>'.$row->user_name.'</td>';
$html .= '<td>'.$row->pos_counter_id.'</td>';
$html .= '</tr>';
}
$html .= '</table>';
$html .= "<br>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>