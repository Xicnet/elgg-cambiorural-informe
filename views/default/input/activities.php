<?php
$activity_label =  elgg_view('input/text', array(
				'name' => 'activitylabel[]'
));
$activity_date = elgg_view('input/date', array(
        'name' => 'activitydate[]',
        'value' => $vars['activity_date']
));

$activity_type = elgg_view(
			'input/radio', array(
				'name' => 'activitytype[]',
			'options' => array(
				'Individual' => '1',
				'Grupal' => '2'
)));
$activity_comment = elgg_view('input/longtext', array(
						'name' => 'activitycomment[]'
));

echo <<<___HTML

<div class="elgg-output elgg-activity">
	<label>Actividad :</label><br/>
	<label>Titulo:</label> $activity_label<br/>
	<label>Fecha:</label> $activity_date<br/>
	<label>Tipo:</label> $activity_type<br/>
	<label>Comentarios:</label> $activity_comment <br/>
	<br />
</div>

___HTML;
