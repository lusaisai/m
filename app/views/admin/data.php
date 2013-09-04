<?php if ($error != ""): ?>
	<div class="alert alert-error">
		<p><?php echo $error ?></p>
	</div>
<?php elseif ( $success != "" ): ?>
	<div class="alert alert-success">
		<p><?php echo $success ?></p>
	</div>
	<?php if ($newsongs): ?>
		<h4>New added songs:</h4>
		<table class="table">
		<tr><th>Artist</th><th>Album</th><th>Song</th></tr>
		<?php foreach ($newsongs as $newsong): ?>
			<tr><th><?php echo $newsong->artist_name ?></th><th><?php echo $newsong->album_name ?></th><th><?php echo $newsong->song_name ?></th></tr>
		<?php endforeach ?>
		</table>
	<?php endif ?>
<?php endif ?>