<?php
/**
 * View for report_activity objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, FALSE);
$entity = elgg_extract('entity', $vars, FALSE);

if (!$entity) {
    return TRUE;
}

$owner = $entity->getOwnerEntity();
$container = $entity->getContainerEntity();

$title = $entity->title;
$date  = strftime('%A %d %B %Y', strtotime($entity->date));
$scope = $entity->scope;
$notes = $entity->notes;

echo <<<___HTML

<div class="elgg-report-activity" id="report-activity-{$entity->guid}">
    <h4>$title</h4>
    <p>
      <span class="activity-date">$date</span>
      <span class="activity-scope">$scope</span>
      <span class="activity-notes">$notes</span>
    </p>
</div>

___HTML;

