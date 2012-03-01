$(function() {
	$('a.filter_add_link').click(function () {
		$(this.parentNode.parentNode).children(".filter_new").slideToggle("fast");
		$(this.parentNode.parentNode).children(".filter_add").toggle();
		$(this.parentNode.parentNode).find(".filter_edit").toggle();
		return false;
	});
});