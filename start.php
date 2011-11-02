<?php

	/**
	 * INRIA Elgg custom index page
	 * 
	 * @package ElggIndexCustom
	 */

	 
    function index_inria_init() {
	
        // Extend system CSS with our own styles
		elgg_extend_view('css','custom_index_inria/css');
				
       	// Replace the default index page
       	// Replace the custom_index page
	   	register_plugin_hook('index','system','custom_index_inria');
    }
    
    function custom_index_inria() {
			
			if (!include_once(dirname(__FILE__) . "/index.php")) return false;
			return true;
			
		}


    // Make sure the
		    register_elgg_event_handler('init','system','index_inria_init');

?>