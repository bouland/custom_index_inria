<script type="text/javascript">$(function() {
	$('#<?php echo $vars['tree_id']?>').checkboxTree({
			initializeChecked: 'expanded',
			initializeUnchecked: 'collapsed',
			onCheck: { 
				ancestors: 'checkIfFull',
				descendants: 'check',
				node: 'collapse'
			},
			onUncheck: {
				ancestors: 'uncheck',
				node: 'expand' 
			}
		});
	}); </script>
