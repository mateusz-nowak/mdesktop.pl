$(document).ready(function(){
	$("#shoutbox_text").val("Napisz coś, co chcesz przekazać innym użytkownikom serwisu");
	$("#shoutbox_text, #track_search_id").click(function() {
		$(this).val(null);
	});

	$(".draggable").draggable({
		opacity: 0.5,
		revert: true,
	});
	
	$("a[rel=switchPlaylist]").click(function(){
		var itemSelected = $(this);
		var selectedItemLocation = itemSelected.attr('href').replace('/show', '/download');
		var audioElement = document.getElementById('audioPlaylist');
		
		$('#trackList li').removeClass('selected');
		$(this).closest('li').addClass('selected');

		audioElement.src = selectedItemLocation;
		audioElement.load();
			
		return false;
	});

	$("#trackPlaylistCatalogs li").droppable({
		drop: function(event, ui) {
			var dataSend = {"id": ui.draggable.attr('data-draggable-id'), "name": $(this).attr('data-playlist-id')};

			$.ajax({
				type: 'POST',
				url: '/playlist/new',
				data: dataSend,
				success: function(response) {
					alert("Dodano do playlisty!");
				},
			});
		},
		activate: function(event, ui) {
			$(this).css("background", "#444444");
		},
		over: function(event, ui) {
			$(this).css("background", "#FFF");
		},
		out: function(event, ui) {
			$(this).css("background", "#222222");
		},
		deactivate: function(event, ui) {
			$(this).css("background", "#222222");
		},

	});
	
	$("#trackList li").hover(function(){
		$(this).find(".actions").toggle();
	});

	$(".right .add_new_playlist").click(function(){
		var dataSend = {"name": window.prompt("Podaj nazwę nowej playlisty")};

		$.ajax({
				type: 'POST',
				url: '/playlist/new',
				data: dataSend,
				success: function(response) {
					window.location.reload();
				},
			});

	});

    $(".confirm_before").click(function() {
        return confirm("Jesteś pewny/pewna, że chcesz to zrobić?");
    });

});

