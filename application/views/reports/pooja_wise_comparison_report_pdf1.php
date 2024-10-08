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
$headerString = "Pooja Wise Collection Comparison Report";
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
$html .= '<h1>Pooja Wise Collection Comparison Report For '.$current.'</h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">SL No</th>';
$html .= '<th>Pooja Code</th>';
$html .= '<th>Pooja</th>';
$html .= '<th>'.$current.'</th>';
$html .= '<th>'.$previous.'</th>';
$html .= '<th>'.$prevYear.'</th>';
$html .= '</tr>';
$i=0;
foreach($poojas as $row){
    $i++;
    $rate1 = "0.00";
    if(!empty($reports1)){
        foreach($reports1 as $row1){
            if($row->id == $row1->pooja_master_id){
                $rate1 = $row1->total_amount;
            }
        }
    }
    $rate2 = "0.00";
    if(!empty($reports2)){
        foreach($reports2 as $row2){
            if($row->id == $row2->pooja_master_id){
                $rate2 = $row2->total_amount;
            }
        }
    }
    $rate3 = "0.00";
    if(!empty($reports3)){
        foreach($reports3 as $row3){
            if($row->id == $row3->pooja_master_id){
                $rate3 = $row3->total_amount;
            }
        }
    }
    $html .= '<tr style="text-align:left;">';
    $html .= '<td  style="width:40px">'.$i.'</td>';
    $html .= '<td>'.$row->id.'</td>';
    $html .= '<td>'.$row->pooja_name.'</td>';
    $html .= '<td style="text-align:right">'.number_format((float)$rate1, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format((float)$rate2, 2, '.', '').'</td>';
    $html .= '<td style="text-align:right">'.number_format((float)$rate3, 2, '.', '').'</td>';
    $html .= '</tr>';
}


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>