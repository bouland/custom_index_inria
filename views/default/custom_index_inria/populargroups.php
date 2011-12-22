<?php

/**
 * Elgg riverdashboard newest messbers sidebar box
 * 
 * @package ElggRiverDash
 * 
 */
$groups = get_entities_by_relationship_count('member', true,"", "",0,28,0);
	
?>

<div class="sidebarBox">
<h3><a href="<?php echo $vars['url']; ?>pg/groups/all/"><?php echo elgg_echo('custom_index_inria:groups:wall'); ?></a></h3>
<div class="groupsWrapper"><br />
<?php 
	foreach($groups as $group) {
		echo "<div class=\"popularGroup\">" . elgg_view("groups/icon", array('entity' => $group, 'size' => 'small')) . "</div>";
	}
?>
<div class="clearfloat"></div>
</div>
</div>