<?php
$z = $vars['report_activities_count'];

$entity = elgg_extract('entity', $vars);

$activity_label = elgg_view('input/text', array(
                                'name' => "activities[$z][title]",
				'value' => $entity->title,
));


if($entity->getGUID()) {
	$activity_guid = $entity->getGUID();
	$activity_guid_hidden = elgg_view('input/hidden', Array('name' => "activities[$z][guid]", 'value' => $activity_guid));
} else {
	$activity_guid_hidden = '';
}

if (empty($vars['activity_date'])) {
    $activity_date = NULL;
} else {
    $activity_date = strftime('%B %d %F %Y', $entity->date);
}
$activity_date = elgg_view('input/date', array(
        'name' => "activities[$z][date]",
        'value' => $activity_date
));


$activity_scope = elgg_view(
                        'input/radio', array(
                                'name' => "activities[$z][scope]",
				'value' => $entity->scope,
                        'options' => array(
                                'Individual' => '1',
                                'Grupal' => '2'
)));
$activity_notes = elgg_view('input/longtext', array(
                                                'name' => "activities[$z][notes]",
	'value' => $entity->notes,
));

if ($entity->canEdit()) {

	$options = array('guid' => $entity->guid);

	$delete = elgg_view('output/confirmlink', 
		array(	'text' => '<span class="elgg-icon elgg-icon-delete-alt float-alt"></span>',
			'href' => 'action/informe/deleteactivity?' . http_build_query($options),
			'is_action' => TRUE,
			'confirm' => elgg_echo('informe:confirm:delete_activity'),
		));

} else { $delete = ''; }

echo <<<___HTML

<div class="elgg-output elgg-activity">
        <h4>Actividad $delete</h4>
        $activity_guid_hidden
        $activity_label
        <h4>Fecha</h4>
        <span class="elgg-date"> $activity_date</span>
        <h4>Tipo</h4>
        $activity_scope
        <h4>Comentarios</h4>
	$activity_notes
</div>

___HTML;
