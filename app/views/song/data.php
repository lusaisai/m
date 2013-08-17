<div class="songs">
    <table class="table table-bordered table-hover table-condensed">
        <?php foreach ($data as $song): ?>
            <tr>
                <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["id"] ?>"><?php echo $song["song_name"] ?></label></td>
                <td><?php echo $song['artist_name']; ?></td>
                <td><?php echo $song['album_name']; ?></td>
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
    <div class="btn-group">
        <button class="btn btn-primary album-play"><i class='icon-music icon-white'></i> Play</button>
    </div>
</div>

<?php include dirname(__FILE__) . '/../pagination.php' ?>
