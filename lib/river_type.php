<?php
/// Cache river_types in order to minimise database access.
global $RIVERTYPE_CACHE;
$RIVERTYPE_CACHE = NULL;

/**
* For a given rivertype ID, return its Standard Class.
*
* with id, type ,subtype, action, view
*
* @param int $rivertype_id
*
* @return a Standard Class or FALSE
*/
function get_rivertype_from_id($river_type_id) {
	global $CONFIG, $RIVERTYPE_CACHE;

	$river_type_id = (int)$river_type_id;

	if (!$river_type_id) {
		return false;
	}

	if (isset($RIVERTYPE_CACHE[$river_type_id])) {
		return $RIVERTYPE_CACHE[$river_type_id];
	}

	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}river_types where id=$river_type_id");
	if ($result) {
		if (!$RIVERTYPE_CACHE) {
			//select_default_memcache('subtype_cache');
			$RIVERTYPE_CACHE = array();
		}

		$RIVERTYPE_CACHE[$river_type_id] = $result;
		return $result;
	}

	return false;
}
/**
* For a given rivertype ID, return its type.
*
* @param int $rivertype_id
* 
* @return a string or FALSE
*/
function get_rivertype_type_from_id($river_type_id) {
	global $RIVERTYPE_CACHE;


	if (get_rivertype_from_id($river_type_id)) {
		return $RIVERTYPE_CACHE[$river_type_id]->type;
	}else{
		return NULL;
	}
}
/**
* For a given rivertype ID, return its subtype.
*
* @param int $rivertype_id
* 
* @return a string or NULL
*/
function get_rivertype_subtype_from_id($river_type_id) {
	global $RIVERTYPE_CACHE;


	if (get_rivertype_from_id($river_type_id)) {
		return $RIVERTYPE_CACHE[$river_type_id]->subtype;
	}else{
		return NULL;
	}
}
/**
* For a given rivertype ID, return its action.
*
* @param int $rivertype_id
* 
* @return a string or NULL
*/
function get_rivertype_action_from_id($river_type_id) {
	global $RIVERTYPE_CACHE;


	if (get_rivertype_from_id($river_type_id)) {
		return $RIVERTYPE_CACHE[$river_type_id]->action;
	}else{
		return NULL;
	}
}
/**
* For a given rivertype ID, return its view.
*
* @param int $rivertype_id
*
* @return a string or NULL
*/
function get_rivertype_view_from_id($river_type_id) {
	global $RIVERTYPE_CACHE;


	if (get_rivertype_from_id($river_type_id)) {
		return $RIVERTYPE_CACHE[$river_type_id]->view;
	}else{
		return false;
	}
}
/**
 * This function will register a new subtype, returning its ID as required.
 *
 * @param string $type The type you're subtyping
 * @param string $subtype The subtype label Optional
 * @param string $action action type An arbitrary string to define the action (eg 'comment', 'create')
 * @param string $view view handler
 */
function add_rivertype($type, $subtype = "", $action, $view) {
	global $CONFIG;
	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$action = sanitise_string($action);
	$view = sanitise_string($view);

	if ( $type == "" || $action == "" || $view == "" ) {
		return FALSE;
	}

	$id = get_rivertype_id($type, $subtype, $action);

	if ($id==0) {
		return insert_data("insert into {$CONFIG->dbprefix}river_types (type, subtype, action, view) values ('$type','$subtype','$action','$view')");
	}

	return $id;
}
/**
* Return the integer ID for a given rivertype, or false.
*
*
* @param string $type
* @param string $subtype Optional
* @param string $action
* 
* @return int or FALSE
*/
function get_rivertype_id($type, $subtype="", $action) {
	global $CONFIG, $RIVERTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$action = sanitise_string($action);
	
	if ( $type == "" || $action == "") {
		//return $subtype;
		return FALSE;
	}

	// Todo: cache here? Or is looping less efficient that going to the db each time?
	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}river_types
		where type='$type' and subtype='$subtype' and action='$action'");

	if ($result) {
		if (!$RIVERTYPE_CACHE) {
			//select_default_memcache('rivertype_cache');
			$RIVERTYPE_CACHE = array();
		}

		$RIVERTYPE_CACHE[$result->id] = $result;
		return $result->id;
	}

	return FALSE;
}
function get_rivertypes(){
	global $CONFIG, $RIVERTYPE_CACHE;
	$sql = "SELECT * FROM {$CONFIG->dbprefix}river_types ORDER BY type,subtype,action";
	$RIVERTYPE_CACHE = get_data($sql);
	return $RIVERTYPE_CACHE;
}