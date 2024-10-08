<?php 
		$currentday = date("d"); 
		$currentmon = date("M"); 
		$currentYear = date("Y"); 
		$engYear['gregmonth'];
        $currentday1 = date('d',strtotime($engYear['gregday']));     
	    $currentMonth = date('m',strtotime($engYear['gregdate'])); 
	if($currentMonth == 1){
		$prevMonth = 12;
		$prevYear = date('Y',strtotime($engYear['gregdate'])) - 1;
		$nextMonth = $currentMonth + 1;
		$nextYear = date('Y',strtotime($engYear['gregdate']));
	}else if($currentMonth == 12){
		$prevMonth = $currentMonth - 1;
		$prevYear = date('Y',strtotime($engYear['gregdate']));
		$nextMonth = 1;
		$nextYear = date('Y',strtotime($engYear['gregdate'])) + 1;
	}else{
		$prevMonth = $currentMonth - 1;
		$prevYear = date('Y',strtotime($engYear['gregdate']));
		$nextMonth = $currentMonth + 1;
		$nextYear = date('Y',strtotime($engYear['gregdate']));
	}
	
?>
<table class="table table-bordered Customtable">
	<tr class="calendarHead">
		<th colspan="2" class="calendarHeadMonth">
			<div class="calendarDirection calenderDirectionLeft">
				<a href="javascript:void(0)" onclick="get_calendar(<?php echo $prevMonth.','.$prevYear ?>)">
					<i class="fa fa-arrow-left" aria-hidden="true"></i>
				</a>
			</div>
		</th>
		<th colspan="3" class="calendarHeadYear">
			<?php echo $engYear['gregmonthmal']." - ".$engYear['gregyear'] ?>
			<h6><?php echo $malYear1['malyear']." ".$malYear1['malmonth']." - ".$malYear2['malyear']." ".$malYear2['malmonth'] ?></h6>
		</th>
		<th colspan="2" class="calendarHeadMalMonth">
			<div class="calendarDirection calenderDirectionRight">
				<a href="javascript:void(0)" onclick="get_calendar(<?php echo $nextMonth.','.$nextYear ?>)">
					<i class="fa fa-arrow-right" aria-hidden="true"></i>
				</a>
			</div>
		</th>
	</tr>
	<tr class="day calendar-heading">
		<th class="day" title="Sun" > <span>ഞായർ</span> </th>
		<th class="day" title="Mon"> <span>തിങ്കൾ </span> </th>
		<th class="day" title="Tue"> <span>ചൊവ്വ</span> </th>
		<th class="day" title="Wed"> <span>ബുധൻ</span> </th>
		<th class="day" title="Thu"> <span>വ്യാഴം</span> </th>
		<th class="day" title="Fri"> <span>വെള്ളി</span> </th>
		<th class="day" title="Sat"> <span>ശനി</span> </th>
	</tr>
	<?php 
	//  echo $currentday;
		$i = 0;
		$j = 0;
		$l = 5;
		echo "<tr>";
		
		foreach($data as $row){
				if($currentday==$row->gregday && $currentmon==$engYear['gregmonth'] && $currentYear==$engYear['gregyear'])
			{
				$date="background-color: lightblue;";
			}
			else{
				$date="";
			}
			
			$j++;
			$i++;
			if($j == 1){
				if($row->gregweekday == "Sun"){
				}else if($row->gregweekday == "Mon"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}else if($row->gregweekday == "Tue"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}else if($row->gregweekday == "Wed"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}else if($row->gregweekday == "Thu"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}else if($row->gregweekday == "Fri"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}else if($row->gregweekday == "Sat"){
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
					echo '<td class="cal-day prevMonth" data-day="0"> </td>';
					$i++;
				}
			}
			$l++;
			echo '<td class="cal-day"  style="'.$date.'" data-day="'.$l.'" data-toggle="modal" data-target="#myModal">';
			echo '<div class="main-day">'.$row->gregday.'</div>';
			echo '<span class="day-info">';
			echo '<span class="sub-day">'.$row->malday.'</span>'; 
			echo '<span class="nakshatra-name">'.$row->malnakshatram.'</span> ';
			echo '<span class="nakshatra-ghati">'.$row->malnakshatram_time.'</span> ';
			echo '<span class="tithi-name">'.$row->thithi.'</span> ';
			echo '<span class="tithi-ghati">'.$row->thithi_time.'</span> </span>';
			echo '</td>';
			if($i%7 == 0){
				echo '</tr><tr>';
				$l = 5;
			}
		} 
		$count = $i%7;
		for($k=0;$k<(7-$count);$k++){
			echo '<td class="cal-day prevMonth" data-day="0"> </td>';
		}
		echo "</tr>";
	?>
</table>