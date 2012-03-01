<?php

	/**
	 * INRIA Elgg custom index page
	 * 
	 * @package ElggIndexCustom
	 */

    function custom_index_inria_init() {

    	global $CONFIG;
    	
    	require_once dirname(__FILE__) . "/lib/custom_index_inria.php";
    	require_once dirname(__FILE__) . "/lib/river_filter.php";
    	require_once dirname(__FILE__) . "/lib/river_type.php";
    	
    	register_elgg_event_handler('pagesetup', 'system', 'custom_index_inria_pagesetup');
    	
    	register_page_handler('settings','custom_index_inria_page_handler');
    	
    	register_action('filter/save', FALSE, $CONFIG->pluginspath . "custom_index_inria/actions/save.php");
    	register_action('filter/delete', FALSE, $CONFIG->pluginspath . "custom_index_inria/actions/delete.php");
    	
    	run_function_once("river_filter_run_once");
    	run_function_once("custom_index_inria_run_once");
        // Extend system CSS with our own styles
		elgg_extend_view('css','custom_index_inria/css');
		elgg_extend_view('css','filter/css');
		elgg_extend_view('metatags','custom_index_inria/metatags');
       	// Replace the default index page
       	// Replace the custom_index page
	   	register_plugin_hook('index','system','custom_index_inria_index');
   }
    
    function custom_index_inria_index() {
			
			if (!include_once(dirname(__FILE__) . "/index.php")) return false;
			return true;
			
	}
	function custom_index_inria_run_once()
	{
		$filter = new ElggFilter(get_plugin_setting('default_filter', 'custom_index_inria'));
		$filter->owner_guid = 0;
		$filter->container_guid = 0;
		$filter->access_id = ACCESS_PUBLIC;
		$filter->save();
		$filter->river_types= array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26);
		$filter->title='Toutes';
		$filter->save();
		set_plugin_setting('default_filter', $filter->guid, 'custom_index_inria');
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