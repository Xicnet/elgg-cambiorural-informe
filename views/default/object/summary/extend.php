<?php
/*

 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity menu and metadata (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['tags']      HTML for the tags (default is tags on entity, pass false for no tags)
 * @uses $vars['content']   HTML for the entity content (optional)
 */

if (elgg_instanceof($vars['entity'], 'object', 'informe')) {

	$informe = $vars['entity'];


	echo elgg_view('output/longtext', array(
        	        'value' => $informe->getSummary(),
                	'class' => 'blog-post',
	        ));

}
