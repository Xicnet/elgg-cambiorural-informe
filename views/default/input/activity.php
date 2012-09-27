<?php
$z = $vars['report_activities_count'];
$activity_label = elgg_view('input/text', array(
                                'name' => "activities[$z][title]"
));
$activity_date = elgg_view('input/date', array(
        'name' => "activities[$z][date]",
        'value' => $vars['activity_date']
));

$activity_type = elgg_view(
                        'input/radio', array(
                                'name' => "activities[$z][type]",
                        'options' => array(
                                'Individual' => '1',
                                'Grupal' => '2'
)));
$activity_notes = elgg_view('input/longtext', array(
                                                'name' => "activities[$z][notes]"
));

echo <<<___HTML

<div class="elgg-output elgg-activity activity-guid-$activity->guid">
        <label>Actividad :</label><br/>
        <label>Titulo:</label> $activities_label<br/>
        <label>Fecha:</label> $activity_date<br/>
        <label>Tipo:</label> $activity_type<br/>
        <label>Comentarios:</label> $activity_notes <br/>
        <br />
</div>

___HTML;
