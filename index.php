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
    	//$sidebar = elgg_view("groups/featured", array('featured' => $featured_groups));
    	$sidebar = elgg_view("custom_index_inria/populargroups");
   		if (is_plugin_enabled('edifice')) {
			$sidebar .= elgg_view("theme_inria/sidemenu_categories");
		}
    	$sidebar .= elgg_view("custom_index_inria/onlinemembers");
		$sidebar .= elgg_view("riverdashboard/newestmembers");
		
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
	
	$offset = (int) get_input('offset',0);
	$orient = get_input('orient','all');
	$callback = get_input('callback');
	$limit = (Int)get_plugin_setting('nbRiver','custom_index_inria');
	$filter_guid = strip_tags(get_input('filter_guid',''));
	$filter_guid = mb_convert_encoding($filter_guid, 'HTML-ENTITIES', 'UTF-8');
	$filter_guid = htmlspecialchars($filter_guid, ENT_QUOTES, 'UTF-8', false);
	
	
	$filters = get_default_filters();
	if(is_array($filters)){
		$user_filters = get_user_filters(get_loggedin_user());
		if(is_array($user_filters)){
			$filters = array_merge($filters,$user_filters);
			usort($filters,'cmp_filters');
		}
		if (empty($filter_guid)) {
			$filter = $filters[0];
		}else{
			$filter = get_entity($filter_guid);
		}
		if($filter instanceof ElggFilter)
		{
			switch($orient) {
				case 'mine':
					$subject_guid = get_loggedin_userid();
					$relationship_type = '';
					break;
				case 'friends':
					$subject_guid = get_loggedin_userid();
					$relationship_type = 'friend';
					break;
				default:
					$subject_guid = 0;
					$relationship_type = '';
					break;
			}
	
			$items = get_river_items_filtered(array(	'filter' 	=> 	$filter,
														'limit'		=>	($limit + 1),
														'offset'	=>	$offset,
														'subject_guid'			=> 	$subject_guid,
														'subject_relationship' 	=> 	$relationship_type));
			
		}
		$nav = elgg_view('custom_index_inria/nav',array(	'filters' 		=> $filters,
															'filter_guid' 	=> $filter_guid,
															'orient' 		=> $orient));
		$river = elgg_view('river/item/list',array(	'limit' => $limit,
													'offset' => $offset,
													'items' => $items,
													'pagination' => true));
		// Replacing callback calls in the nav with something meaningless
		$river = str_replace('callback=true', 'replaced=88,334', $river);
		$ajax_content = $nav . $river . elgg_view('riverdashboard/js');
	}
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