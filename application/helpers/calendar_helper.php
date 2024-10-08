<?php
function show($data){
    echo '<pre>';print_r($data);exit;
}
function gregMonths($year=AIY){
    $CI =& get_instance();
    return $CI->db->select('gregmonth,gregdate')
                    ->where('gregyear',$year)
                    ->group_by('gregmonth')
                    ->order_by('id','ASC')
                    ->get('calendar_malayalam')
                    ->result_array();
}

function malYears($engYear=AIY){
    $CI =& get_instance();
    return $CI->db->select('malyear')
                    ->where('gregyear >=',$engYear)
                    ->group_by('malyear')
                    ->order_by('id','ASC')
                    ->get('calendar_malayalam')
                    ->result_array();
}

function getMalYear($engDate){
    $CI =& get_instance();
    return $CI->db->select('malyear')
                    ->where('gregdate',$engDate)
                    ->group_by('malyear')
                    ->order_by('id','ASC')
                    ->get('calendar_malayalam')
                    ->row_array();
}

function malMonths($malYear){
    $CI =& get_instance();
    return $CI->db->select('malmonth,maldate')
                    ->where('malyear',$malYear)
                    ->group_by('malmonth')
                    ->order_by('id','ASC')
                    ->get('calendar_malayalam')
                    ->result_array();
}

function getMalMonth($engDate){
    $CI =& get_instance();
    return $CI->db->select('malmonth')
                    ->where('gregdate',$engDate)
                    ->group_by('malyear')
                    ->order_by('id','ASC')
                    ->get('calendar_malayalam')
                    ->row_array();
}

function getCalendarHeading($lang,$gregdate){
    $CI =& get_instance();
    $html = '';
    $monthDetail = $CI->db->where('gregdate',$gregdate)->get('calendar_malayalam')->row_array();
    $tempYear = '';
    $tempMonth = '';
    if($lang=='eng'){
        $gregYear = $monthDetail['gregyear'];
        $gregMonth = $monthDetail['gregmonth'];
        $monthDetails = $CI->db->where('gregyear',$gregYear)->where('gregmonth',$gregMonth)->group_by('malmonth')->order_by('id','ASC')->get('calendar_malayalam')->result_array();
        $malYear = '';
        $malMonth = '';
        foreach($monthDetails as $k=>$v){
            if($k!=0 && $tempYear != $v['malyear']){
                $malYear = $malYear.' - '.$v['malyear'];
            }else{
                $malYear = $v['malyear'];
            }
            $tempYear = $v['malyear'];
            if($k!=0 && $tempMonth != $v['malmonth']){
                $malMonth = $malMonth.' - '.$v['malmonth'];
            }else{
                $malMonth = $v['malmonth'];
            }
            $tempMonth = $v['malmonth'];
        }
        $html = '
                <div class="col-sm-6" style="text-align:center;">
                    <h4>'.$gregYear.'</h4>
                    <h4>'.$gregMonth.'</h4>
                </div>
                <div class="col-sm-6" style="text-align:center;">
                    <h4>'.$malYear.'</h4>
                    <h4>'.$malMonth.'</h4>
                </div>
                ';
    }
    if($lang=='mal'){
        $malYear = $monthDetail['malyear'];
        $malMonth = $monthDetail['malmonth'];
        $monthDetails = $CI->db->where('malyear',$malYear)->where('malmonth',$malMonth)->group_by('gregmonth')->order_by('id','ASC')->get('calendar_malayalam')->result_array();
        $gregYear = '';
        $gregMonth = '';
        foreach($monthDetails as $k=>$v){
            if($k!=0 && $tempYear != $v['gregyear']){
                $gregYear = $malYear.' - '.$v['gregyear'];
            }else{
                $gregYear = $v['gregyear'];
            }
            $tempYear = $v['gregyear'];
            if($k!=0 && $tempMonth != $v['gregmonth']){
                $gregMonth = $gregMonth.' - '.$v['gregmonth'];
            }else{
                $gregMonth = $v['gregmonth'];
            }
            $tempMonth = $v['gregmonth'];
        }
        $html = '
                <div class="col-sm-6" style="text-align:center;">
                    <h4>'.$gregYear.'</h4>
                    <h4>'.$gregMonth.'</h4>
                </div>
                <div class="col-sm-6" style="text-align:center;">
                    <h4>'.$malYear.'</h4>
                    <h4>'.$malMonth.'</h4>
                </div>
                ';

    }
    return $html;
}

function getCalendarContent($lang,$gregdate){
    $CI =& get_instance();
    $html = '';
    $monthDetail = $CI->db->where('gregdate',$gregdate)->get('calendar_malayalam')->row_array();
    $monthDetails = array();
    if($lang=='eng'){
        $gregYear = $monthDetail['gregyear'];
        $gregMonth = $monthDetail['gregmonth'];
        $monthDetails = $CI->db->where('gregyear',$gregYear)->where('gregmonth',$gregMonth)->order_by('id','ASC')->get('calendar_malayalam')->result_array();
    }
    if($lang=='mal'){
        $malYear = $monthDetail['malyear'];
        $malMonth = $monthDetail['malmonth'];
        $monthDetails = $CI->db->where('malyear',$malYear)->where('malmonth',$malMonth)->order_by('id','ASC')->get('calendar_malayalam')->result_array();
    }
    if(!empty($monthDetails)){
        foreach($monthDetails as $row){
            if(strtotime($row['gregdate']) >= time()){
                $disabled = '';//'<td width="10%"><a style="cursor: pointer;" data-toggle="tooltip" class="edit_btn_datatable" data-placement="right" data-original-title="Edit Data"><i class="fa fa-edit "></i></a></td>';
            }else{
                $disabled = 'disabled';
            }
            if($row['vavu']==0){
                $velutavavu = 'selected';
            }else{
                $velutavavu = '';
            }
            if($row['vavu']==15){
                $karutavavu = 'selected';
            }else{
                $karutavavu = '';
            }
            if($row['hall_blocking'] == 1){
                $hall_blocking_status = 'checked';
            }else{
                $hall_blocking_status = '';
            }
            if($row['aavahanam_blocking'] == 1){
                $aavahanam_blocking_status = 'checked';
            }else{
                $aavahanam_blocking_status = '';
            }

            $vavu = '<select class="data_update" '.$disabled.' name="vavu[]">
                        <option value="'.$row['vavu'].'">&nbsp;</option>
                        <option '.$velutavavu.' value="0">വെളുത്ത വാവു</option>
                        <option '.$karutavavu.' value="15">കറുത്ത വാവു</option>
                    </select>';
            

            $hall_block = '<input type="checkbox" class="data_update" '.$disabled.' name="hall_block_'.$row['id'].'" id="hall_block_'.$row['id'].'" '.$hall_blocking_status.'/><label for="hall_block_'.$row['id'].'">Block</label>';
            $aavahanam_block = '<input type="checkbox" class="data_update" '.$disabled.' name="aavahanam_block_'.$row['id'].'" id="aavahanam_block_'.$row['id'].'" '.$aavahanam_blocking_status.'/><label for="aavahanam_block_'.$row['id'].'">Block</label>';

            $html .= '<tr>
                    <td width="10%">'.$row['malweekday'].'</td>
                    <td width="15%"><input '.$disabled.' type="hidden" name="id[]" value="'.$row['id'].'">'.$row['malmonth'].'-'.$row['malday'].'</td>
                    <td width="10%">'.$row['gregmonth'].'-'.$row['gregday'].'</td>
                    <td width="15%"><textarea class="data_update" '.$disabled.' rows="1" cols="15" name="malnakshatram[]">'.$row['malnakshatram'].'</textarea></td>
                    <td width="10%"><textarea class="data_update" '.$disabled.' rows="1" cols="15" name="malnakshatram_time[]">'.$row['malnakshatram_time'].'</textarea></td>
                    <td width="15%"><textarea class="data_update" '.$disabled.' rows="1" cols="15" name="thithi[]">'.$row['thithi'].'</textarea></td>
                    <td width="10%"><textarea class="data_update" '.$disabled.' rows="1" cols="15" name="thithi_time[]">'.$row['thithi_time'].'</textarea></td>
                    <td width="15%">'.$vavu.'</td>
                    <td width="5%">'.$hall_block.'</td>
                    <td width="5%">'.$aavahanam_block.'</td>';
        }
        $html .= '</tr>';
    }
    return $html;
}

?>