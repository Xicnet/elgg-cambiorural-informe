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
	$new_post = TRUE;
}

// set the previous status for the hooks to update the time_created and river entries
$old_status = $informe->status;

// set defaults and required values.

// group this report belongs to
$container_guid = (int)get_input('container_guid');
$group = get_entity($container_guid);

// period for this report
$report_month = date('F Y', strtotime(get_input('informe_period_y')."-".get_input('informe_period_m')));
$values = array(
	'title' => "Informe del grupo ".$group->name." ($report_month)",
	'informe_period_m' => '',
	'informe_period_y' => '',
	'meeting_pa' => '',
	'meeting_ap' => '',
	'meeting_manager' => '',
	'building' => '',
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
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => '',
	'tags' => '',
	'container_guid' => (int)get_input('container_guid'),
);

// fail if a required entity isn't set
$required = array('');
if(get_input('status') == 'published') {
	$required = array(
		'title' => '',
		'informe_period_m',
		'informe_period_y',
		'meeting_pa',
		'meeting_ap',
		'meeting_manager',
		'meeting_date',
		'building',
		'meeting_place',
		'meeting_assistance',
		'topics',
		'news',
		'requirements',
		'rating',
		'pros',
		'cons',
		'meeting_comments',
		'productiv',
		'other_comments',
		'description'
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
		case 'building':
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
			if ($value) {
				$values[$name] = $value;
			} else {
				unset ($values[$name]);
			}
			break;

		case 'tags':
			if ($value) {
				$values[$name] = string_to_tag_array($value);
			} else {
				unset ($values[$name]);
			}
			break;

		case 'excerpt':
			if ($value) {
				$values[$name] = elgg_get_excerpt($value);
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
}

// only try to save base entity if no errors
if (!$error) {
	if ($informe->save()) {
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

		$status = $informe->status;

		// add to river if changing status or published, regardless of new post
		// because we remove it for drafts.
		if (($new_post || $old_status == 'draft') && $status == 'published') {
			add_to_river('river/object/informe/create', 'create', elgg_get_logged_in_user_guid(), $informe->getGUID());

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
