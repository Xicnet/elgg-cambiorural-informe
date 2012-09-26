<?php
/**
 * Extend groups/profile/fields with AP and PA
 */

$group_ap = get_entity($group->ap);
$group_pa = get_entity($group->pa);

if (elgg_instanceof($group_ap, 'user')) { 
	$even_odd = ($even_odd == 'even') ? 'odd' : 'even';
	$title    = elgg_echo('informe:group_ap');
	$link     = elgg_view('output/url', array(
											  'text' => $group_ap->name,
											  'value' => $group_ap->getURL(),
											  'is_trusted' => TRUE,));

	echo "<p><b>$title: </b> $link</p>";

}

if (elgg_instanceof($group_pa, 'user')) { 
	$even_odd = ($even_odd == 'even') ? 'odd' : 'even';
	$title    = elgg_echo('informe:group_pa');
	$link     = elgg_view('output/url', array(
											  'text' => $group_pa->name,
											  'value' => $group_pa->getURL(),
											  'is_trusted' => TRUE,));
	
	echo "<p><b>$title: </b> $link</p>";
	
}
