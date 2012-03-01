<?php // Get the Elgg framework
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");

// Make sure only valid admin users can see this
admin_gatekeeper();

$filters = get_default_filters();

set_input('owner_guid', 0);

$title = elgg_view_title(elgg_echo('custom_index_inria:settings:filter'));

$content = elgg_view_entity_list($filters, 10, 0, 999, true, 'list', false);

$content .= elgg_view('filter/add');

$wrapper = elgg_view('filter/wrapper', array('content' => $content));

$body = elgg_view_layout("two_column_left_sidebar", '', $title . $wrapper);

page_draw(elgg_echo("usersettings:user"), $body);
