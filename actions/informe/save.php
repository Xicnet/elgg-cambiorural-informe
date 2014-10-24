<?php
/**
 * Save informe entity
 *
 * @package Blog
 */

// start a new sticky form session in case of failure
elgg_make_sticky_form('informe');

// save or preview
$save = (bool)get_input('save');

// store errors to pass along
$error = FALSE;
$error_forward_url = REFERER;
$user = elgg_get_logged_in_user_entity();

// edit or create a new entity
$guid = get_input('guid');

$activities = get_input('activities');

// group this report belongs to
$container_guid = (int)get_input('container_guid');
$group = get_entity($container_guid);

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'informe') && $entity->canEdit()) {
		$informe = $entity;
	} else {
		register_error(elgg_echo('informe:error:post_not_found'));
		forward(get_input('forward', REFERER));
	}

	// save some data for revisions once we save the new edit
	$revision_text = $informe->description;
	$new_post = $informe->new_post;
} else {
	$informe = new ElggInforme();
	$informe->subtype = 'informe';
	$informe->meeting_ap = $group->ap;
	$informe->meeting_pa = $group->pa;
	$new_post = TRUE;
}

// set the previous status for the hooks to update the time_created and river entries
$old_status = $informe->status;

// set the previous approval status
$old_approval = $informe->approval;

// set defaults and required values.

if (!elgg_instanceof($group, 'group')) {
    register_error(elgg_echo('informe:error:nocontainer'));
    forward(REFERRER);
}

// period for this report
$report_month = strftime('%B %Y', strtotime(get_input('informe_period_y')."-".get_input('informe_period_m')));
$values = array(
	'title' => "Informe del grupo ".$group->name." ($report_month)",
	'informe_period_m' => '',
	'informe_period_y' => '',
	#'meeting_pa' => '',
	#'meeting_ap' => '',
	'meeting_manager' => '',
	'meeting_building' => '',
	'meeting_date' => '',
	'meeting_place' => '',
	'meeting_assistance' => '',
	'topics' => '',
	'news' => '',
	'requirements' => '',
	'rating' => '',
	'pros' => '',
	'cons' => '',
	'meeting_comments' => '',
	'productiv' => '',
	'other_comments' => '',
	'description' => '',
	'status' => 'draft',
	'approval' => 'pending',
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'container_guid' => $group->guid,
);

// fail if a required entity isn't set
$required = array();
if(get_input('status') == 'published') {
	$required = array(
		'title' => '',
		'informe_period_m',
		'informe_period_y',
		'meeting_pa',
		'meeting_ap',
		'meeting_manager',
		'meeting_date',
		'meeting_building',
		'meeting_place',
		'meeting_assistance',
		#'topics',
		#'news',
		#'requirements',
		#'rating',
		#'pros',
		#'cons',
		#'meeting_comments',
		#'productiv',
		#'other_comments'
	);
}

// load from POST and do sanity and access checking
foreach ($values as $name => $default) {
	$value = get_input($name, $default);

	if (in_array($name, $required) && empty($value)) {
		$error = elgg_echo("informe:error:missing:$name");
	}

	if ($error) {
		break;
	}

	switch ($name) {
		case 'meeting_manager':
		case 'meeting_building':
		case 'meeting_date':
		case 'meeting_place':
		case 'meeting_assistance':
		case 'pa_guid':
		case 'ap_guid':
		case 'news':
		case 'requirements':
		case 'rating':
		case 'pros':
		case 'cons':
		case 'meeting_comments':
		case 'productiv':
		case 'other_comments':
		case 'topics':
                case 'meeting_pa':
                case 'approval':
                case 'meeting_ap':
			if ($value) {
				$values[$name] = $value;
			} else {
				unset ($values[$name]);
			}
			break;
                case 'meeting_ap':
			$values[$name] = $value;
			break;

		case 'tags':
			if ($value) {
				$values[$name] = string_to_tag_array($value);
			} else {
				unset ($values[$name]);
			}
			break;

		case 'container_guid':
			// this can't be empty or saving the base entity fails
			if (!empty($value)) {
				if (can_write_to_container($user->getGUID(), $value)) {
					$values[$name] = $value;
				} else {
					$error = elgg_echo("informe:error:cannot_write_to_container");
				}
			} else {
				unset($values[$name]);
			}
			break;

		// don't try to set the guid
		case 'guid':
			unset($values['guid']);
			break;

		default:
			$values[$name] = $value;
			break;
	}

	$values['description'] = $values['meeting_manager']." ".  $values['meeting_building']." ".
				$values['meeting_place']." ". $values['news']." ". $values['requirements']." ".
				$values['pros']." ". $values['cons']." ". $values['meeting_comments']." ".
				$values['productiv']." ". $values['other_comments']." ". $values['topics'];
}

// if preview, force status to be draft
if ($save == false) {
	$values['status'] = 'draft';
}

// assign values to the entity, stopping on error.
if (!$error) {
	foreach ($values as $name => $value) {
		if (FALSE === ($informe->$name = $value)) {
			$error = elgg_echo('informe:error:cannot_save' . "$name=$value");
			break;
		}
	}
	if(is_null($informe->due_time)) {
		//$informe->due_time = strtotime(get_input('informe_period_y').'-'.get_input('informe_period_m') . ' + 1 month + 15 days');
		$next_month = '';
		if(date('j') > 16) {
			$next_month = ' + 1 month';
		}
		$informe->due_time = strtotime(get_input('informe_period_y').'-'.get_input('informe_period_m') . ' + 15 days' . $next_month);
	}
}

// only try to save base entity if no errors
if (!$error) {
	if ($informe->save()) {
		
		// save activities
		foreach($activities as $params) {
			if(!empty($params['title'])) {
				if($params['guid']) {
					$activity = get_entity($params['guid']);
				} else {
					$activity = new ElggReportActivity();
				}
				$activity->title = $params['title'];
				$activity->date  = $params['date'];
				$activity->scope = $params['scope'];
				$activity->notes = $params['notes'];
				if ($activity->save()) {
					add_entity_relationship($informe->getGUID(), 'report_activity', $activity->getGUID());
				} else {
					register_error('informe:error:cannotsaveactivity');
					forward(REFERER);
				}
			}
		}

		// remove sticky form entries
		elgg_clear_sticky_form('informe');

		// remove autosave draft if exists
		$informe->deleteAnnotations('informe_auto_save');

		// no longer a brand new post.
		$informe->deleteMetadata('new_post');

		// if this was an edit, create a revision annotation
		if (!$new_post && $revision_text) {
			$informe->annotate('informe_revision', $revision_text);
		}

		system_message(elgg_echo('informe:message:saved'));

		$status   = $informe->status;
		$approval = $informe->approval;

		if ($old_approval == 'pending' && $approval == 'approved') {
			$ap = get_entity($group->ap);
			$pa = get_entity($group->pa);

			// send mail to AP notifying there is a new informe pending review
			$to      = "{$pa->name} <{$pa->email}>";
			$subject = 'Su informe fue aprobado';
			$body    = "Estimada/o {$pa->name},\n"
					. "\n"
					. "El Agente de Proyecto {$ap->name} ha dado por aprobado su informe mensual:\n"
					. "\n"
					. "{$informe->title}\n"
					. "\n"
					. "Puede verlo en el siguiente link:\n"
					. $informe->getURL()
					. "\n"
					. "\n"
					. "--\n"
					. "Le saludamos muy atentamente!\n"
					. "El equipo de la Red Cambio Rural.\n"
					. "http://www.redcambiorural.magyp.gob.ar/";
			elgg_send_email	('no-responder-redcambiorural@minagri.gob.ar', $to, $subject, $body);
		}

		// add to river if changing status or published, regardless of new post
		// because we remove it for drafts.
		if (($new_post || $old_status == 'draft') && $status == 'published') {
			add_to_river('river/object/informe/create', 'create', elgg_get_logged_in_user_guid(), $informe->getGUID());

			$ap = get_entity($group->ap);
			$pa = get_entity($group->pa);

			// send mail to AP notifying there is a new informe pending review
			$to      = "{$ap->name} <{$ap->email}>";
			$subject = 'Nuevo informe publicado pendiente de revisión';
			$body    = "Estimada/o {$ap->name},\n"
					. "\n"
					. "El Promotor Asesor {$pa->name} ha publicado un informe para el grupo {$group->name}.\n"
					. "\n"
					. "Por favor, revíselo y si corresponde márquelo como aprobado:\n\n"
					. $informe->getURL()
					. "\n"
					. "\n"
					. "--\n"
					. "Le saludamos muy atentamente!\n"
					. "El equipo de la Red Cambio Rural.\n"
					. "http://www.redcambiorural.magyp.gob.ar/";
			elgg_send_email	('no-responder-redcambiorural@minagri.gob.ar', $to, $subject, $body);

			if ($guid) {
				$informe->time_created = time();
				$informe->save();
			}
		} elseif ($old_status == 'published' && $status == 'draft') {
			elgg_delete_river(array(
				'object_guid' => $informe->guid,
				'action_type' => 'create',
			));
		}

		if ($informe->status == 'published' || $save == false) {
			forward($informe->getURL());
		} else {
			forward("informe/edit/$informe->guid");
		}
	} else {
		register_error(elgg_echo('informe:error:cannot_save'));
		forward($error_forward_url);
	}
} else {
	register_error($error);
	forward($error_forward_url);
}
