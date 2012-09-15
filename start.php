<?php
/**
 * Informes
 *
 * @package Informe
 *
 * @todo
 * - Either drop support for "publish date" or duplicate more entity getter
 * functions to work with a non-standard time_created.
 * - Pingbacks
 * - Notifications
 * - River entry for posts saved as drafts and later published
 */

elgg_register_event_handler('init', 'system', 'informe_init');

/**
 * Init informe plugin.
 */
function informe_init() {

	elgg_register_library('elgg:informe', elgg_get_plugins_path() . 'informe/lib/informe.php');

	// add a site navigation item
	$item = new ElggMenuItem('informe', elgg_echo('informe:informes'), 'informe/all');
	elgg_register_menu_item('site', $item);

	elgg_register_event_handler('upgrade', 'upgrade', 'informe_run_upgrades');

	// add to the main css
	elgg_extend_view('css/elgg', 'informe/css');

	// register the informe's JavaScript
	$informe_js = elgg_get_simplecache_url('js', 'informe/save_draft');
	elgg_register_simplecache_view('js/informe/save_draft');
	elgg_register_js('elgg.informe', $informe_js);

	// routing of urls
	elgg_register_page_handler('informe', 'informe_page_handler');

	// override the default url to view a informe object
	elgg_register_entity_url_handler('object', 'informe', 'informe_url_handler');

	// notifications
	register_notification_object('object', 'informe', elgg_echo('informe:newpost'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'informe_notify_message');

	// add informe link to
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'informe_owner_block_menu');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'informe_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'informe_pingback_subtypes');

	// Register for search.
	elgg_register_entity_type('object', 'informe');
	// @todo check if blog is already registered:
//	elgg_register_entity_type('object', 'blog');

	// Add group option
	add_group_tool_option('informe', elgg_echo('informe:enableblog'), true);
	elgg_extend_view('groups/tool_latest', 'informe/group_module');

	// add a informe widget
	elgg_register_widget_type('informe', elgg_echo('informe'), elgg_echo('informe:widget:description'));

	// Add admin menu item
	elgg_register_admin_menu_item('administer', 'informer', 'statistics');

	elgg_register_widget_type(
			'group_reports',
			elgg_echo('informer:widget:group_report'),
			elgg_echo('informer:widget:group_report:description'),
			'admin');

	// register actions
	$action_path = elgg_get_plugins_path() . 'informe/actions/informe';
	elgg_register_action('informe/save', "$action_path/save.php");
	elgg_register_action('informe/auto_save_revision', "$action_path/auto_save_revision.php");
	elgg_register_action('informe/delete', "$action_path/delete.php");

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'informe_entity_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'informe_ecml_views_hook');
}

/**
 * Dispatches informe pages.
 * URLs take the form of
 *  All informes:       informe/all
 *  User's informes:    informe/owner/<username>
 *  Friends' informe:   informe/friends/<username>
 *  User's archives: informe/archives/<username>/<time_start>/<time_stop>
 *  Informe post:       informe/view/<guid>/<title>
 *  New post:        informe/add/<guid>
 *  Edit post:       informe/edit/<guid>/<revision>
 *  Preview post:    informe/preview/<guid>
 *  Group informe:      informe/group/<guid>/all
 *
 * Title is ignored
 *
 * @todo no archives for all informes or friends
 *
 * @param array $page
 * @return bool
 */
function informe_page_handler($page) {

	elgg_load_library('elgg:informe');

	// @todo remove the forwarder in 1.9
	// forward to correct URL for informe pages pre-1.7.5
	informe_url_forwarder($page);

	// push all informes breadcrumb
	elgg_push_breadcrumb(elgg_echo('informe:informes'), "informe/all");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = informe_get_page_content_list($user->guid);
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			$params = informe_get_page_content_friends($user->guid);
			break;
		case 'archive':
			$user = get_user_by_username($page[1]);
			$params = informe_get_page_content_archive($user->guid, $page[2], $page[3]);
			break;
		case 'view':
		case 'read': // Elgg 1.7 compatibility
			$params = informe_get_page_content_read($page[1]);
			break;
		case 'add':
			gatekeeper();
			$params = informe_get_page_content_edit($page_type, $page[1]);
			break;
		case 'edit':
			gatekeeper();
			$params = informe_get_page_content_edit($page_type, $page[1], $page[2]);
			break;
		case 'group':
			if ($page[2] == 'all') {
				$params = informe_get_page_content_list($page[1]);
			} else {
				$params = informe_get_page_content_archive($page[1], $page[3], $page[4]);
			}
			break;
		case 'report':
			$params = informe_get_page_content_report($page[1], $page[2]);
			break;
		case 'all':
			$params = informe_get_page_content_list();
			break;
		default:
			return false;
	}

	if (isset($params['sidebar'])) {
		$params['sidebar'] .= elgg_view('informe/sidebar', array('page' => $page_type));
	} else {
		$params['sidebar'] = elgg_view('informe/sidebar', array('page' => $page_type));
	}

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);
	return true;
}

/**
 * Format and return the URL for informes.
 *
 * @param ElggObject $entity Informe object
 * @return string URL of informe.
 */
function informe_url_handler($entity) {
	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "informe/view/{$entity->guid}/$friendly_title";
}

/**
 * Add a menu item to an ownerblock
 */
function informe_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "informe/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('informe', elgg_echo('informe'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->informe_enable != "no") {
			$url = "informe/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('informe', elgg_echo('informe:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add particular informe links/info to entity menu
 */
function informe_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'informe') {
		return $return;
	}

	if ($entity->canEdit() && $entity->status != 'published') {
		$status_text = elgg_echo("informe:status:{$entity->status}");
		$options = array(
			'name' => 'published_status',
			'text' => "<span>$status_text</span>",
			'href' => false,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Set the notification message body
 * 
 * @param string $hook    Hook name
 * @param string $type    Hook type
 * @param string $message The current message body
 * @param array  $params  Parameters about the informe posted
 * @return string
 */
function informe_notify_message($hook, $type, $message, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (elgg_instanceof($entity, 'object', 'informe')) {
		$descr = $entity->excerpt;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('informe:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Register informes with ECML.
 */
function informe_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/informe'] = elgg_echo('informe:informes');

	return $return_value;
}

/**
 * Upgrade from 1.7 to 1.8.
 */
function informe_run_upgrades($event, $type, $details) {
	$informe_upgrade_version = elgg_get_plugin_setting('upgrade_version', 'informes');

	if (!$informe_upgrade_version) {
		 // When upgrading, check if the ElggInforme class has been registered as this
		 // was added in Elgg 1.8
		if (!update_subtype('object', 'informe', 'ElggInforme')) {
			add_subtype('object', 'informe', 'ElggInforme');
		}

		elgg_set_plugin_setting('upgrade_version', 1, 'informes');
	}
}
