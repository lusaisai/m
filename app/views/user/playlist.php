<div class="playlist-nav">
	<?php foreach ($data as $playlist): ?>
	<div class="playlist">
		<button songids="<?php echo $playlist->song_ids ?>" playlistid="<?php echo $playlist->id ?>" class="playlist-name btn btn-default" type="button"><?php echo $playlist->name; ?></button>
	</div>
	<?php endforeach ?>
</div>
<div class="playlistdetail">
	
</div>

<script>
	$(".playlist-name").click(function(event) {
		var id = $(this).attr('playlistid');
		$('.playlist-name').removeClass('btn-success');
		$(".playlistdetail").load( '/m/user/playlistdetail/' + id );
		$(this).addClass('btn-success');
	});
</script>
