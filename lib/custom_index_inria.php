<?php
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