<?php
global $CONFIG;

$src = $CONFIG->url . 'mod/custom_index_inria/graphics/Crystal_Clear_action_button_cancel.png';

$default = array( 	'src' => $src,
					'href' => '',
					'class' => 'cancel',
					'title' => elgg_echo('cancel:title'),
					'alt' 	=> elgg_echo('cancel:alt'));

$vars = array_merge($default, $vars);

echo elgg_view('icon/icon', $vars);
