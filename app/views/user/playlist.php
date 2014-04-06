<div id="playlists">
	<div class="playlist-nav">
		<?php foreach ($data as $playlist): ?>
		<div class="playlist">
			<button songids="<?php echo $playlist->song_ids ?>" playlistid="<?php echo $playlist->id ?>" class="playlist-name btn btn-default" type="button"><?php echo $playlist->name; ?></button>
		</div>
		<?php endforeach ?>
	</div>

	<?php
		$prevPageId = $pageid - 1;
		$nextPageId = $pageid + 1;
		$toalPages = ceil($count / $limit);
		$startid = $pageid - 5 > 1 ? $pageid - 5 : 1;
		$endid = $pageid + 4 < $toalPages ? $pageid + 4 : $toalPages;
	?>

	<ul class="pagination play-list-pagination">
		<?php if ($startid > 1): ?>
			<li><a href="javascript:;" pageid="1">First</a></li>
			<li><a>...</a></li>
		<?php endif ?>

		<?php for( $i = $startid; $i <= $endid; $i++ ): ?>
			<?php if ($i == $pageid): ?>
				<li class="active"><a><?php echo $i?></a></li>
			<?php else: ?>
				<li><a href="javascript:;" pageid="<?php echo $i?>"><?php echo $i?></a></li>
			<?php endif ?>
		<?php endfor ?>

		<?php if ($endid < $toalPages): ?>
			<li><a>...</a></li>
			<li><a href="javascript:;" pageid="<?php echo $toalPages?>">Last</a></li>
		<?php endif ?>
	</ul>
</div>
<script>
	$(".playlist-name").click(function(event) {
		var id = $(this).attr('playlistid');
		$('.playlist-name').removeClass('btn-success');
		$(".playlistdetail").load( '/m/user/playlistdetail/' + id );
		$(this).addClass('btn-success');
	});
	$('.play-list-pagination a[pageid]').click(function(event) {
		var pageid = $(this).attr("pageid");
		$("#playlists").load( "/m/user/showplaylist/" + pageid);
	});
</script>
