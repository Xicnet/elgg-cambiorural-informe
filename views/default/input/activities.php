<?php
$activity_label =  elgg_view('input/text', array('name' => 'activity_label'));
$activity_date = elgg_view('input/date', array(
        'name' => 'activity_date',
        'id' => 'activity_date',
        'value' => $vars['activity_date']
));

$activity_type = elgg_view(
			'input/radio', array('name' => 'activity_type',
			'options' => array(
				'Individual' => '1',
				'Grupal' => '2'
)));
$activity_comment = elgg_view('input/longtext', array('name' => 'activity_comment'));

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
