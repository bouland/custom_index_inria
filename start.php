<?php

	/**
	 * INRIA Elgg custom index page
	 * 
	 * @package ElggIndexCustom
	 */

    function custom_index_inria_init() {

    	require_once dirname(__FILE__) . "/lib/custom_index_inria.php";
    	require_once dirname(__FILE__) . "/lib/river_filter.php";
    	require_once dirname(__FILE__) . "/lib/river_type.php";
    	
    	register_elgg_event_handler('pagesetup', 'system', 'custom_index_inria_pagesetup');
    	
    	register_page_handler('settings','custom_index_inria_page_handler');
    	
        // Extend system CSS with our own styles
		elgg_extend_view('css','custom_index_inria/css');
				
       	// Replace the default index page
       	// Replace the custom_index page
	   	register_plugin_hook('index','system','custom_index_inria_index');
   }
    
    function custom_index_inria_index() {
			
			if (!include_once(dirname(__FILE__) . "/index.php")) return false;
			return true;
			
	}
	function custom_index_inria_pagesetup() {
		global $CONFIG;
		if (get_context() == 'settings') {
			$user = get_loggedin_user();
			add_submenu_item(elgg_echo('custom_index_inria:settings:filter'), $CONFIG->wwwroot . "pg/settings/filter/{$user->username}/");
		}
	}
	function custom_index_inria_page_handler($page) {
		global $CONFIG;
	
		// default to personal notifications
		if (!isset($page[0])) {
			$page[0] = '';
		}
		set_input('username', $page[1]);
		
		switch ($page[0]) {
			case 'filter':
				require $CONFIG->pluginspath . "custom_index_inria/filter.php";
				break;
			default:
				usersettings_page_handler($page);
				break;
		}
		
		return TRUE;
	}
	
	register_elgg_event_handler('init','system','custom_index_inria_init');

?>