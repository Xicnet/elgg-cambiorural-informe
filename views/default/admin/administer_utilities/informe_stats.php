<style>
tr {
}
td {
	border: 1px solid orange;
	padding: 3px;
}
div.cell {
	border: 1px solid black;
	padding: 3px;
}
div.cell.period {
	float: left;
	width: 5%;
}
div.cell.informe_guid {
	float: left;
	width: 5%;
}
div.cell.informe_title {
	float: left;
	width: 30%;
}
div.cell.group_guid {
	float: left;
	width: 5%;
}
div.cell.group_name {
	float: left;
	width: 20%;
}
div.cell.informe_pa {
	float: left;
	width: 20%;
}
</style>
<?php
/**
 * Elgg Reported content admin page
 *
 * @package ElggReportedContent
 */

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);

$options = array('type' => 'object', 'subtype' => 'informe', 'limit' => $limit, 'offset' => $offset);

$list = elgg_get_entities($options);

# FIXME : there MUST be another way to set the count to limit the patinator
# FIXME : this slows down a lot, as queries are made twice
$options = array('type' => 'object', 'subtype' => 'informe', 'limit' => 0, 'offset' => $offset);
$list_count = count(elgg_get_entities($options));

$informes = array();

foreach($list as $informe) {
        $periodstamp = $informe->informe_period_y . str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);
	$informes[] = array('periodstamp' => $periodstamp, 'object' => $informe);

}

rsort($informes);

$i = array();

foreach($informes as $informe) {
	$i[] = $informe['object'];
}

$body = elgg_view_entity_list($list, array("count" => $list_count, "offset" => $offset, "limit" => $limit, "full_view" => false, "list_type_toggle" => true, "pagination" => true));
$title = elgg_echo('member_directory:member_directory_title');

$body = elgg_view_layout('default', array(
        'content' => $body,
        'title' => $title,
        'filter' => '',
        'header' => '',
        'full_view' => false,
        'pagination' => true,
));

echo "<table>";
echo elgg_view_page($title, $body);
echo "</table>";

