<?php
/**
 * Edit informe form
 *
 * @package Blog
 */

$informe = get_entity($vars['guid']);
$vars['entity'] = $informe;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="message warning">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';
$preview_button = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/informe/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete elgg-state-disabled float-alt'
	));
}

// published informes do not get the preview button
if (!$vars['guid'] || ($informe && $informe->status != 'published')) {
	$preview_button = elgg_view('input/submit', array(
		'value' => elgg_echo('preview'),
		'name' => 'preview',
		'class' => 'mls',
	));
}

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
$action_buttons = $save_button . $preview_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'id' => 'informe_title',
	'value' => $vars['title']
));

$topics_label = elgg_echo('topics');
$topics_input = elgg_view('input/text', array(
	'name' => 'topics',
	'id' => 'informe_topics',
	'value' => $vars['topics']
));

$excerpt_label = elgg_echo('informe:excerpt');
$excerpt_input = elgg_view('input/text', array(
	'name' => 'excerpt',
	'id' => 'informe_excerpt',
	'value' => html_entity_decode($vars['excerpt'], ENT_COMPAT, 'UTF-8')
));

$body_label = elgg_echo('informe:body');
$body_input = elgg_view('input/longtext', array(
	'name' => 'description',
	'id' => 'informe_description',
	'value' => $vars['description']
));

$save_status = elgg_echo('informe:save_status');
if ($vars['guid']) {
	$entity = get_entity($vars['guid']);
	$saved = date('F j, Y @ H:i', $entity->time_created);
} else {
	$saved = elgg_echo('informe:never');
}

$status_label = elgg_echo('informe:status');
$status_input = elgg_view('input/dropdown', array(
	'name' => 'status',
	'id' => 'informe_status',
	'value' => $vars['status'],
	'options_values' => array(
		'draft' => elgg_echo('informe:status:draft'),
		'published' => elgg_echo('informe:status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/dropdown', array(
	'name' => 'comments_on',
	'id' => 'informe_comments_on',
	'value' => $vars['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'name' => 'tags',
	'id' => 'informe_tags',
	'value' => $vars['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'id' => 'informe_access_id',
	'value' => $vars['access_id']
));

$categories_input = elgg_view('input/categories', $vars);

// hidden inputs
if (elgg_instanceof(elgg_get_page_owner_guid(), 'group')) {
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
} else {
$container_guid_input = '';
}
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));

if (!elgg_instanceof(elgg_get_page_owner_guid(), 'group')) {

	$group_options = array();

	$um = get_users_membership(elgg_get_logged_in_user_guid());

	foreach ($um AS $g) {
		$group_options[$g->getGUID()] = $g->name;
	}

	$group_label = elgg_echo('group');
	$group_input = elgg_view('input/dropdown', array(
		'name' => 'informe_container_guid',
		'id' => 'group_guid_picker',
		'value' => $vars['group_guid'],
		'options_values' => $group_options,
	));
}

echo <<<___HTML

$draft_warning

<div>
	<label for="informe_container_guid">$group_label</label>
	$group_input
</div>

<div>
	<label for="informe_title">$title_label</label>
	$title_input
</div>

<div>
	<label for="informe_topics">$topics_label</label>
	$topics_input XXX
</div>

<div>
	<label for="informe_excerpt">$excerpt_label</label>
	$excerpt_input
</div>

<label for="informe_description">$body_label</label>
$body_input
<br />

<div>
	<label for="informe_tags">$tags_label</label>
	$tags_input
</div>

$categories_input

<div>
	<label for="informe_comments_on">$comments_label</label>
	$comments_input
</div>

<div>
	<label for="informe_access_id">$access_label</label>
	$access_input
</div>

<div>
	<label for="informe_status">$status_label</label>
	$status_input
</div>

<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="informe-save-status-time">$saved</span>
	</div>

	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;
