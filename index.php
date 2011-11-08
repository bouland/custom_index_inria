<?php

	/**
	 * Elgg custom index
	 * 
	 * @package ElggCustomIndex
	 */

	// Get the Elgg engine
	require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");
	global $CONFIG;
	
    if (!isloggedin()){
    	//grab the login form
		$sidebar = elgg_view('custom_index_inria/login', array('login' => elgg_view("account/forms/login")));
		
    } else {
    	$featured_groups = elgg_get_entities_from_metadata(array('metadata_name' => 'featured_group', 'metadata_value' => 'yes', 'types' => 'group', 'limit' => 6, 'full_view' => FALSE));
    	$sidebar = elgg_view("groups/featured", array('featured' => $featured_groups));
   		 if (is_plugin_enabled('edifice')) {
			$sidebar .= elgg_view("theme_inria/sidemenu_categories");
		}
    	$sidebar .= elgg_view("custom_index_inria/onlinemembers");
		$sidebar .= elgg_view("riverdashboard/newestmembers");
		
    }
	
		
	$content = strip_tags(get_input('content',''));
	$content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
	$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8', false);
	
	$content = explode(',' ,$content);
	$type = $content[0];
	if (isset($content[1])) {
		$subtype = $content[1];
	} else {
		$subtype = '';
	}
	
	// only allow real and registered types
	switch($type) {
		case 'user':
		case 'object':
		case 'group':
		case 'site':
			break;
	
		default:
			$type = '';
			break;
	}
	
	// only allow real and registered subtypes
	$registered_entities = get_registered_entity_types($type);
	
	if (!in_array($subtype, $registered_entities)) {
		$subtype = '';
	}
	
	$orient = get_input('display');
	$callback = get_input('callback');
	
	if ($type == 'all') {
		$type = '';
		$subtype = '';
	}
	$limit = (Int)get_plugin_setting('nbWire','custom_index_inria');
	
	$wire = elgg_view_river_items(  0,
									0,
									'',
									'object',
									'thewire',
									'',
									$limit,
									0,
									0,
									false);
	$nav = elgg_view('custom_index_inria/nav',array(
													'type' => $type,
													'subtype' => $subtype,
													'orient' => $orient
												));
	
	// dig the river
	$limit = (Int)get_plugin_setting('nbRiver','custom_index_inria');
	$river = elgg_view_river_items( $subject_guid,
									0,
									$relationship_type,
									$type,
									$subtype,
									'',
									$limit,
									0,
									0,
									true);
	// Replacing callback calls in the nav with something meaningless
	$river = str_replace('callback=true', 'replaced=88,334', $river);
	$ajax_content = $nav . $river . elgg_view('riverdashboard/js');
	if (empty($callback)) {
		// create our view
		If ($wire)
			$area2 .= elgg_view('custom_index_inria/index_box', array('title' => '<a href="'.($CONFIG->url).'pg/thewire/all">'.elgg_echo('inria:thewire').'</a>',
															  'body'  => $wire));
		If ($river)
			$area2 .= elgg_view('custom_index_inria/index_box', array('title' => '<a href="'.($CONFIG->url).'pg/activity">'.elgg_echo('inria:news').'</a>',
																  'body'  => elgg_view('riverdashboard/container', array('body' => $ajax_content)) ));
		//$area2 .= elgg_view('riverdashboard/js');
		$area2 .= elgg_view('custom_index_inria/extend', $vars);
		//display the contents 
	
	    $body = elgg_view_layout('sidebar_boxes', $sidebar, $area2);
	    page_draw($title, $body);
	} else {
		// ajax callback
		header("Content-type: text/html; charset=UTF-8");
		echo $ajax_content . elgg_view('riverdashboard/js');
	}

?>