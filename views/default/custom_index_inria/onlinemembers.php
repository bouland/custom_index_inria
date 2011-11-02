<?php

/**
 * Elgg riverdashboard newest messbers sidebar box
 * 
 * @package ElggRiverDash
 * 
 */

$newest_members = find_active_users(600, 8, $offset);
?>

<div class="sidebarBox">
<?php
	echo '<h3>' . elgg_echo('inria:members_online') .'</h3>';
	echo elgg_view('custom_index_inria/members', array('members' => $newest_members));
?>
</div>