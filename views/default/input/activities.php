<?php

$report_activities_count = 0;

$activities = $vars['activities'];

if (!empty($activities)) {
	foreach($activities as $activity) {
		echo elgg_view('input/activity', array('entity' => $activity, 'report_activities_count' => $report_activities_count));
		$report_activities_count++;
	}
}
