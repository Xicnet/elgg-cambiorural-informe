<?php
/**
 * Elgg file download.
 * 
 * @package ElggFile
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the guid
$file_guid = get_input("file_guid");

forward("informe/download/$file_guid");
