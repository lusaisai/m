<?php foreach ($data as $album): ?>
    <div id="<?php echo $album["id"] ?>" class="album panel panel-primary">
        <div class="panel-heading">
            <h3><?php echo $album["album_name"] ?></h3>
            <h6>-- <?php echo $album["artist_name"] ?></h6>
        </div>
        <div class="panel-body">
        <div>
            <a href="<?php echo "/music/{$album['artist_name']}/{$album['album_name']}/{$album['image']}" ?>" data-lightbox="lightbox-image">
                <img src="<?php echo "/music/{$album['artist_name']}/{$album['album_name']}/{$album['image']}" ?>" class="img-rounded album-image">
            </a>
        </div>
        <div class="slide songs">
            <table class="table table-bordered table-hover table-condensed table-striped">
                <?php foreach ($album["songs"] as $song): ?>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["id"] ?>">
                            <?php echo $song["name"] ?>
                            <?php if ( $song["is_hot"] ): ?>
                                    <span class="badge badge-hot">Hot</span>
                            <?php endif ?>
                        </label></td>
                        <td style="text-align:center"><button class="btn btn-default btn-sm song-play" type="button" songid="<?php echo $song["id"] ?>"><span class="glyphicon glyphicon-headphones"></span></button></td>
                        <td style="text-align:center"><button class="btn btn-default btn-sm song-add" type="button" songid="<?php echo $song["id"] ?>"><span class="glyphicon glyphicon-plus"></span></button></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <div class="btn-group">
                <button class="btn btn-default reverse-check">Reverse Check</button>
                <button class="btn btn-default check-all">Check All</button>
                <button class="btn btn-default uncheck-all">Uncheck All</button>
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary song-list"><span class="glyphicon glyphicon-list"></span></button>
            <button album_id="<?php echo $album["id"] ?>" class="btn btn-primary album-play"><span class="glyphicon glyphicon-music"></span> Play</button>
        </div>
        </div>
    </div>
<?php endforeach ?>

<?php include dirname(__FILE__) . '/../pagination.php'; ?>

<script><?php include dirname(__FILE__) . '/../common.js' ?></script>

