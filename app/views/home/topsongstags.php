<ul>
	<?php foreach ($topSongs as $song): ?>
		<li class="topsongs"><a songid="<?php echo $song->song_id ?>" href="javascript:;"><?php echo $song->song_name ?></a></li>
	<?php endforeach ?>
</ul>