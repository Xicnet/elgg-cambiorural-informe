<?php

$report_activities_count = 0;


if(empty($activities)) {
	echo elgg_view('input/activity', array('entity' => $activity, 'report_activities_count' => $report_activities_count));
} else {
	foreach($activities as $activity) {
		echo elgg_view('input/activity', array('entity' => $activity, 'report_activities_count' => $report_activities_count));
		$report_activities_count++;
	}
}
