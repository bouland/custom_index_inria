<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/custom_index_inria/js/filter_add.js"></script>
<?php

echo '<div class="filter_add_wrapper">';
echo '<div class="filter_new" style="display:none">';
echo elgg_view('object/filter', array('entity' => new ElggFilter()));
echo '</div>';
echo '<div class="filter_add">';
echo elgg_view('icon/add', array('title' => elgg_echo('custom_index_inria:forms:filter:edit:add'), 'class' => 'add filter_add_link'));
echo '</div>';
echo '</div>';

?>
