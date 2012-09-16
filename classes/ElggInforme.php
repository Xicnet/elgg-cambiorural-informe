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

		$this->attributes['subtype'] = "informe";

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

	public function getSummary() {
		return $this->topics . $this->outlook;
	}


	public function getBody() {
		// Compose body from report fields
		if (!empty($this->body)) {
			//return $this->body;
		}
		$body = "";

		// Read-only fields
		$ap = get_entity($this->meeting_ap);
		$pa = get_entity($this->meeting_pa);
		$body.= elgg_view('output/longtext', array('value' => '<b>Agente de Proyecto</b>'));
		$body.= elgg_view('output/longtext', array('value' => $ap->name));
		$body.= elgg_view('output/longtext', array('value' => '<b>Promotor Asesor</b>'));
		$body.= elgg_view('output/longtext', array('value' => $pa->name));
		$body.= elgg_view('output/longtext', array('value' => '<b>Nombre del representate</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_manager));
		$body.= elgg_view('output/longtext', array('value' => '<b>Establecimiento</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->building));
		$body.= elgg_view('output/longtext', array('value' => '<b>Fecha</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_date));
		$body.= elgg_view('output/longtext', array('value' => '<b>Lugar</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_place));
		$body.= elgg_view('output/longtext', array('value' => '<b>Cantidad de asistentes</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_assistance));
		$body.= elgg_view('output/longtext', array('value' => '<b>Temas tratados</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->topics));
		$body.= elgg_view('output/longtext', array('value' => '<b>Novedades</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->news));
		$body.= elgg_view('output/longtext', array('value' => '<b>Inquietudes y requerimientos</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->requirements));
		$body.= elgg_view('output/longtext', array('value' => '<b>Evaluación de la reunión</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->rating));
		$body.= elgg_view('output/longtext', array('value' => '<b>Aspectos positivos</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->pros));
		$body.= elgg_view('output/longtext', array('value' => '<b>Aspectos negativos</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->cons));
		$body.= elgg_view('output/longtext', array('value' => '<b>Comentarios</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_comments));
		$body.= elgg_view('output/longtext', array('value' => '<b>Evaluación de la situación productiva zonal</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->productiv));
		$body.= elgg_view('output/longtext', array('value' => '<b>Otros comentarios</b>'));
		$body.= elgg_view('output/longtext', array('value' => $this->other_comments));
		$body.='<p></p>';

		// Sections

		// 

		$body.= $this->description;

		$this->body = $body;
		return $this->body;
	}
}
