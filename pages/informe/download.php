<?php
error_log("aca");
/**
 * Elgg file download.
 *
 * @package ElggFile
 */

$stats_type = get_input("stats_type");

switch ($stats_type) {
	case 'informe_stats':
		$filename = "$stats_type.csv";
		$filepath = "/tmp/$filename";
		break;
	case 'user_stats':
		$filename = "$stats_type.csv";
		$filepath = "/tmp/$filename";
		break;
	default:
		return false;
}


$mime = "application/octet-stream";


// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($filepath);
exit;
