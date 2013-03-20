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

$limit = get_input("limit", 5);
$offset = get_input("offset", 0);

$options = array('type' => 'object', 'subtype' => 'informe',
                'limit' => $limit,
                'offset' => $offset,
                );

error_log(1);
$list = elgg_get_entities($options);
error_log(2);

$informes = array();

foreach($list as $informe) {
/*
        $informe_pa      = get_entity($informe->meeting_pa);
        $group           = get_entity($informe->container_guid);
        $period_stamp    = $informe->informe_period_y . str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);
        $informe_period  = $informe->informe_period_y ."/".str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);
        $informe_guid    = $informe->guid;
        $informe_title   = $informe->title;
        $group_guid      = $group->getGUID();
        $group_name      = $group->name;
        $group_pa        = $informe_pa->name;
	
        $informe_pa = get_entity($informe->meeting_pa);
        $group      = get_entity($informe->container_guid);
        $i[] = $informe->informe_period_y . str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);
        $i[] = $informe->informe_period_y ."/".str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);
        $i[] = $informe->guid;
        $i[] = "<a href=\"{$informe->getURL()}\">{$informe->title}</a>";
        $i[] = $group->getGUID();
        $i[] = "<a href=\"{$group->getURL()}\">{$group->name}</a>";
        if($informe_pa->name) {
	        $i[] = "<a href=\"{$informe_pa->getURL()}\">{$informe_pa->name}</a>";
	} else {
	        $i[] = "SIN PA";
	}

	$informes[] = $i;
*/

        $periodstamp = $informe->informe_period_y . str_pad($informe->informe_period_m, 2, 0, STR_PAD_LEFT);

	$informes[] = array('periodstamp' => $periodstamp, 'object' => $informe);

}

error_log("SORT START");
rsort($informes);
error_log("SORT END");

$i = array();

foreach($informes as $informe) {
	$i[] = $informe['object'];
}


error_log(3);
//function elgg_view_entity_list($entities, $vars = array(), $offset = 0, $limit = 10, $full_view = true, $list_type_toggle = true, $pagination = true) {
$body = elgg_view_entity_list($list, array("count" => 1000, "offset" => $offset, "limit" => $limit, "full_view" => false, "list_type_toggle" => true, "pagination" => true));
//$body = elgg_view_entity_list($i, $vars = array('full_view' => false), $offset = 0, $limit = 10, $pagination = true);
error_log(4);
$title = elgg_echo('member_directory:member_directory_title');
error_log(5);
$full_view = false;

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


error_log(6);

