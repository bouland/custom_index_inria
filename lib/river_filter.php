<?php
define('FILTER_DEFAULT_ORDER', -1);

class ElggFilter extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();
		$this->attributes['subtype'] = "filter";
		$this->attributes['title'] = elgg_echo('custom_index_inria:filter:label:new');
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
				$value = implode(',', $value);
			}elseif ($name == 'first' && $value == TRUE)
			{
				$owner = get_user($this->owner_guid);
				$owner_filters = get_user_filters($owner);
				if(is_array($owner_filters))
				{
					foreach( $owner_filters as $filter)
					{
						if($filter->guid != $this->guid){
							$filter->first = FALSE;
							$filter->save();
						}
					}
				}
			}
			return set_private_setting($this->guid, $name, $value);
		}
		return true;
	}
	public function update(int $ower_guid, array $filter_river_type_ids, $label, $first = FALSE)
	{
		if( ! get_entity($ower_guid) instanceof ElggUser){
			$ower_guid = 0;
			$access_id = ACCESS_PUBLIC;
		}else{
			$access_id = ACCESS_PRIVATE;
		}
		
		if( ! is_valid_river_type_ids($filter_river_type_ids) )
		{
			return false;
		}
		if( ! empty($label) && $label != "")
		{
			$this->title = $label ;
		}
		if( $first != $this->first)
		{
			$this->first = $first;
		}
		$this->river_types = $filter_river_type_ids;
		$this->owner_guid = $ower_guid;
		$this->access_id = $access_id;
		return $this->save();
	}
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
		if( ! is_int((int)$river_type_id) ){
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
	if(is_array($filters))
	{
		usort($filters,'cmp_filters');
		return $filters;
	}else{
		return FALSE;
	}
}
function get_default_filters()
{
	global $CONFIG;
	$sql = "SELECT DISTINCT e.* FROM {$CONFIG->dbprefix}entities e  WHERE  e.type = 'object' AND e.subtype = 43 AND e.owner_guid = 0 ORDER BY e.time_created desc LIMIT 0, 999";
	
	$filters = get_data($sql, 'entity_row_to_elggstar');
	if(is_array($filters))
	{
		usort($filters,'cmp_filters');
		return $filters;
	}else{
		return FALSE;
	}
}
function cmp_filters(ElggFilter $f1, ElggFilter $f2)
{
	if( $f1->first && ! $f2->first){
		return -1;
	}
	if( ! $f1->first && $f2->first){
		return 1;
	}
	if($f1->owner_guid < $f2->owner_guid){
		return 1;
	}
	if($f1->owner_guid > $f2->owner_guid){
		return -1;
	}
	return 0;
}
/**
* Run some things once.
*
*/
function river_filter_run_once() {
	// Register a class
	add_subtype("object", "filter", "ElggFilter");
}
function get_river_items_filtered(array $options){
	$defaults = array(
						'filter' 				=> 	get_entity(get_plugin_setting('default_filter', 'custom_index_inria')),
						'limit'					=>	10,
						'offset'				=>	0,
						'object_guid' 			=> 	0,
						'subject_guid'			=> 	0,
						'subject_relationship' 	=> 	''
	);
	
	$options = array_merge($defaults, $options);
	
	
	$limit = (int)$options['limit'];
	$offset = (int)$options['offset'];
	$object_guid = (int)$options['object_guid'];
	$subject_guid = (int)$options['subject_guid'];
	$subject_relationship = $options['subject_relationship'];
	$filter = $options['filter'];
	
	global $CONFIG;
	
	$where = array();
	$where[] = 'r.river_type = t.id';
	
	if( $filter instanceof ElggFilter)
	{
		$and = array();
		$or = array();
		foreach ($filter->river_types as $rivertype_id){
			$river_type = get_rivertype_from_id($rivertype_id);
			$and = array();
			$and[] = " type = '{$river_type->type}' ";
			if($river_type->subtype){
				$and[] = " subtype = '{$river_type->subtype}' ";
			}
			$and[] = " action = '{$river_type->action}' ";
			$or[] = '( '. implode(' AND ', $and) . ' )';
		}
		$where[] = '( '. implode(' OR ', $or) . ' )';
	}
	
	if (empty($subject_relationship)) {
		if (!empty($subject_guid)) {
			if (!is_array($subject_guid)) {
				$where[] = " subject_guid = {$subject_guid} ";
			} else {
				$where[] = " subject_guid in (" . implode(',',$subject_guid) . ") ";
			}
		}
	} else {
		if (!is_array($subject_guid)) {
			if ($entities = elgg_get_entities_from_relationship(array(
					'relationship' => $subject_relationship,
					'relationship_guid' => $subject_guid,
					'limit' => 9999))
			) {
				$guids = array();
				foreach($entities as $entity) {
					$guids[] = (int) $entity->guid;
				}
				// $guids[] = $subject_guid;
				$where[] = " subject_guid in (" . implode(',',$guids) . ") ";
			} else {
				return array();
			}
		}else{
			return array();
		}
	}
	if (!empty($object_guid))
	{
		if (!is_array($object_guid)) {
			$where[] = " object_guid = {$object_guid} ";
		} else {
			$where[] = " object_guid in (" . implode(',',$object_guid) . ") ";
		}
	}
	$whereclause = implode(' AND ', $where);
	// Construct main SQL
	$sql = "SELECT r.id,type,subtype,action,access_id,view,subject_guid,object_guid,annotation_id,posted" .
		 		" FROM {$CONFIG->dbprefix}river r, {$CONFIG->dbprefix}river_types t WHERE {$whereclause} ORDER BY posted DESC LIMIT {$offset},{$limit}";
	
	// Get data
	return get_data($sql);
}
