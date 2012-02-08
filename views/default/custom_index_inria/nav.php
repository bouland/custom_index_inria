<?php

/**
 * Elgg riverdashboard navigation view
 *
 * @package ElggRiverDash
 *
 */

$contents = array();
$contents['all'] = 'all';
if (!empty($vars['config']->registered_entities)) {
	foreach ($vars['config']->registered_entities as $type => $ar) {
		if (count($vars['config']->registered_entities[$type])) {
			foreach ($vars['config']->registered_entities[$type] as $subtype) {
				$keyname = 'item:' . $type . ':' . $subtype;
				$contents[$keyname] = "{$type},{$subtype}";
			}
		} else {
			$keyname = 'item:' . $type;
			$contents[$keyname] = "{$type},";
		}
	}
}

$filters = river_get_item_types();



$allselect = '';
$friendsselect = '';
$mineselect = '';
switch($vars['orient']) {
	case '':
		$allselect = 'class="selected"';
		break;
	case 'friends':
		$friendsselect = 'class="selected"';
		break;
	case 'mine':
		$mineselect = 'class="selected"';
		break;
}
//mod/custom_index_inria/?callback=true&amp;display='+$('input#display').val() + '&amp;content=' + $('select#content').val()

?>
	<div class="riverdashboard_filtermenu">
	<div class="riverdashboard_filter_id">
		<select name=filter_id id="filter_id" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/custom_index_inria?callback=true&amp;filter_id=' + $('select#filter_id').val());">
			<?php
			foreach($contents as $label => $content) {
				if (("{$vars['type']},{$vars['subtype']}" == $content) ||
						(empty($vars['subtype']) && $content == 'all')) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option value=\"{$content}\" {$selected}>" . elgg_echo($label) . "</option>";
			}

?>
		</select>
		<input type="hidden" name="display" id="display" value="<?php echo htmlentities($vars['orient']); ?>" />
		<!-- <input type="submit" value="<?php echo elgg_echo('filter'); ?>" /> -->
	</div>
		<div class="riverdashboard_filter_context">
		<select name="content" id="content" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/custom_index_inria?callback=true&amp;content=' + $('select#content').val());">
			<?php
			echo "<option value=\"{$content}\" {$selected}>" . elgg_echo($label) . "</option>";
			foreach($contents as $label => $content) {
				if (("{$vars['type']},{$vars['subtype']}" == $content) ||
						(empty($vars['subtype']) && $content == 'all')) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option value=\"{$content}\" {$selected}>" . elgg_echo($label) . "</option>";
			}

?>
		</select>
		<input type="hidden" name="display" id="display" value="<?php echo htmlentities($vars['orient']); ?>" />
		<!-- <input type="submit" value="<?php echo elgg_echo('filter'); ?>" /> -->
	</div>
	</div>
	<!-- </div> -->
