<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");

gatekeeper();

$filter_label = get_input('filter_label');

$filter_guid = get_input('filter_guid');

$filter_first = get_input('filter_first',FALSE);

$filter_river_type_ids = get_input('filter_river_type_ids');

$user_guid = get_input('user_guid');

if ( ! empty($filter_label) && ! empty($filter_river_type_ids) )
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
	if ( ! empty($filter_guid) )
	{
		if ( is_int((int)$filter_guid) )
		{
			$filter = get_entity($filter_guid);
			if( $filter instanceof ElggFilter )
			{
				if ($filter->update($user_guid, $filter_river_type_ids, $filter_label, $filter_first))
				{
					system_message(elgg_echo('custom_index_inria:actions:save:update:success'));
				}else{
					register_error(elgg_echo('custom_index_inria:actions:save:update:failed'));
				}
			}else{
				register_error(elgg_echo('custom_index_inria:actions:save:guid:notfound'));
				forward(REFERRER);	
			}
		}
		else{
			register_error(elgg_echo('custom_index_inria:actions:save:invalid:guid'));
			forward($_SERVER['HTTP_REFERER']);
		}
	}else{
		$filter = new ElggFilter();
		if ($filter->save() && $filter->update($user_guid, $filter_river_type_ids, $filter_label, $filter_first))
		{
			system_message(elgg_echo('custom_index_inria:actions:save:create:success'));
		}else{
			register_error(elgg_echo('custom_index_inria:actions:save:create:failed'));
		}
	}
}else{
	register_error(elgg_echo('custom_index_inria:actions:action:save:filtersnotvalid'));	
}

forward($_SERVER['HTTP_REFERER']);
