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
		$body.= elgg_view('output/longtext', array('value' => $this->building));
		$body.= elgg_view('output/longtext', array('value' => $this->meeting_place));

		// Sections

		// 

		$body.= $this->description;

		$this->body = $body;
		return $this->body;
	}
}
