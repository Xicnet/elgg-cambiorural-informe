<?php
/**
 * Elgg informe browser download action.
 *
 * @package ElggFile
 */

// @todo this is here for backwards compatibility (first version of embed plugin?)
$download_page_handler = elgg_get_plugins_path() . 'informe/download.php';

include $download_page_handler;
