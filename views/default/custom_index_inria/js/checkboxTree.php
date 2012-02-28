<script type="text/javascript">$(function() {
	$('#<?php echo $vars['tree_id']?>').checkboxTree({
			initializeChecked: 'expanded',
			initializeUnchecked: 'collapsed',
			onCheck: { 
				ancestors: 'checkIfFull',
				descendants: 'check',
				node: 'expand'
			},
			onUncheck: {
				ancestors: 'uncheck',
				node: 'collapse' 
			}
		});
	}); </script>
