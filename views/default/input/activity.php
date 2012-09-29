<?php
$z = $vars['report_activities_count'];

$entity = elgg_extract('entity', $vars);

$activity_label = elgg_view('input/text', array(
                                'name' => "activities[$z][title]",
				'value' => $entity->title,
));


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

echo <<<___HTML

<div class="elgg-output elgg-activity">
        <h4>Actividad</h4>
        $activity_label
        <h4>Fecha</h4>
        <span class="elgg-date"> $activity_date</span>
        <h4>Tipo</h4>
        $activity_scope
        <h4>Comentarios</h4>
	$activity_notes
</div>

___HTML;
