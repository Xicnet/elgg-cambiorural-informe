<?php
/**
 * Group informe module
 */

$group = elgg_get_page_owner_entity();

if ($group->informe_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "informe/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'informe',
	'container_guid' => elgg_get_page_owner_guid(),
	'metadata_name_value_pairs' => array('name' => 'status', 'value' => 'published'),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities_from_metadata($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('informe:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "informe/add/$group->guid",
	'text' => elgg_echo('informe:write'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('informe:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
