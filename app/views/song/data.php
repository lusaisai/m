<div class="songs">
    <table class="table table-bordered table-hover table-condensed table-striped">
        <?php foreach ($data as $song): ?>
            <tr>
                <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["id"] ?>">
                    <?php echo $song["song_name"] ?>
                    <?php if ( $song["is_hot"] ): ?>
                            <span class="badge badge-hot">Hot</span>
                    <?php endif ?>
                </label></td>
                <td><?php echo $song['artist_name']; ?></td>
                <td><?php echo $song['album_name']; ?></td>
                <td style="text-align:center"><button class="btn btn-default btn-xs song-play" type="button" songid="<?php echo $song["id"] ?>"><span class="glyphicon glyphicon-headphones"></span></button></td>
                <td style="text-align:center"><button class="btn btn-default btn-xs song-add" type="button" songid="<?php echo $song["id"] ?>"><span class="glyphicon glyphicon-plus"></span></button></td>
            </tr>
        <?php endforeach ?>
    </table>
    <div class="btn-group">
        <button class="btn btn-default reverse-check">Reverse Check</button>
        <button class="btn btn-default check-all">Check All</button>
        <button class="btn btn-default uncheck-all">Uncheck All</button>
    </div>
    <div class="btn-group">
        <button class="btn btn-primary album-play"><span class="glyphicon glyphicon-music"></span> Play</button>
    </div>
</div>

<?php include dirname(__FILE__) . '/../pagination.php' ?>

<script><?php include dirname(__FILE__) . '/../common.js' ?></script>

