<?php
/**
 *
 * @package custom_index_inria
 * @subpackage INRIA
 *
 * @uses $vars['title'] The title of content
 * @uses $vars['body'] The content to display inside content wrapper
 *  *
 */
?>
<div class="index_box">
	
	<div class="index_title"><h2>
<?php
	echo $vars['title'];
?>	
	</h2></div>

	<div class="contentWrapper
<?php
		if (isset($vars['subclass'])) {
			echo ' ' . $vars['subclass'];
		}
?>">
<?php
	echo $vars['body'];
?>
	</div>
	
</div>