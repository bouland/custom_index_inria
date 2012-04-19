<?php
$title = $vars['title'];

$body = elgg_substr($vars['body']->description, 0, 1216);
$body .= "<br /><br /><a href=\"{$vars->url}/pg/expages/read/About\">" . elgg_echo('custom_index_inria:more') . '</a>';

?>
<div id="index_public">
	<h1><?php echo $title; ?></h1>
	<div class="contentWrapper">
		<?php echo $body; ?>
	</div>
</div>