<?php

/**
 * Elgg riverdashboard navigation view
 *
 * @package ElggRiverDash
 *
 */
if(is_array($vars['filters'])){
	$filters = $vars['filters'];
?>
<div class="nav_filter_menu">
	<div class="nav_filter_id">
		<select name=filter_guid id="filter_guid" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/custom_index_inria?callback=true&amp;filter_guid=' + $('select#filter_guid').val() + '&amp;orient=' + $('select#orient').val());">
			<?php
			foreach($filters as $filter) {
				if ( ($vars['filter_guid'] == $filter->guid) ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option value=\"{$filter->guid}\" {$selected}>" . elgg_echo($filter->title) . "</option>";
			}
			?>
		</select>
	</div>
	<div class="nav_filter_orient">
		<select name=orient id="orient" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/custom_index_inria?callback=true&amp;filter_guid=' + $('select#filter_guid').val() + '&amp;orient=' + $('select#orient').val());">
			<?php
			$orient_options = array('all', 'friends','mine');
			foreach($orient_options as $option) {
				if ( $vars['orient'] == $option ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option value=\"{$option}\" {$selected}>" . elgg_echo($option) . "</option>";
			}
			?>
		</select>
	</div>
	<div class="nav_filter_edit">
		<?php
		echo elgg_view('icon/edit', array('href' => $vars['url'] . 'pg/settings/filter/' . get_loggedin_user()->username));
		?>
	</div>
</div>
<?php
}