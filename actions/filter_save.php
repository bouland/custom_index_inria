<?php

gatekeeper();

$user_guid = get_input('user_guid');
$user = get_entity($guid);
if( ! ( $user && $user instanceof ElggUser ) ) {
	$user = get_loggedin_user();
}

$filters = get_input('filters');
if ($filters && is_array($filters)) {
	
	if (set_user_filter_setting($user, $filters)){
	
		system_message(elgg_echo('custom_index_inria:filter:save:success'));
	
	}else{
		
		register_error(elgg_echo('custom_index_inria:filter:save:failed'));
	}
}else{
	register_error(elgg_echo('custom_index_inria:filter:form:filtersnotvalid'));	
}

forward($_SERVER['HTTP_REFERER']);