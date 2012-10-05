<?php

//@todo XHR support

$guid = (int) get_input('guid');

$activity = get_entity($guid);

if (elgg_instanceof($activity, 'object', 'report_activity') && $activity->canEdit()) {
	if ($activity->delete()) {
		// update view
		system_message(elgg_echo('informe:action:deleteactivity:success'));
	} else {
		register_error(elgg_echo('informe:action:deleteactivity:failure'));
	}
} else {
	register_error("xxx " . print_r($activity, TRUE));
}

forward(REFERRER);
