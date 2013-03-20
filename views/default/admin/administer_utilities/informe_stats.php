<style>
tr {
}
td {
	border: 1px solid orange;
	padding: 3px;
}
</style>
<?php
/**
 * Elgg Reported content admin page
 *
 * @package ElggReportedContent
 */

$options = array('type' => 'object', 'subtype' => 'informe',
                'limit' => 200
                );

$list = elgg_get_entities($options);

$informes = array();

echo "<table>";
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
*/
	
	$i = array();
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
}

foreach($informes as $informe) {
	echo "<tr>";
	echo "<td>$informe[1]</td>";
	echo "<td>$informe[2]</td>";
	echo "<td>$informe[3]</td>";
	echo "<td>$informe[4]</td>";
	echo "<td>$informe[5]</td>";
	echo "<td>$informe[6]</td>";
	echo "</tr>";
}

echo "</table>";
