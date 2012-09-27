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

$label = $entity->label;
$date  = strftime('', $entity->activity_date);

$type = $entity->activity_type;
$activity_notes = $entity->activity_notes;

?>
<div class="elgg-report-activity" id="report-activity-<?php echo $entity->guid; ?>">
    <h4><?php echo $label; ?></h4>
    <p>
      <span class="activity-date"><?php echo $date; ?></span>
      <span class="activity-type"><?php echo $type; ?></span>
      <span class="activity-notes"><?php echo $activity_notes; ?></span>
    </p>
</div>

