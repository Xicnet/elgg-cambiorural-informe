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

//		error_log("====== ElggInforme canEdit($user_guid) meeting_pa = {$this->meeting_pa}");
		return (parent::canEdit() && $this->meeting_pa == $user_guid);
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
		$activities = elgg_list_entities_from_relationship(array('relationship_guid' => $this->getGUID(), 'relationship' => 'report_activity'));

$body .= <<<___HTML

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

<div>
	<label for="informe_period">Período</label>
	$report_month
	<span>$due_time</span>
</div>

<div>
	<label for="informe_container_guid">Grupo</label>
	$group->name
</div>

<div>
	<label for="informe_group_pa">Promotor Asesor</label>
	$pa->name
</div>

<div>
	<label for="informe_group_ap">Agente de Proyecto</label>
	$ap->name
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
		$this->meeting_date
	</div>
	<div>
		<label for="informe_building">Establecimiento</label>
		$this->building
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
				<p>$this->topics</p>
			</div>
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_news">1.2. Novedades</label>
			<div class='_block'>
				<p>$this->news</p>
			</div>
		</div>
	</div>

	<div class='_block'>
		<div>
			<label for="informe_requirements">1.3. Inquietudes y requerimientos</label>
			<div class='_block'>
				<p>$this->requirements</p>
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
				<p>$this->pros</p>
			</div>
		</div>

		<div class='_block'>
			<div>
				<label for="informe_cons">Aspectos negativos</label>
				<p>$this->cons</p>
			</div>
		</div>

		<div class='_block'>
			<div>
				<label for="informe_meeting_comments">Comentarios</label>
				<p>$this->meeting_comments</p>
			</div>
		</div>
	</div>
</div>

<p>&nbsp;</p>

<div>
	<label for="informe_productiv">2. Evaluación de la situación productiva zonal</label>
	<div class="_block">
		<p>$this->productiv</p>
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
		<p>$this->other_comments</p>
	</div>
</div>

___HTML;


		$this->body = $body;
		return $this->body;
	}
}
