<?php
define('FILTER_DEFAULT_ORDER', -1);

class ElggFilter extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();
		$this->attributes['subtype'] = "filter";
		$this->attributes['title'] = elgg_echo('custom_index_inria:filter:label:default');
	}
	public function __construct($guid = null) {
		parent::__construct($guid);
	}
	
	/**
	 * Override entity get and sets in order to save data to private data store.
	 */
	public function get($name) {
		// See if its in our base attribute
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}
		
		// No, so see if its in the private data store.
		$meta = get_private_setting($this->guid, $name);
		if ($meta) {
			if($name == 'river_types'){
				$meta = explode(',', $meta);
			}
			return $meta;
		}
	
		// Can't find it, so return null
		return null;
	}
	
	/**
	 * Override entity get and sets in order to save data to private data store.
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Check that we're not trying to change the guid!
			if ((array_key_exists('guid', $this->attributes)) && ($name=='guid')) {
				return false;
			}
	
			$this->attributes[$name] = $value;
		} else {
			if($name == 'river_types'){
				if ( ! is_array($value) )
				{
					return FALSE;	
				}
				$value = impode(',', $value);
			}elseif ($name == 'first' && $value == TRUE)
			{
				$owner = get_user($this->owner_guid);
				$owner_filters = get_user_filters($owner);
				foreach( $owner_filters as $filter)
				{
					if($filter->guid != $this->guid){
						$filter->first = FALSE;
						$filter->save();
					}
				}
			}
			return set_private_setting($this->guid, $name, $value);
		}
		return true;
	}
	public function update(ElggUser $user, array $filter_river_type_ids, $label, $first = FALSE)
	{
		if( ! $user instanceof ElggUser )
		{
			return false;
		}
		if( ! is_valid_river_type_id($filter_river_type_ids) )
		{
			return false;
		}
		if( ! empty($label) && $label != "")
		{
			$filter->title = $label ;
		}
		if( $first )
		{
			$filter->first = TRUE;
		}
		$this->river_types = $filter_river_type_ids;
		$this->owner_guid = $user->guid;
		$this->access_id = ACCESS_PRIVATE;
		$this->save();
		return $this;
	}
}
/**
* Return a filter with $river_type_ids
* 
* @param ElggUser $user
* @param array $filter_river_type_ids
* @param string $label Optional
* @param int $order Optional
*
* @return ElggClass or FALSE
*/
function create_filter(ElggUser $user, array $filter_river_type_ids, $label, $first)
{
	if( ! $user instanceof ElggUser )
	{
		return false;
	}
	$filter = exist_filter($user, $filter_river_type_ids);
	if ( ! $filter )
	{
		$filter= new ElggFilter();
	}
	return $filter->update($user, $filter_river_type_ids, $label, $first);
}
/**
* Return true if
* $river_type_ids is comma separated int values.
* and
* this values are valid river_types
*
* @param array $filter_river_type_ids
*
* @return ElggClass or FALSE
*/
function is_valid_river_type_ids(array $river_type_ids)
{
	foreach ($river_type_ids as $river_type_id)
	{
		if( ! is_int($river_type_id) ){
			return FALSE;
		}
		if( ! get_rivertype_from_id($river_type_id) )
		{
			return FALSE;
		}
	}
	return TRUE;
}
/**
* Return the filter for given array of river_type ids, or false.
*
* @param string $filter_river_type_ids
* 
* @return ElggClass or FALSE
*/
function exist_filter(ElggUser $user, array $filter_river_type_ids)
{
	if( !is_valid_river_type_ids($filter_river_type_ids) )
	{
		return false;
	}
	
	$filters = get_user_filters($user);
	foreach ($filters as $filter)
	{
		if($filter->river_types == $filter_river_type_ids)
		{
			return $filter;
		}
	}
	return FALSE;
}
/**
* Return the filter for given array of river_type ids, or false.
*
* @param int $user_guid
*
* @return ElggClass or FALSE
*/
function get_user_filters(ElggUser $user)
{
	if( ! $user instanceof ElggUser )
	{
		return false;
	}
	$filters = elgg_get_entities(array(	'type' => 'object',
										'subtype' => 'filter',
										'owner_guid' => $user->guid,
										'limit' => 999));
	sort($filters);
	return $filters;
}
/**
* Run some things once.
*
*/
function river_filter_run_once() {
	// Register a class
	add_subtype("object", "filter", "ElggFilter");
}
