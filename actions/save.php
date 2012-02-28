<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");

gatekeeper();

$filter_label = get_input('filter_label');

$filter_guid = get_input('filter_guid');

$filter_first = get_input('filter_first');

$filter_river_type_ids = get_input('filter_river_type_ids');

$user_guid = get_input('user_guid');

if( ! $user = get_user($user_guid))
{
	$user = get_loggedin_user();
}

if ( ! empty($filter_label) && ! empty($filter_river_type_ids) && ! empty($filter_first) )
{
	if ( ! is_string($filter_label) )
	{
		register_error(elgg_echo('custom_index_inria:filter:save:invalid:label'));
		forward(REFERRER);
	}
	if ( ! is_array($filter_river_type_ids) && count($filter_river_type_ids) > 0 )
	{
		register_error(elgg_echo('custom_index_inria:filter:save:invalid:ids'));
		forward(REFERRER);
	}
	if ( $filter_first == 'true' )
	{
		$filter_first = TRUE;
	}else{
		$filter_first = FALSE;
	}
	if ( ! empty($filter_guid) )
	{
		if ( is_int($filter_guid) )
		{
			$filter = get_entity($filter_guid);
			if( ! $filter instanceof ElggFilter )
			{
				register_error(elgg_echo('custom_index_inria:filter:save:guid:notfound'));
				forward(REFERRER);	
			}
		}
		else{
			register_error(elgg_echo('custom_index_inria:filter:save:invalid:guid'));
			forward(REFERRER);
		}
	}
	if( ! $filter ){
		if (create_filter($user, $filter_river_type_ids, $filter_label, $filter_first))
		{
			system_message(elgg_echo('custom_index_inria:filter:save:create:success'));
		}else{
			register_error(elgg_echo('custom_index_inria:filter:save:create:failed'));
		}
	}else{
		if ($filter->update($user, $filter_river_type_ids, $filter_label, $filter_first))
		{
			system_message(elgg_echo('custom_index_inria:filter:save:update:success'));
		}else{
			register_error(elgg_echo('custom_index_inria:filter:save:update:failed'));
		}
	}
	
}else{
	register_error(elgg_echo('custom_index_inria:filter:form:filtersnotvalid'));	
}

forward($_SERVER['HTTP_REFERER']);
