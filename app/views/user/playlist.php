<div class="playlist-nav">
	<?php foreach ($data as $playlist): ?>
	<div class="playlist">
		<button playlistid="<?php echo $playlist->id ?>" class="playlist-name btn" type="button"><?php echo $playlist->name; ?></button>
		<div class="btn-group">
			<button songids="<?php echo $playlist->song_ids ?>" class="btn btn-primary playlist-play"><i class='icon-music icon-white'></i> Play</button>
			<button playlistid="<?php echo $playlist->id ?>" class="btn btn-danger playlist-delete"><i class='icon-remove icon-white'></i> Delete</button>
			<button songids="<?php echo $playlist->song_ids ?>" playlistid="<?php echo $playlist->id ?>" class="btn btn-warning playlist-rename"><i class='icon-edit icon-white'></i> Rename</button>
		</div>
	</div>
	<?php endforeach ?>	
</div>
<div class="playlistdetail">
	
</div>

<script>
	$(".playlist-delete").click(function () {
		var id = $(this).attr('playlistid');
		bootbox.confirm("You are going to delete playlist " + $(this).closest('.playlist').find('.playlist-name').text() + ", Please confirm.", function(result) {
			if (result) {
				$.get( '/m/user/deleteplaylist/' + id , function(data) {
					$('#data').load('/m/user/showplaylist');
				});
			};
		}); 
	});
	$(".playlist-rename").click(function () {
		var playlistid = $(this).attr('playlistid');
		var songids = $(this).attr('songids');
		bootbox.prompt("Please enter the new playlist name ", function(result) {
			if (result !== null) {
				$.get( '/m/user/saveplaylist/' + songids + '/' + result + '/' + playlistid , function(data) {
					$('#data').load('/m/user/showplaylist');
				});
			};
		}); 
	});
	$(".playlist-name").click(function(event) {
		var id = $(this).attr('playlistid');
		$('.playlist-name').removeClass('btn-inverse');
		$(".playlistdetail").load( '/m/user/playlistdetail/' + id );
		$(this).addClass('btn-inverse');
	});
</script>
