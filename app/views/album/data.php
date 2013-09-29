<?php foreach ($data as $album): ?>
    <div id="<?php echo $album["id"] ?>" class="album">
        <blockquote>
            <p><?php echo $album["album_name"] ?></p>
            <small><?php echo $album["artist_name"] ?></small>
        </blockquote>
        <div><img src="<?php echo "/music/{$album['artist_name']}/{$album['album_name']}/{$album['image']}" ?>" class="img-rounded album-image"></div>
        <div class="slide songs">
            <table class="table table-bordered table-hover table-condensed table-striped">
                <?php foreach ($album["songs"] as $song): ?>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["id"] ?>"><?php echo $song["name"] ?></label></td>
                        <td style="text-align:center"><button class="btn btn-mini song-play" type="button" songid="<?php echo $song["id"] ?>"><i class="icon-headphones"></i></button></td>
                        <td style="text-align:center"><button class="btn btn-mini song-add" type="button" songid="<?php echo $song["id"] ?>"><i class="icon-plus"></i></button></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <div class="btn-group">
                <button class="btn reverse-check">Reverse Check</button>
                <button class="btn check-all">Check All</button>
                <button class="btn uncheck-all">Uncheck All</button>
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary song-list"><i class="icon-list icon-white"></i></button>
            <button album_id="<?php echo $album["id"] ?>" class="btn btn-primary album-play"><i class='icon-music icon-white'></i> Play</button>
        </div>
    </div>
<?php endforeach ?>

<?php include dirname(__FILE__) . '/../pagination.php'; ?>
