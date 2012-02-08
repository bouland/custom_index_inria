<?php
function river_get_item_types(){
	global $CONFIG;
	$sql = "SELECT DISTINCT type,subtype,action_type" .
			 		" FROM {$CONFIG->dbprefix}river ORDER BY type,subtype,action_type";

	// Get data
	return get_data($sql);

}

function set_user_filter_setting(ElggUser $user, array $filters) {
	
	if (!$user) {
		$user = get_loggedin_user();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$user->river_filters = $filters;
		return $user->save();
	}

	return false;
}