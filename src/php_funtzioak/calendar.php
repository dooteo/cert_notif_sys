<?php

$calendar_jan = 'January';
$calendar_feb = 'February';
$calendar_mar = 'March';
$calendar_apr = 'April';
$calendar_may = 'May';
$calendar_jun = 'June';
$calendar_jul = 'July';
$calendar_aug = 'August';
$calendar_sep = 'September';
$calendar_oct = 'October';
$calendar_nov = 'November';
$calendar_dec = 'December';

$calendar_monday = 'Monday';
$calendar_tuesday = 'Tuesday';
$calendar_wednesday = 'Wednesday';
$calendar_thursday = 'Thursday';
$calendar_friday = 'Friday';
$calendar_saturday = 'Saturday';
$calendar_sunday = 'Sunday';

$calendar_sun = 'S';
$calendar_mon = 'M';
$calendar_tue = 'T';
$calendar_wed = 'W';
$calendar_thu = 'T';
$calendar_fri = 'F';
$calendar_sat = 'S';

$first_day_week = 1;
// 0 for Sunday
$monthNames = array(
$calendar_jan,
$calendar_feb,
$calendar_mar,
$calendar_apr,
$calendar_may,
$calendar_jun,
$calendar_jul,
$calendar_aug,
$calendar_sep,
$calendar_oct,
$calendar_nov,
$calendar_dec,
);

$days = array(
0 => $calendar_sun,
1 => $calendar_mon,
2 => $calendar_tue,
3 => $calendar_wed,
4 => $calendar_thu,
5 => $calendar_fri,
6 => $calendar_sat
);
/* draws a calendar 
*  $fdw == first day of week
*/

function unimail_calendar($fdw, $month, $year){
	global $days;
	
	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$calendar.= '<tr class="calendar-row">';
	for ($i = $fdw; $i < 7; $i++) {
		$calendar .='<th class="calendar-day-head">';
		$calendar .= $days[$i];
		$calendar .='</th>';
	}
	for ($i = 0; $i < $fdw; $i++) {
		$calendar .='<th class="calendar-day-head">';
		$calendar .= $days[$i];
		$calendar .='</th>';
	}
	
	$calendar.= '</tr>';


	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	for ($i = $fdw; $i < $running_day ; $i++) {
		$calendar.= '<td class="calendar-day-np"></td>';
	}
	for ($i = 0; $i < ((7 - $fdw) % 7) ; $i++) {
		$calendar.= '<td class="calendar-day-np"></td>';
	}

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  
			    IF MATCHES FOUND, PRINT THEM !! **/
			$calendar.= str_repeat('<p> </p>',2);
			
		$calendar.= '</td>';
		if ($running_day == ((6 + $fdw) % 7) ) {

			$calendar.= '</tr>';
			if (($day_counter+1) != $days_in_month) {
				$calendar.= '<tr class="calendar-row">';
			}
			//$running_day = -1;
			$days_in_this_week = 0;
		}
		if ($running_day == 6) { // Reset running_day
			$running_day = -1;
		}
		$running_day++; 
		$day_counter++;
	}

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8) {
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++) {
			$calendar.= '<td class="calendar-day-np"> </td>';
		}
	}

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}

?>
