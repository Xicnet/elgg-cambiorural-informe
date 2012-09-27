<?php
/**
 * Extended class to override the time_created
 *
 * @property string $status      The published status of the blog post (published, draft)
 * @property string $comments_on Whether commenting is allowed (Off, On)
 * @property string $excerpt     An excerpt of the blog post used when displaying the post
 */
class ElggReportActivity extends ElggObject {

	/**
	 * Set subtype to informe.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype']  = "report_activity";
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
		return (parent::canEdit() && $this->group_pa == $user_guid);
	}
}
