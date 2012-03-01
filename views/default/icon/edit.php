<?php
global $CONFIG;

$src = $CONFIG->url . 'mod/custom_index_inria/graphics/Crystal_Clear_action_edit.png';

$default = array( 	'src' => $src,
					'href' => '',
					'class' => 'edit',
					'title' => elgg_echo('edit:title'),
					'alt' 	=> elgg_echo('edit:alt'));

$vars = array_merge($default, $vars);

echo elgg_view('icon/icon', $vars);
