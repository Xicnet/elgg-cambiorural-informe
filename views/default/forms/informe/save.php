<?php
/**
 * Edit informe form
 *
 * @package Blog
 */

$informe = get_entity($vars['guid']);
$vars['entity'] = $informe;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="message warning">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';
$preview_button = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/informe/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete elgg-state-disabled float-alt'
	));
}

// published informes do not get the preview button
if (!$vars['guid'] || ($informe && $informe->status != 'published')) {
	$preview_button = elgg_view('input/submit', array(
		'value' => elgg_echo('preview'),
		'name' => 'preview',
		'class' => 'mls',
	));
}

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
$action_buttons = $save_button . $preview_button . $delete_link;

$months = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

$informe_period_label = elgg_echo('Período');
$informe_period_m_value = $vars['informe_period_m'] ? $vars['informe_period_m'] : date('n');
$informe_period_m_input = elgg_view('input/dropdown', array('name' => 'informe_period_m', 'value' => $informe_period_m_value, 'options_values' => $months));
$informe_period_y_value = $vars['informe_period_y'] ? $vars['informe_period_y'] : date('Y');
$informe_period_y_input  = elgg_view('input/dropdown', array('name' => 'informe_period_y', 'value' => $informe_period_y_value, 'options_values' => array('2011' => '2011', '2012' => '2012')));

$topics_label = elgg_echo('Temas tratados');
$topics_input = elgg_view('input/text', array(
	'name' => 'topics',
	'id' => 'informe_topics',
	'value' => $vars['topics']
));

$news_label = elgg_echo('Novedades');
$news_input = elgg_view('input/text', array(
	'name' => 'news',
	'id' => 'informe_news',
	'value' => $vars['news']
));

$requirements_label = elgg_echo('Inquietudes y requerimientos');
$requirements_input = elgg_view('input/text', array(
	'name' => 'requirements',
	'id' => 'informe_requirements',
	'value' => $vars['requirements']
));

$rating_label = elgg_echo('Evaluación de la reunión');
$rating_input = elgg_view('input/dropdown', array(
	'name' => 'rating',
	'id' => 'informe_rating',
	'value' => $vars['rating'],
	'options_values' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10')

));

$pros_label = elgg_echo('Aspectos positivos');
$pros_input = elgg_view('input/text', array(
	'name' => 'pros',
	'id' => 'informe_pros',
	'value' => $vars['pros']
));

$cons_label = elgg_echo('Aspectos negativos');
$cons_input = elgg_view('input/text', array(
	'name' => 'cons',
	'id' => 'informe_cons',
	'value' => $vars['cons']
));

$meeting_comments_label = elgg_echo('Comentarios');
$meeting_comments_input = elgg_view('input/text', array(
	'name' => 'meeting_comments',
	'id' => 'informe_meeting_comments',
	'value' => $vars['meeting_comments']
));

$productiv_label = elgg_echo('Evaluación de la situación productiva zonal');
$productiv_input = elgg_view('input/text', array(
	'name' => 'productiv',
	'id' => 'informe_productiv',
	'value' => $vars['productiv']
));

if($vars['guid']) {
	$activities = elgg_get_entities_from_relationship(array('relationship_guid' => $vars['guid'], 'relationship' => 'report_activity', 'inverse_relationship' => false, 'order_by_metadata' => array( 'name' => 'date', 'direction' => ASC)));
}

$activities_label = elgg_echo('Otras actividades desarrolladas durante el mes');
$activities_input = elgg_view('input/activities', array('activities' => $activities));

$other_comments_label = elgg_echo('Otros comentarios');
$other_comments_input = elgg_view('input/text', array(
	'name' => 'other_comments',
	'id' => 'informe_other_comments',
	'value' => $vars['other_comments']
));

$days = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31', );

$years  = array('2011' => '2011', '2012' => '2012');

$group_responsible_label = elgg_echo('Nombre del representante');
$group_responsible_input = elgg_view('input/text', array(
	'name' => 'meeting_manager',
	'id' => 'meeting_manager',
	'value' => $vars['meeting_manager']
));

$meeting_date_label = elgg_echo('Fecha');
$meeting_date_input = elgg_view('input/date', array(
	'name' => 'meeting_date',
	'id' => 'meeting_date',
	'value' => $vars['meeting_date']
));

$building_label = elgg_echo('Establecimiento');
$building_input = elgg_view('input/text', array(
	'name' => 'building',
	'id' => 'informe_building',
	'value' => $vars['building']
));

$meeting_place_label = elgg_echo('Lugar');
$meeting_place_input = elgg_view('input/text', array(
	'name' => 'meeting_place',
	'id' => 'informe_meeting_place',
	'value' => $vars['meeting_place']
));

$meeting_assistance_label = elgg_echo('Cantidad de asistentes');
$meeting_assistance_input = elgg_view('input/text', array(
	'name' => 'meeting_assistance',
	'id' => 'informe_meeting_assistance',
	'value' => $vars['meeting_assistance']
));

$save_status = elgg_echo('informe:save_status');
if ($vars['guid']) {
	$entity = get_entity($vars['guid']);
	$saved = date('F j, Y @ H:i', $entity->time_created);
} else {
	$saved = elgg_echo('informe:never');
}

$status_label = elgg_echo('informe:status');
$status_input = elgg_view('input/dropdown', array(
	'name' => 'status',
	'id' => 'informe_status',
	'value' => $vars['status'],
	'options_values' => array(
		'draft' => elgg_echo('informe:status:draft'),
		'published' => elgg_echo('informe:status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/dropdown', array(
	'name' => 'comments_on',
	'id' => 'informe_comments_on',
	'value' => $vars['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'id' => 'informe_access_id',
	'value' => $vars['access_id']
));

$categories_input = elgg_view('input/categories', $vars);

// hidden inputs
if (elgg_instanceof(elgg_get_page_owner_guid(), 'group')) {
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
} else {
$container_guid_input = '';
}
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));

$group = get_entity(elgg_get_page_owner_guid());

if (!elgg_instanceof($group, 'group')) {

	$group_options = array();

	$um = get_users_membership(elgg_get_logged_in_user_guid());

	foreach ($um AS $g) {
		$group_options[$g->getGUID()] = $g->name;
	}

	$group_label = elgg_echo('group');
	$group_input = elgg_view('input/dropdown', array(
		'name' => 'informe_container_guid',
		'id' => 'group_guid_picker',
		'value' => $vars['group_guid'],
		'options_values' => $group_options,
	));
} else {
	$group_label = elgg_echo('group');
	$group_input = elgg_view('output/url', Array('text' => $group->name, 'href' => $group->getURL()));
	$group_input_hidden = elgg_view('input/hidden', Array('name' => 'container_guid', 'value' => $group->guid));

	$group_pa = get_entity($group->pa);
	$group_pa_label = elgg_echo('Promotor Asesor');
	$group_pa_input = elgg_view('output/url', Array('text' => $group_pa->name, 'href' => $group_pa->getURL()));
	$group_pa_hidden = elgg_view('input/hidden', array('name' => 'meeting_pa', 'value' => $group->pa));

	$group_ap = get_entity($group->ap);
	$group_ap_label = elgg_echo('Agente de Proyecto');
	$group_ap_input = elgg_view('output/url', Array('text' => $group_ap->name, 'href' => $group_ap->getURL()));
	$group_ap_hidden = elgg_view('input/hidden', array('name' => 'meeting_ap', 'value' => $group->ap));

}

$due_time = is_null($informe->due_time) ? '' : 'Fecha límite: ' . strftime("%d/%m/%Y", $informe->due_time);

echo <<<___HTML

$draft_warning

<style>
.elgg-input-longtext {
        height: 50px;
}
label{
}
._activity{

}
._h{
        display:none;
}
._block{
        padding:15px;
        margin-left:25px;
        background-color: rgba(20%, 20%, 20%, 0.1);
}

h1{
        font-size:20px;
}
</style>

<script language="javascript">
function add_activity() {
	var i = $(".activities-block").children().size();
	var input_parts;
	var clone = $(".activities-block .elgg-activity:first-child").clone();
	/*
	clone.find('input, textarea').each(function() {
			$(this).attr('name', $(this).attr('name').replace('[0]', '['+ i +']')).attr('value', '');
			if($(this).is('.elgg-input-date')) {
				$(this).attr('id', 'dp0');
			}
	});
	clone.appendTo('#activities-block-container .activities-block:first-child');
			elgg.ui.initDatePicker();
	*/
	elgg.get('/informe/inputactivity?c=' + i, {
		html: 'html',
		success: function(resultText, success, xhr) {
			$('#activities-block-container .activities-block:first-child').append(resultText);
			elgg.ui.initDatePicker();
		}
	});
	return false;
}
</script>

<div>
	<label for="informe_period">$informe_period_label</label>
	$informe_period_m_input / $informe_period_y_input
	<span>$due_time</span>
</div>

<div>
	<label for="informe_container_guid">$group_label</label>
	$group_input
	$group_input_hidden
</div>

<div>
	<label for="informe_group_pa">$group_pa_label</label>
	$group_pa_input
	$group_pa_hidden
</div>

<div>
	<label for="informe_group_ap">$group_ap_label</label>
	$group_ap_input
	$group_ap_hidden
</div>

<div>
	<label for="informe_group_responsible_label">$group_responsible_label</label>
	$group_responsible_input
</div>

<div>
	<label for="informe_building">1. Reunión Mensual</label><br />
</div>

<div class='_block'>
	<div>
		<label for="informe_meeting_date">$meeting_date_label</label>
		$meeting_date_input
	</div>
	<div>
		<label for="informe_building">$building_label</label>
		$building_input
	</div>
	<div>
		<label for="informe_meeting_place">$meeting_place_label</label>
		$meeting_place_input
	</div>
	<div>
		<label for="informe_meeting_assistance">$meeting_assistance_label</label>
		$meeting_assistance_input
	</div>

	<br />
	<div class='_block'>
		<div>
			<label for="informe_topics">1.1. $topics_label</label>
			$topics_input
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_news">1.2. $news_label</label>
			$news_input
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_requirements">1.3. $requirements_label</label>
			$requirements_input
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_rating">1.4. $rating_label</label>
		</div>
		<div class='_block elgg-report-activity'>
			<div>
				<label>Califique la reunión (1 ~ 10):</label><br />
				$rating_input
			</div>
			<div>
				<label for="informe_pros">$pros_label</label>
				$pros_input
			</div>
			<div>
				<label for="informe_cons">$cons_label</label>
				$cons_input
			</div>
			<div>
				<label for="informe_meeting_comments">$meeting_comments_label</label>
				$meeting_comments_input
			</div>
		</div>
	</div>
</div>

<div>
	<label for="informe_productiv">2. $productiv_label</label>
	$productiv_input
</div>

<div>
	<label for="informe_activities">3. $activities_label</label>
	<div class="_block">
		<div id="activities-block-container">
			<div class="activities-block">
				$activities_input
			</div>
		</div>
		<a href="#" onclick="add_activity(); return false;">Agregar actividad</a>
	</div>
</div>

<div>
	<label for="informe_other_comments">4. $other_comments_label</label>
	$other_comments_input
</div>

$categories_input

<div>
	<label for="informe_comments_on">$comments_label</label>
	$comments_input
</div>

<div>
	<label for="informe_access_id">$access_label</label>
	$access_input
</div>

<div>
	<label for="informe_status">$status_label</label>
	$status_input
</div>

<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="informe-save-status-time">$saved</span>
	</div>

	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;
