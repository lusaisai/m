<ul>
	<?php foreach ($topArtists as $artist): ?>
		<li class="topartists"><a href="<?php echo URL::to("artist/id/{$artist->artist_id}"); ?>"><img src="<?php echo Config::get('music.url')."/{$artist->artist_name}/{$artist->image_name}.tm.gif"; ?>"></a></li>
	<?php endforeach ?>
</ul>