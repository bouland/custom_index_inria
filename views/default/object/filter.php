<?php
if ( ! empty($vars['entity'])){
	$entity = $vars['entity'];
	if ($entity instanceof ElggFilter)
	{
		echo '<div class="filter">';
		$del_url = elgg_add_action_tokens_to_url($vars['url'] . 'action/filter/delete?filter_guid=' . $entity->guid);
		echo '<div class="filter_delete_link">' . elgg_view('icon/cancel', array('href' => $del_url, 'title' => elgg_echo('custom_index_inria:forms:filter:edit:delete'))) . '</div>';
		echo '<div class="filter_edit_link">' . elgg_view('icon/edit', array('class' => 'edit collapsibleboxlink', 'title' => elgg_echo('custom_index_inria:forms:filter:edit'))) . '</div>';
		echo '<div class="filter_title"><h3>' . $entity->title . '</h3></div><div class="clearfloat"></div>';
		if(empty($entity->guid) || $entity->canEdit()){
			echo elgg_view('forms/filter/edit', $vars);
		}
		echo '</div>';
	}
	
}