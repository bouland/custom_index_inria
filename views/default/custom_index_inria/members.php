<?php
$members = $vars['members'];

if ( is_array($members) ) {
	echo '<div class="membersWrapper"><br />';
	
	foreach($members as $member) {
		echo "<div class=\"recentMember\">" . elgg_view("profile/icon", array('entity' => $member, 'size' => 'tiny')) . "</div>";
	}
	echo '<div class="clearfloat"></div>';
	echo '</div>';
}
?>
