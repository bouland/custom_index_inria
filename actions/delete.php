<?php

	/**
	 * Elgg blog: delete post action
	 * 
	 * @package ElggBlog
	 */

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
		$guid = (int) get_input('filter_guid');
		
	// Make sure we actually have permission to edit
		$filter = get_entity($guid);
		if ($filter instanceof ElggFilter && $filter->canEdit()) {
	
		// Delete it!
				$rowsaffected = $filter->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("custom_index_inria:actions:deleted:success"));
				} else {
					register_error(elgg_echo("custom_index_inria:actions:deleted:fail"));
				}

		
		}
		forward($_SERVER['HTTP_REFERER']);
?>