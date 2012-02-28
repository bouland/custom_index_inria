<?php // Get the Elgg framework
require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php");

// Make sure only valid admin users can see this
gatekeeper();

// Make sure we don't open a security hole ...
if ((!page_owner_entity()) || (!page_owner_entity()->canEdit())) {
	set_page_owner(get_loggedin_userid());
}
$user = page_owner_entity();
$filters = get_user_filters($user);

$content = elgg_view_title(elgg_echo('custom_index_inria:settings:filter'));
foreach($filters as $filter)
{
	$content .= elgg_view("forms/filter/edit", array('filters' => $filter));
}
$content .= elgg_view("forms/filter/edit");

$body = elgg_view_layout("two_column_left_sidebar", '', $content);

page_draw(elgg_echo("usersettings:user"), $body);
