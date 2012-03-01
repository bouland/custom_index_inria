<?php // Get the Elgg framework
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");

// Make sure only valid admin users can see this
gatekeeper();

// Make sure we don't open a security hole ...
if ((!page_owner_entity()) || (!page_owner_entity()->canEdit())) {
	set_page_owner(get_loggedin_userid());
}
set_input('owner_guid', page_owner());

$filters = get_user_filters(page_owner_entity());

$title = elgg_view_title(elgg_echo('custom_index_inria:settings:filter'));

$content = elgg_view_entity_list($filters, 10, 0, 999, true, 'list', false);

$content .= elgg_view('filter/add');

$wrapper = elgg_view('filter/wrapper', array('content' => $content));

$body = elgg_view_layout("two_column_left_sidebar", '', $title . $wrapper);

page_draw(elgg_echo("usersettings:user"), $body);
