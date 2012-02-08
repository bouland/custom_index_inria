<?php

/**
 * Elgg custom_index_inria plugin filter
 *
 * @package INRIA
 */

// Load Elgg framework
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// Ensure only logged-in users can see this page
gatekeeper();

set_page_owner(get_loggedin_userid());

// Set the context to settings
set_context('settings');


$body = elgg_view('custom_index_inria/forms/filter');

// Insert it into the correct canvas layout
$body = elgg_view_layout('two_column_left_sidebar', '', $body);


page_draw(elgg_echo('notifications:subscriptions:changesettings'), $body);
