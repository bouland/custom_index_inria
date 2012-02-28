<?php
	global $CONFIG;

	if (empty($vars['filter'])) {

			$guid = NULL;
		    $label = "";
		    $check = "";
		    $title = elgg_echo('custom_index_inria:forms:filter:edit:title:new');
		    $filter_river_types_ids=array();
	}else{
		$filter = $vars['filter'];
		if ($filter instanceof ElggFilter){
			$guid = $filter->guid;
		    $label = $filter->title;
		    $filter_river_types_ids = $filter->river_types;
		    $title = sprintf(elgg_echo('custom_index_inria:forms:filter:edit:title'), $filter->title);
		    if($filter->first)
		    {
		    	$check = 'checked';
		    }
		}else{
			
		}
	}
	$content .= '<div class="filter_form_wrapper"><h3>' . $title .'</h3>';
	
	$form = '<p>' . elgg_echo('custom_index_inria:forms:filter:edit:label')  . elgg_view('input/text', array('internalname' => 'filter_label', 'value' => $label)) . '</p>' ;

	$form .= '<p><input type="checkbox" name="filter_first" value="true" ' . $check .'>' . elgg_echo('custom_index_inria:forms:filter:edit:first') . '</p>';
	
	$form .= elgg_echo('custom_index_inria:forms:filter:edit:select');
	
	$form .= elgg_view('custom_index_inria/js/checkboxTree',array('tree_id' => 'tree1'));
	
	$river_types = get_rivertypes();
	$form .= '<ul id="tree1"><li><input type="checkbox">'. elgg_echo('custom_index_inria:forms:filter:edit:all') . '<ul>';
	$object_prev = '';
	foreach($river_types as $river_type){
		
		$object = $river_type->type . ':' . $river_type->subtype;
		if($object != $object_prev)
		{
			if($object_prev != ''){
				$form .= '</ul></li>';
			}
			if ($river_type->type == 'object')
			{
				$name = $river_type->subtype;
			}else{
				$name = $river_type->type;
			}
			$form .= '<li><input type="checkbox">' . elgg_echo($name);
			$form .= '<ul>';
		}
		
		$value = $river_type->id;
		if (array_search($value, $filter_river_types_ids))
		{
			$check = "checked";
		}else{
			$check = "";
		}
		$name = elgg_echo('custom_index_inria:forms:filter:edit:river_type:' . $river_type->type .':' . $river_type->subtype .':' . $river_type->action);
		$form .= '<li><input type="checkbox" name="filter_river_type_ids[]" value="' . $value .'" ' . $check .'>' . elgg_echo('custom_index_inria:forms:filter:edit:river:action:' . $river_type->action) . '</li>';
		
		
		$object_prev = $object;
	}
	$form .= '</ul></ul></li></ul>';
	$form .= elgg_view('input/hidden', array('internalname' => 'filter_guid', 'value' => $guid));
	
	$form .= elgg_view('input/submit', array('internalname'=> 'save', 'value' => elgg_echo('custom_index_inria:forms:filter:edit:save')));
	
	$content .= elgg_view('input/form', array('action' => $vars['url'].'action/filter/save', 'body' => $form, 'internalname' => 'filter_form'));
	
	$content .= '</div>';
	
	echo $content;
