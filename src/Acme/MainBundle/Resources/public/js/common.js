$(document).ready(function(){
	$("#shoutbox_text").val("Napisz coś, co chcesz przekazać innym użytkownikom serwisu");
	$("#shoutbox_text, #track_search_id").click(function() {
		$(this).val(null);
	});
});