<?php
/**
 * Object summary
 *
 * Sample output
 * <ul class="elgg-menu elgg-menu-entity"><li>Public</li><li>Like this</li></ul>
 * <h3><a href="">Title</a></h3>
 * <p class="elgg-subtext">Posted 3 hours ago by George</p>
 * <p class="elgg-tags"><a href="">one</a>, <a href="">two</a></p>
 * <div class="elgg-content">Excerpt text</div>
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity menu and metadata (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['tags']      HTML for the tags (default is tags on entity, pass false for no tags)
 * @uses $vars['content']   HTML for the entity content (optional)
 */

$entity = $vars['entity'];

$title_link = elgg_extract('title', $vars, '');
if ($title_link === '') {
	if (isset($entity->title)) {
		$text = $entity->title;
	} else {
		$text = $entity->name;
	}
	$params = array(
		'text' => $text,
		'href' => $entity->getURL(),
		'is_trusted' => true,
	);
	$title_link = elgg_view('output/url', $params);
}

$metadata = elgg_extract('metadata', $vars, '');
$subtitle = elgg_extract('subtitle', $vars, '');
$content = elgg_extract('content', $vars, '');

$tags = elgg_extract('tags', $vars, '');
if ($tags === '') {
	$tags = elgg_view('output/tags', array('tags' => $entity->tags));
}

$informe_pa = get_entity($entity->meeting_pa);
$group      = get_entity($entity->container_guid);
$informe_period = $entity->informe_period_y ."/".str_pad($entity->informe_period_m, 2, 0, STR_PAD_LEFT);
$informe_guid   = $entity->guid;
$informe_title  = "<a href=\"{$entity->getURL()}\">{$entity->title}</a>";
$group_guid     = $group->getGUID();
$group_name    = "<a href=\"{$group->getURL()}\">{$group->name}</a>";
if($informe_pa->name) {
                $informe_pa = "<a href=\"{$informe_pa->getURL()}\">{$informe_pa->name}</a>";
} else {
                $informe_pa = "SIN PA";
}


/*
if ($metadata) {
	echo $metadata;
}
if ($title_link) {
	echo "<h3>$title_link .... $informe_period</h3>";
}
echo "<div class=\"elgg-subtext\">$subtitle</div>";
echo $tags;

echo elgg_view('object/summary/extend', $vars);

if ($content) {
	echo "<div class=\"elgg-content\">$content</div>";
}
echo "<tr>";
echo "</tr>";
*/
echo "<div class=\"cell period\">$informe_period</div>";
echo "<div class=\"cell informe_guid\">$informe_guid</div>";
echo "<div class=\"cell informe_title\">$informe_title</div>";
echo "<div class=\"cell group_guid\">$group_guid</div>";
echo "<div class=\"cell group_name\">$group_name</div>";
echo "<div class=\"cell informe_pa\">$informe_pa</div>";
