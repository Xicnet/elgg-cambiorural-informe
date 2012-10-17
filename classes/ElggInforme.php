<?php
/**
 * Extended class to override the time_created
 *
 * @property string $status      The published status of the blog post (published, draft)
 * @property string $comments_on Whether commenting is allowed (Off, On)
 * @property string $excerpt     An excerpt of the blog post used when displaying the post
 */
class ElggInforme extends ElggObject {

	/**
	 * Set subtype to informe.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype']  = "informe";
	}

	/**
	 * Can a user comment on this blog?
	 *
	 * @see ElggObject::canComment()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		$result = parent::canComment($user_guid);
		if ($result == false) {
			return $result;
		}

		if ($this->comments_on == 'Off') {
			return false;
		}

		return true;
	}

	/**
	 * Can a user edit this report?
	 *
	 * @see ElggObject::canEdit()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.8
	 */
	public function canEdit($user_guid = 0) {

		$user = $user_guid == 0 ? elgg_get_logged_in_user_entity() : get_entity($user_guid);
		if (!elgg_instanceof($user, 'user')) {
			return FALSE;
		}
		$user_guid = $user->guid;
		$group = $this->getContainerEntity();

		return (parent::canEdit() && ($user_guid == $group->pa || $this->meeting_pa == $user_guid));
	}

	public function getSummary() {
		//return $this->topics;
		return '';
	}

	public function getBody() {
		// Compose body from report fields
		if (!empty($this->body)) {
			//return $this->body;
		}
		$body = "";

		// Read-only fields
		$group = get_entity($this->container_guid);
		$ap = get_entity($this->meeting_ap);
		$pa = get_entity($this->meeting_pa);
		$report_month = strftime('%B %Y', strtotime($this->informe_period_y."-".$this->informe_period_m));
		$meeting_date = strftime('%A %d %B %Y', strtotime($this->meeting_date));
		$topics = nl2br($this->topics);
		$news = nl2br($this->news);
		$requirements = nl2br($this->requirements);
		$pros = nl2br($this->pros);
		$cons = nl2br($this->cons);
		$meeting_comments = nl2br($this->meeting_comments);
		$activities = elgg_list_entities_from_relationship(array('relationship_guid' => $this->getGUID(), 'relationship' => 'report_activity'));

		// optional values
		$informe_productiv = empty($this->productiv) ? elgg_echo('informe:empty:field') : nl2br($this->productiv);
		$other_comments    = empty($this->other_comments) ? elgg_echo('informe:empty:field') : nl2br($this->other_comments);

		// entity links
		$options = array('text' => $group->name, 'href' => $group->getURL());
		$group_link = elgg_view('output/url',  $options);

		if (elgg_instanceof($ap, 'user')) {
			$options = array('text' => $ap->name, 'href' => $ap->getURL());
			$ap_link = elgg_view('output/url',  $options);
		}
		if (elgg_instanceof($pa, 'user')) {
			$options = array('text' => $pa->name, 'href' => $pa->getURL());
			$pa_link = elgg_view('output/url',  $options);
		}

$body .= <<<___HTML

<div>
	<label for="informe_period">Período</label>
	$report_month
	<span>$due_time</span>
</div>

<div>
	<label for="informe_container_guid">Grupo</label>
	$group_link
</div>

<div>
	<label for="informe_group_pa">Promotor Asesor</label>
	$pa_link
</div>

<div>
	<label for="informe_group_ap">Agente de Proyecto</label>
	$ap_link
</div>

<div>
	<label for="informe_group_responsible_label">Nombre del representante</label>
	$this->meeting_manager
</div>

<p>&nbsp;</p>

<div>
	<label for="informe_building">1. Reunión Mensual</label><br />
</div>

<div class='_block'>
	<div>
		<label for="informe_meeting_date">Fecha</label>
		$meeting_date
	</div>
	<div>
		<label for="informe_building">Establecimiento</label>
		$this->meeting_building
	</div>
	<div>
		<label for="informe_meeting_place">Lugar</label>
		$this->meeting_place
	</div>
	<div>
		<label for="informe_meeting_assistance">Cantidad de asistentes</label>
		$this->meeting_assistance
	</div>

	<br />
	<div class='_block'>
		<div>
			<label for="informe_topics">1.1. Temas tratados</label>
			<div class='_block'>
				<p>$topics</p>
			</div>
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_news">1.2. Novedades</label>
			<div class='_block'>
				<p>$news</p>
			</div>
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_requirements">1.3. Inquietudes y requerimientos</label>
			<div class='_block'>
				<p>$requirements</p>
			</div>
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_rating">1.4. Evaluación de la reunión</label>
		</div>
		<div class='_block'>
			<div>
				<label for="informe_rating_value">Calificación</label>
				$this->rating
			</div>
		</div>

		<div class='_block'>
			<div>
				<label for="informe_pros">Aspectos positivos</label>
				<p>$pros</p>
			</div>
		</div>

		<div class='_block'>
			<div>
				<label for="informe_cons">Aspectos negativos</label>
				<p>$cons</p>
			</div>
		</div>

		<div class='_block'>
			<div>
				<label for="informe_meeting_comments">Comentarios</label>
				<p>$meeting_comments</p>
			</div>
		</div>
	</div>
</div>

<p>&nbsp;</p>

<div>
	<label for="informe_productiv">2. Evaluación de la situación productiva zonal</label>
	<div class="_block">
		<p>$informe_productiv</p>
	</div>
</div>

<p>&nbsp;</p>

<div>
	<label for="informe_activities">3. Otras actividades desarrolladas durante el mes</label>
	<div class="_block">
		<div id="activities-block-container">
			<div class="activities-block">
				$activities
				<hr />
			</div>
		</div>
	</div>
</div>

<p>&nbsp;</p>

<div>
	<label for="informe_other_comments">4. Otros comentarios</label>
	<div class="_block">
		<p>$other_comments</p>
	</div>
</div>

___HTML;


		$this->body = $body;
		return $this->body;
	}
}
