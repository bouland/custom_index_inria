<?php
	global $CONFIG;
	$plugin = find_plugin_settings('custom_index_inria');
?>
<p>
    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
        <legend><?php echo elgg_echo('inria:settings:title');?></legend>
        
        <label for="params[nbWire]"><?php echo elgg_echo('inria:settings:label:nbWire');?></label><br/>
        <div class="example"><?php echo elgg_echo('inria:settings:help:nbWire');?></div>
        <input type="text" name="params[nbWire]" value="<?php if (empty($plugin->nbWire)) {echo 3;} else {echo $plugin->nbWire;}?>"/><br/>
        
		<label for="params[nbRiver]"><?php echo elgg_echo('inria:settings:label:nbRiver');?></label><br/>
        <div class="example"><?php echo elgg_echo('inria:settings:help:nbRiver');?></div>
        <input type="text" name="params[nbRiver]" value="<?php if (empty($plugin->nbRiver)) {echo 3;} else {echo $plugin->nbRiver;}?>"/><br/>
        
        <a href="<?php echo $CONFIG->url . 'mod/custom_index_inria/filter_admin.php'?>"><?php  echo elgg_echo('inria:settings:filter:default:link'); ?></a>
    </fieldset>
</p>
